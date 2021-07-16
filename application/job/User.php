<?php

namespace app\job;


use think\Exception;
use think\Log;
use think\queue\Job;
use think\queue;
use think\Db;
use think\Request;

class User
{
    private $queue_file = '';

    public function __construct(Request $request = null)
    {
        $this->queue_file = RUNTIME_PATH . '/queue_log/' . date('Y_m_d') . '.log';
    }

    /**
     * 添加一条普通消息
     */
    private function addOneMsg($params){
        $ret = Db::name("user_msg")->insert($params);
        if($ret){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param Exception $e
     */
    public function writeLog(Exception $e){
        file_put_contents($this->queue_file, "[".date('Y-m-d H:i:s')."]队列执行失败：". $e->getMessage() . '文件：'.$e->getFile() . '行：'. $e->getLine() . PHP_EOL, FILE_APPEND);
    }

    /**
     * 用户注册成功后添加推广信息提醒
     * @param array [user_id=>"",from_id=>""]
     */
    public function userInvite(Job $job, $param){
        try {
            $temp_data = [
                "user_id" => $param["from_id"],
                "title" => "分享成功通知",
                "content" => "有新的小伙伴通过了您的邀请！",
                "type" => "invite",
                "ext" => 0,
                "is_read" => 0,
                "create_time" => time()
            ];
            $this->addOneMsg($temp_data);
            $job->delete();
        }catch (Exception $e){
            //$job->release(3);
            if($job->attempts() > 2){
                $job->delete();
            }
            $this->writeLog($e);
        }

    }

    /**
     * 用户下单成功后添加信息提醒
     * @param array [user_id=>"", order_id=>""]
     */
    public function orderPay(Job $job, $param){
        try{
            $temp_data = [
                "user_id"  =>  $param["user_id"],
                "title"    =>  "订单支付成功",
                "content"  =>  "您的订单已经支付成功，可以通过在线咨询或者提交问卷获得详细解答。",
                "type"     =>  "order",
                "ext"      =>  0,
                "is_read"  =>  0,
                "create_time" => time()
            ];
            $this->addOneMsg($temp_data);
            $job->delete();
        }catch (Exception $e){
            //$job->release(3);
            if($job->attempts() > 2){
                $job->delete();
            }
            $this->writeLog($e);
        }
    }

    /**
     * 问卷回复
     */
    public function testLogReply(Job $job, $param){
        try {
            $param["create_time"] = array_key_exists("create_time", $param) ? $param["create_time"] : 0;
            $date_w = date('Y-m-d H:i:s', $param["create_time"]);
            $temp_data = [
                "user_id"  =>  $param["user_id"],
                "title"    =>  "收到新的咨询建议",
                "content"  =>  "您于{$date_w}日提交的问题收到新的回复，点击查看详情！",
                "type"     =>  "kyc",
                "ext"      =>  $param["test_log_id"],
                "is_read"  =>  0,
                "create_time" => time()
            ];
            $this->addOneMsg($temp_data);
            $job->delete();
        }catch (Exception $e){
            //$job->release(3);
            if($job->attempts() > 2){
                $job->delete();
            }
            $this->writeLog($e);
        }
    }

    /**
     * 赠送优惠券提醒
     */
    public function endGiveCoupon(Job $job, $param){
        try {
            $temp_data = [
                "user_id"  =>  $param["user_id"],
                "title"    =>  "收到新的优惠券",
                "content"  =>  "您的账户获得新的优惠券，点击查看详情",
                "type"     =>  "coupon",
                "ext"      =>  0,
                "is_read"  =>  0,
                "create_time" => time()
            ];
            $this->addOneMsg($temp_data);
            $job->delete();
        }catch (Exception $e){
            //$job->release(3);
            if($job->attempts() > 2){
                $job->delete();
            }
            $this->writeLog($e);
        }
    }

    /**
     * Created by PhpStorm.
     * Author mifan89
     * Datetime: 2021/7/16 0016 14:30
     * @param Job $job
     * @param $param
     */
    public function couponOverdue(Job $job, $param){
        try {
            $coupon = Db::name("user_coupon")->where("id",$param["id"])->update(["status"=>3]);
            $job->delete();
        }catch (Exception $e){
            //$job->release(3);
            if($job->attempts() > 2){
                $job->delete();
            }
            $this->writeLog($e);
        }
    }

    /**
     * 赠送优惠券快过期提醒，延时写入提醒
     */
    public function couponTimeout(Job $job, $param){
        try {
            $temp_data = [
                "user_id"  =>  $param["user_id"],
                "title"    =>  "您有优惠券将要过期",
                "content"  =>  "您有优惠券将要在7日内过期，请记得使用哦!点击查看详情",
                "type"     =>  "coupon",
                "ext"      =>  0,
                "is_read"  =>  0,
                "create_time" => time()
            ];
            $this->addOneMsg($temp_data);
            $job->delete();
        }catch (Exception $e){
            //$job->release(3);
            if($job->attempts() > 2){
                $job->delete();
            }
            $this->writeLog($e);
        }
    }

    /**
     * 添加档案通知
     */
    public function addArchive(Job $job, $param){
        try {
            $temp_data = [
                "user_id"  =>  $param["user_id"],
                "title"    =>  "您有新的咨询档案",
                "content"  =>  "您有新的咨询档案已经生成!点击查看详情",
                "type"     =>  "archive",
                "ext"      =>  0,
                "is_read"  =>  0,
                "create_time" => time()
            ];
            $this->addOneMsg($temp_data);
            $job->delete();
        }catch (Exception $e){
            if($job->attempts() > 2){
                $job->delete();
            }
            $this->writeLog($e);
        }
    }
}