<?php


namespace app\api\controller;

use EasyWeChat\Factory;
use think\Db;
use think\Hook;
use think\Queue;

class Pay
{

    /**
     * 微信支付回调
     *
     */
    public function wxNotify(){
        $log_file = RUNTIME_PATH . 'pay_log/' . date('Y_m_d').'.log';
        $message = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        if(empty($message)) $message = file_get_contents("php://input");
        if(empty($message)) exit('no message send');
        @file_put_contents($log_file, '【'.date('Y-m-d H:i:s').'】微信支付回调原始消息'.$message . PHP_EOL, FILE_APPEND);
        $sys_config = Db::name("xitong")->where("id",1)->field("wechat_pay_appid,wechat_pay_merchant,wechat_pay_key")->find();
        $config = [
            // 必要配置
            'app_id'             => $sys_config['wechat_pay_appid'],
            'mch_id'             => $sys_config['wechat_pay_merchant'],
            'key'                => $sys_config['wechat_pay_key']
        ];
        $app = Factory::payment($config);

        $response = $app->handlePaidNotify(function($notify, $successful) use ($log_file){
            $time = time();

            @file_put_contents($log_file, '【'.date('Y-m-d H:i:s').'】微信支付回调消息'.json_encode($notify) . PHP_EOL, FILE_APPEND);
            $out_trade_no = substr($notify["out_trade_no"], 0, strlen($notify["out_trade_no"])-6);
            $order = Db::name('order')->where('order_no', $out_trade_no)->where("status",1)->find();
            if (empty($order)) { // 如果订单不存在 或者 订单已经支付过了
                @file_put_contents($log_file, '【'.date('Y-m-d H:i:s').'】微信支付回调处理失败：查询不到订单:' . $notify["out_trade_no"] . PHP_EOL, FILE_APPEND);
                return false;
            }
            if ($notify['return_code'] !== 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                @file_put_contents($log_file, '【'.date('Y-m-d H:i:s').'】微信支付回调处理失败：通信失败:' . $notify["out_trade_no"] . PHP_EOL, FILE_APPEND);
                return false;
            }
            // 用户是否支付成功
            if ($notify['result_code'] !== 'SUCCESS') {
                @file_put_contents($log_file, '【'.date('Y-m-d H:i:s').'】微信支付回调处理失败：支付未成功:' . $notify["out_trade_no"] . PHP_EOL, FILE_APPEND);
                return false;
            }
            if($notify['cash_fee'] != $notify['total_fee']){
                @file_put_contents($log_file, '【'.date('Y-m-d H:i:s').'】微信支付回调处理失败：实际支付金额和订单金额不等:' . $notify["out_trade_no"] . PHP_EOL, FILE_APPEND);
                return false;
            }
            if($order["service_id"] == 0){
                $config = Db::name("xitong")->where("id",1)->value("jijin_setting");
                $config = json_decode($config);
                $sw_service_time = $config["jijin_service_time"] ?? 'year';  //一年服务时间

            }else{
                $sw_service_time = Db::name("service")->where("id",$order["service_id"])->value("service_time");
            }
            switch ($sw_service_time){
                case 'year':
                    $service_time = 31536000;
                    break;
                case 'month':
                    $service_time = 2592000;
                    break;
                case 'week':
                    $service_time = 604800;
                    break;
                case 'day':
                    $service_time = 86400;
                    break;
                default:
                    $service_time = 31536000;
            }
            $ret = Db::name("order")->where("id", $order["id"])->update([
                "status" => 2,
                "payment_code" => $notify['transaction_id'],
                "pay_time" => $time,
                "update_time" => $time,
                "t_amount" => bcdiv($notify['cash_fee'], 100, 2),
                "service_timeout" => $service_time
            ]);
            $params = [
                "user_id" => $order["user_id"],
                "order_id"=> $order["id"]
            ];
            Queue::push("app\job\User@orderPay",$params,'orderPay');
            if ($ret!==false){
                return false;
            }
            return true;
        });
        $response->send();
    }


}