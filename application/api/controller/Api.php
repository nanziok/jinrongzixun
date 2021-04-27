<?php


namespace app\api\controller;


use think\Controller;
use think\db\exception\DataNotFoundException;
use think\Exception;
use think\Request;
use think\Db;

class Api extends Controller
{
    public $config;
    public $user_token;

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->config = Db::name("xitong")->where("id",1)->find()->toCollection();
        //判断登录
        $headers = Request::instance()->header();
        if(in_array('auth',$headers) && !empty($headers['auth'])){
            $info = Db::name("token")
                ->where("token",$headers["auth"])
                ->find()
                ->toCollection();
            if(empty($info)){
                $this->createError("未登录");
            }
            if(bcadd($info["expire_time"],$info["create_time"]) <= time()){
                $this->createError("登录超时");
            }
            $this->user_token = $info;
        }else{
            $this->createError("请登录后访问");
        }
    }

    /**
     * @param int $len
     * @param int $type 类型：1=纯数字，2=纯字母，3=纯小写，4=纯大写，5=字母加数字，6=数字加小字幕，7=数字加大字幕
     * @return string
     */
    public function getStringRand($len=10, $type=1){
        $string1 = '1234567890';
        $string2 = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string3 = 'abcdefghijklmnopqrstuvwxyz';
        $string4 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $string5 = '123456789oabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string6 = '1234567890abcdefghijklmnopqrstuvwxyz';
        $string7 = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        switch ($type){
            case 1:
                $string_sw = $string1;
                break;
            case 2:
                $string_sw = $string2;
                break;

            case 3:
                $string_sw = $string3;
                break;
            case 4:
                $string_sw = $string4;
                break;
            case 5:
                $string_sw = $string5;
                break;
            case 6:
                $string_sw = $string6;
                break;

            case 7:
                $string_sw = $string7;
                break;
        }
        for ($i = 0; $i < $len; $i++) {
            $return .= substr($string_sw, mt_rand(0,strlen($string_sw)),1);
        }
        return $return;
    }
    public function getOpenid(){
        try {
            $user_token = $this->user_token;
            $info = Db::name("third")->where("user_id",$user_token["user_id"])->findOrFail();
            return info["opoenid"];
        }catch (DataNotFoundException $e){
            $this->createError("查无数据");
        }catch (Exception $e){
            $this->createError("获得openid失败");
        }
    }

    /**
     * @param string $msg
     */
    public function createError($msg=''){
        $this->result('',0,$msg,'json');
    }

    /**
     * @param $data
     * @param string $msg
     */
    public function createSuccess($data,$msg=''){
        $this->result($data,0,$msg,'json');
    }

    public function getHost(){
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST'];
    }
}