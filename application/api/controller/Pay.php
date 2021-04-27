<?php


namespace app\api\controller;

use EasyWeChat\Factory;
use think\Db;
use think\Hook;
use think\Queue;

class Pay extends Api
{

    /**
     * 微信支付回调
     *
     */
    public function wxNotify(){
        $log_file = RUNTIME_PATH . '/pay_log/' . date('Y_m_d').'.log';
        $sys_config = Db::name("xitong")->where("id",1)->field("wechat_pay_appid,wechat_pay_merchant,wechat_pay_key")->find();
        $openid = $this->getOpenid();
        $config = [
            // 必要配置
            'app_id'             => $sys_config['wechat_pay_appid'],
            'mch_id'             => $sys_config['wechat_pay_merchant'],
            'key'                => $sys_config['wechat_pay_key']
        ];
        $app = Factory::payment($config);

        $response = $app->handleNotify(function($notify, $successful) use ($log_file){
            file_put_contents($log_file, '【'.date('Y-m-d H:i:s').'】微信支付回调'.json_encode($notify), FILE_APPEND);
            $order = Db::name('order')->where('order_no', $notify['out_trade_no'])->where("status",1)->find();
            if (empty($order)) { // 如果订单不存在 或者 订单已经支付过了
                return true;
            }
            if ($notify['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($notify['result_code'] === 'SUCCESS') {
                    //成功处理操作
                    $ret = Db::name("order")->where("id", $order["id"])->update([
                        "status" => 2,
                        "payment_code" => $notify['transaction_id'],
                        "pay_time" => time(),
                        "update_time" => time()
                    ]);
                    $params = [
                        "user_id" => $order["user_id"],
                        "order_id"=> $order["id"]
                    ];
                    Queue::push("app\job\User@orderPay",$params);
                    if ($ret){
                        return true;
                    }else{
                        return false;
                    }
                } elseif ($notify['result_code'] === 'FAIL') { // 用户支付失败
                    return true;
                }
            } else {
                return false;
            }
        });
    }


}