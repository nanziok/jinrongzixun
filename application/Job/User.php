<?php


namespace app\Job;


use think\Controller;
use think\queue\Job;
use think\queue;
use think\Db;

class User extends Controller
{

    /**
     * 添加一条普通消息
     */
    private function addOneMsg($params){
        $ret = Db::name("user_msg")->insertGetId($params);
        if($ret){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 用户注册成功后添加推广信息提醒
     * @param array [user_id=>"",from_id=>""]
     */
    public function userInvite(Job $job, $param){
        $temp_data = [
            "user_id"  =>  $param["from_id"],
            "title"    =>  "分享成功通知",
            "content"  =>  "有新的小伙伴通过了您的邀请！",
            "type"     =>  "invite",
            "ext"      =>  0,
            "is_read"  =>  0
        ];
        if($this->addOneMsg($temp_data)){
            $job->delete();
        }else{
            $job->release(3);
        }
    }

    /**
     * 用户下单成功后添加信息提醒
     * @param array [user_id=>"", order_id=>""]
     */
    public function OrderPay(Job $job, $param){
        $temp_data = [
            "user_id"  =>  $param["user_id"],
            "title"    =>  "订单支付成功",
            "content"  =>  "您的订单已经支付成功，可以通过在线咨询或者提交问卷获得详细解答。",
            "type"     =>  "order",
            "ext"      =>  0,
            "is_read"  =>  0
        ];
        if($this->addOneMsg($temp_data)){
            $job->delete();
        }else{
            $job->release(3);
        }
    }

    /**
     * 问卷回复
     */
    public function testLogReply(Job $job, $param){
        $temp_data = [
            "user_id"  =>  $param["user_id"],
            "title"    =>  "收到新的咨询建议",
            "content"  =>  "您于2020-4-22日提交的问题收到新的回复，点击查看详情！",
            "type"     =>  "kyc",
            "ext"      =>  $param["test_log_id"],
            "is_read"  =>  0
        ];
        if($this->addOneMsg($temp_data)){
            $job->delete();
        }else{
            $job->release(3);
        }
    }

    /**
     * 赠送优惠券提醒
     */
    public function endGiveCoupon(Job $job, $param){
        $temp_data = [
            "user_id"  =>  $param["user_id"],
            "title"    =>  "收到新的优惠券",
            "content"  =>  "您的账户获得新的优惠券，点击查看详情",
            "type"     =>  "coupon",
            "ext"      =>  0,
            "is_read"  =>  0
        ];
        if($this->addOneMsg($temp_data)){
            $job->delete();
        }else{
            $job->release(3);
        }
    }

    /**
     * 赠送优惠券后过期任务，延时写入提醒
     */
    public function couponTimeout(Job $job, $param){
        $temp_data = [
            "user_id"  =>  $param["user_id"],
            "title"    =>  "您有优惠券将要过期",
            "content"  =>  "您的优惠券将要在7日内过期，请记得使用哦!点击查看详情",
            "type"     =>  "coupon",
            "ext"      =>  0,
            "is_read"  =>  0
        ];
        if($this->addOneMsg($temp_data)){
            $job->delete();
        }else{
            $job->release(3);
        }
    }
}