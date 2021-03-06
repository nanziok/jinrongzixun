<?php
namespace app\api\controller;
use think\Db;
use think\Cookie;
use think\db\exception\DataNotFoundException;
use think\exception\PDOException;
use think\Session;
use think\Request;
use think\Exception;
use EasyWeChat\Factory;
/**
 * @title 用户中心
 * @description 接口说明
 */
class User extends Api{
    public $user_id;

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->user_id = $this->user_token->user_id;
    }

    /**
     * @title 获得用户信息
     * @description 获得用户信息
     * @author 开发者
     * @url /api/user/getUserInfo
     * @method get
     * @return id:用户uid
     * @return nickname:可能是null值
     * @return headimg:http...xxx.png
     * @return inviteCode:邀请码
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserInfo(){
        $user_info = Db::name("user")->where("id",$this->user_id)->field("id,nickname,headimg,inviteCode")->find();
        $this->createSuccess($user_info,"获取用户信息成功");
    }

    /**
     * @title 订单列表
     * @description 客户订单列表,详情页请传参引用,不再另外提供订单详情接口
     * @author mifan89
     * @url /api/user/orderList
     * @method get
     * @param name:page type:int require:0 default:1 desc:页码
     * @param name:page_size type:int require:0 default:1 desc:页长
     * @param name:status type:int require:0 default:0 desc:订单状态：0=全部，1=待付款，2=服务中，3=服务结束，4=已取消，5=退款中，6=已完成
     * @return list:列表项@
     * @list id:订单ID order_no:订单编号 service_text:咨询师 price:订单总价 status:订单状态 status_text:订单状态(文字说明)
     */
    public function orderList(){
        Db::name("user_msg")->where(["user_id"=>$this->user_id,'type'=>'order'])->update(["is_read"=>1]);
        $page = input("page", 1, "int");
        $page_size = input("page_size", 10, "int");
        $status = input("status",  0, "int");
        $map = [];
        if(in_array($status, [1,2,3,4,5,6])){
            $map["status"] = ["eq", $status];
        }
        $map["user_id"] = $this->user_id;
        $list = Db::name("order")->where($map)
            ->field("id,order_no,service_text,price,status")
            ->order("id desc")
            ->page($page,$page_size)
            ->select();
        $status_list = $this->orderStatusList();
        $list = array_map(function ($item) use ($status_list){
            $item["price"] = '¥'.$item["price"];
            $item["status_text"] = $status_list[$item["status"]] ?? '--';
            return $item;
        },$list);
        $this->createSuccess(compact("list"),"获取订单列表成功");
    }

    /**
     * @title 咨询档案列表(仅返回已答复)
     * @description 咨询档案，就是每次提交kyc的答复信息，我也不知道为什么这么设计
     * @author mifan89
     * @url /api/user/testLogList
     * @method get
     * @param name:page type:int require:0 default:1 desc:页码
     * @param name:page_size type:int require:0 default:1 desc:页长
     * @return list:列表项@
     * @list id:档案ID service_text:咨询师名 review_time:档案生成时间
     */
    public function testLogList(){
        Db::name("user_msg")->where(["user_id"=>$this->user_id,'type'=>'addArchive'])->update(["is_read"=>1]);
        $page = input("page", 1, "int");
        $page_size = input("page_size", 10, "int");
//        $list = Db::name("service_test_log")->where("status", 2)
//            ->field("id,service_text,review_time")
//            ->order("id desc")
//            ->page($page,$page_size)
//            ->select();
//        $list = array_map(function ($item){
//            $item["create_time"] = d($item["create_time"]);
//            $item["review_time"] = d($item["review_time"]);
//            return $item;
//        },$list);
        $list = Db::name("user_archive")
            ->where("user_id",$this->user_id)
            ->field("id,user_id,title as service_text,create_time as review_time")
            ->order("id desc")
            ->page($page,$page_size)
            ->select();
        $list = array_map(function ($item){
            $item["review_time"] = d($item["review_time"]);
            return $item;
        },$list);
        $this->createSuccess(compact("list"),"获取咨询档案成功");
    }

    /**
     * @title 咨询档案详情
     * @description 咨询档案，就是每次提交kyc的答复信息，我也不知道为什么这么设计
     * @author mifan89
     * @url /api/user/testLogInfo
     * @method get
     * @param name:id type:int require:1 default: desc:档案id
     * @return info:详情@!
     * @info id:ID service_text:营养师名 review_time:档案创建时间 notes:档案内容（富文本）
     */
    public function testLogInfo(){
        $id = input("id", 0 , "int");
//        $info = Db::name("service_test_log")->where("id", $id)
//            ->field("id,service_text,review_time,notes")
//            ->find();
//        if(!empty($info)) {
//            $info["notes"] = str_replace("/uploads/", $this->base_url . '/uploads/', $info["notes"]);
//            $info["review_time"] = d($info["review_time"]);
//        }
        $info = Db::name("user_archive")->where("id", $id)
            ->field("id,title as service_text,create_time as review_time, content as notes")
            ->find();
        if(!empty($info)) {
            $info["notes"] = str_replace("/uploads/", $this->base_url . '/uploads/', $info["notes"]);
            $info["review_time"] = d($info["review_time"]);
        }
        $this->createSuccess(compact("info"),"获取咨询档案成功");
    }

    /**
     * @title 我的kyc列表
     * @description  kyc列表，产品图展示两个，可能是为了配合重新提交问卷功能
     * @author mifan89
     * @url /api/user/kycList
     * @method get
     * @param name:page type:int require:0 default:1 desc:页码
     * @param name:page_size type:int require:0 default:1 desc:页长
     * @return list:列表项@
     * @list tech_id:咨询师ID（用于获取问卷） order_id:订单id（用于提交问卷） name:咨询师名 tags:标签（半角逗号分割） status:当前状态（已筛选三种状态：2=服务中心，3=服务结束，6=已完成）
     * @return top_image:kyc顶部介绍图片
     */
    public function kycList(){
        $page = input("page", 1, "int");
        $page_size = input("page_size", 10, "int");
        $map = [
            "o.status" => ["in", [2,3,6]],
            "o.user_id" => ['eq', $this->user_id]
        ];
        $field = "o.id as order_id, o.service_id as tech_id, o.status";
        $list = Db::name("order")->alias("o")
            ->join("service s", "s.id=o.service_id", "left")
            ->where($map)
            ->field($field)
            ->order("o.id desc")
            ->page($page, $page_size)
            ->select();
        $list = array_map(function ($item){
            if($item["tech_id"] == 0){
                $item["name"] = '基金咨询';
                $item["tags"] = '基金咨询';
            }else{
                $service_info = Db::name("service")->where("id", $item["tech_id"])->find();
                $item["name"] = $service_info["name"];
                $item["tags"] = implode(' ', explode(",", $service_info["tags"]));
            }
            return $item;
        },$list);
        $top_image = Db::name("xitong")->where("id", 1)->value("kyc_top_image");
        $top_image = $this->base_url . $top_image;
        $this->createSuccess(compact("list","top_image"),"获取kyc列表成功");
    }



    /**
     * @title 我的消息
     * @description 我的消息列表
     * @author mifan90 --2021-05-12
     * @url /api/user/myMsg
     * @method get
     * @return msg:消息@
     * @msg id:ID title:正文 content:内容 description:简介 type:类型（'base'普通消息,'invite'邀请成功消息,'order'订单消息,'kyc'kyc变动通知,'coupon'优惠券通知,'addArchive'咨询档案） ext:附加字段模型关联字段 is_read:是否已读
     * @throws DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function myMsg(){
        $tips = [];
        $sql = Db::name("user_msg")
            ->where(["user_id"=>$this->user_id])
            ->order("is_read asc, id desc")
            ->buildSql(true);
        $msg = Db::table($sql.' a')->field("a.id,a.title,a.content,a.type,a.ext,a.is_read,a.create_time")->group("a.type")->select();
        $list = array_map(function ($item){
            $item["create_time"] = day($item["create_time"]);
            $item["description"] = mb_substr($item["content"], 0, 72);
            return $item;
        }, $msg);
        $this->createSuccess(compact("list"), "我的消息");
    }
    /**
     * @title 设置已读消息
     * @description 展示消息的时候调用
     * @author mifan89
     * @url /api/user/readMyMsg
     * @mrthod get
     * @param name:ids type:string require:1 default: desc:消息ids，多个半角逗号分隔
     */
    public function readMyMsg(){
        $ids = input("ids","");
        $ret = Db::name("user_msg")->where("id","int",explode(",",$ids))->update(["id_read"=>1]);
        if($ret === false){
            $this->createError("设置已读失败");
        }else{
            $this->createSuccess("", "设置已读失败");
        }
    }

    /**
     * @title 获取分享规则（文本）
     * @description 获取分享规则（文本）
     * @author none
     * @url /api/user/getShareContent
     * @method get
     * @return content:分享规则（文本）
     */
    public function getShareContent(){
        $content = Db::name("xitong")->where("id",1)->value("share_rule_des");
        $content = str_replace('/uploads/', $this->base_url . '/uploads/', $content);
        $this->createSuccess(compact("content"),"");
    }

    /**
     * @title 获取推广成功列表
     * @description 获取通过我的二维码注册的人员列表
     * @author mifan89
     * @url /api/user/getPromotionList
     * @method get
     * @param name:page type:int require:0 default:1 desc:页码
     * @param name:page_size type:int require:0 default:1 desc:页长
     * @return list:推广成员列表@
     * @list nickname:昵称（敏感信息已隐藏） add_time:邀请时间戳（秒）
     */
    public function getPromotionList(){
        Db::name("user_msg")->where(["user_id"=>$this->user_id,'type'=>'invite'])->update(["is_read"=>1]);
        $page = input("page", 1, "int");
        $page_size = input("page_size", 10, "int");
        $list = Db::name("user")
            ->where("from_id", $this->user_id)
            ->field("nickname,add_time")
            ->page($page,$page_size)->select();
        $list = array_map(function ($item){
            $item["nickname"] = mb_substr($item["nickname"],0,1) . '***' . mb_substr($item["nickname"],-1);
            $item["add_time"] = d($item["add_time"]);
            return $item;
        },$list);
        $this->createSuccess(compact("list"), "获取推广成功列表成功");
    }

    /**
     * @title 用户邀请码
     * @description 生成小程序码,页面地址可携带参数，以供二维码落地页使用
     * @author none
     * @url /api/user/getShareImg
     * @method get
     * @param name:path type:string require:1 default: desc:小程序【当前正式版本已存在的】页面地址，可携带参数
     * @return pic:/public_html...png
     * @return share_title:自定义分享标题
     * @return share_thumb:自定义分享图片
     */
    public function getShareImg(){
        $path = input("path","");
        $user_info = Db::name("user")->where("id", $this->user_id)->findOrFail();
        $config = Db::name("xitong")->where("id",1)->find();
        $share_title = "来自". $user_info["nickname"] ."的分享";
        $share_thumb = $config["share_thumb"] ?: '/static/manage/images/index/share_thumb.jpg';
        $share_thumb = $this->base_url . $share_thumb;
        $path = $path ?: '/pages/index/index?inviteCode=' . $user_info["inviteCode"];

        $options = [
            'app_id'    => $config["wechat_mp_appid"],
            'secret'    => $config["wechat_mp_secret"],
            'response_type' =>  'array',
            'log' => [
                'level' => 'error',
                'file'  => RUNTIME_PATH . 'log/'.date('Ymd').'/wechat_debug.log',
            ],
        ];
        $file_name = base64_encode($path) . '.png';
        if(file_exists(ROOT_PATH . '/public_html/uploads/uQrcode' . DS . $file_name)){
            $this->createSuccess(["pic"=>$this->base_url . '/uploads/uQrcode/'.$file_name, 'title'=>$share_title, 'thumb'=>$share_thumb, 'path'=>$path],"获取小程序码成功");
        }
        try {

            $app = Factory::miniProgram($options);
            $response = $app->app_code->getQrCode($path, 150);
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                $filename = $response->saveAs(ROOT_PATH . '/public_html/uploads/uQrcode', $file_name);
                $this->createSuccess(["pic"=>$this->base_url . '/uploads/uQrcode/'.$file_name, 'title'=>$share_title, 'thumb'=>$share_thumb, 'path'=>$path],"获取小程序码成功");
            }
            $this->createError("生成小程序码失败: ".$response["errmsg"]);
        }catch (Exception $e){
            $this->createError("生成小程序码失败");
        }
    }

    /**
     * @title 生成订单
     * @description 用户下单
     * @author 不存在
	 * @url /api/user/creatOrder
	 * @method post
     * @param name:tech_id type:int require:1 default:0 desc:咨询师字段，默认0基金咨询
     * @param name:coupon_id type:int require:0 default:0 desc:优惠券id,付款前可更改
     * @param name:amount type:int require:0 default:0 desc:客户购买基金金额，只有基金资讯下单时，必填
     * @return id:订单id
     * @return order_no:订单编号
     */
    public function creatOrder(){
        $service_id = input("tech_id", 0,"int");
        $coupon_id = input("coupon_id", 0,"int");
        if($service_id > 0) {
            $service_info = Db::name("service")->where("id", "$service_id")->where("status", 1)->find();
            if (empty($service_info)) $this->createError("选择的咨询师不存在或者不可用");
            $service_price = $service_info["price"];
            $service_name  = $service_info["name"];
        }else{
            $amount = input("amount", 0,"int");
            $service_name = '基金咨询';
            $jijin_setting = Db::name("xitong")->where("id", 1)->value("jijin_setting");
            $config = json_decode($jijin_setting, true);
            //{"jijin_switch":"percent","jijin_fee_percent":"1","jijin_fee_ladder1":"","jijin_fee_ladder2":"","jijin_fee_ladder3":"","jijin_fee_ladder4":"","jijin_fee_ladder5":"","jijin_fee_ladder6":""}
            if($config["jijin_switch"] == 'percent'){
                $service_price = bcmul($amount, bcdiv(floatval($config["jijin_fee_percent"]),100, 2), 2);
            }else if($config["jijin_switch"] == 'ladder'){
                $service_price = $config["jijin_fee_ladder1"];
                if($amount > 10000){
                    $service_price = $config["jijin_fee_ladder2"];
                }
                if($amount > 30000){
                    $service_price = $config["jijin_fee_ladder3"];
                }
                if($amount > 50000){
                    $service_price = $config["jijin_fee_ladder4"];
                }
                if($amount > 100000){
                    $service_price = $config["jijin_fee_ladder5"];
                }
                if($amount > 200000){
                    $service_price = $config["jijin_fee_ladder6"];
                }
            }
        }
        $temp_data = [
            "order_no"  =>  $this->getOrderNo(2),
            "user_id"   =>  $this->user_id,
            "service_id"=>  $service_id,
            "service_text"=> $service_name,
            "price"     =>  $service_price,
            "c_amount"  =>  $service_price,
            "t_amount"  =>  0,
            "pay_type"  =>  "wechat",
            "payment_code" =>  "",
            "coupon_id" =>  $coupon_id,
            "status"    =>  "1",
            "create_time"=> time()
        ];
        $ret = Db::name("order")->insertGetId($temp_data);
        if($ret === false){
            $this->createError("生成订单失败");
        }else{
            $this->createSuccess(["id"=>$ret,"order_no"=>$temp_data["order_no"]], "生成订单成功");
        }
    }

    /**
     * @title 订单详情
     * @description 获取订单详情，应用场景：对未付款订单重新付款，对应用优惠券订单刷新
     * @author mifan89
     * @url /api/user/orderInfo
     * @method get
     * @param name:order_id type:int require:0 desc:订单id，和订单编号二选一，至少有一个必填
     * @param name:order_no type:string require:0 desc:订单编号，和订单id二选一，至少有一个必填

     * @return id:订单ID
     * @return order_no:订单编号
     * @return price:订单总价
     * @return c_amount:应付款(优惠后)
     *
     * @return t_amount:实付款
     * @return payment_code:支付平台订单号
     * @return pay_type:支付方式
     * @return pay_type_text:支付方式(文字说明)
     * @return pay_time:支付时间
     * @return create_time:订单生成时间
     * @return coupon_id:优惠券ID
     * @return service_text:咨询师名
     * @return service_timeout:咨询有效期

     * @return status:订单状态
     * @return status_text:订单状态(文字说明)
     */
    public function orderInfo(){
        $order_id = input("order_id",0,"int");
        $order_no = input("order_no","");
        $status_list = $this->orderStatusList();
        $pay_type_list = $this->orderPayTypeList();
        if(!empty($order_id) || !empty($order_no)) {
            if ($order_id > 0) {
                $info = Db::name("order")->where("id", $order_id)->find();
            } else if($order_no != "") {
                $info = Db::name("order")->where("order_no", $order_no)->find();
            }
            if(empty($info)) $this->createError("没有查询到订单信息");
            $info["c_amount"] = ' ¥ '. $info["c_amount"];
            $info["create_time"] = d($info["create_time"]);
            $info["pay_time"] = d($info["pay_time"]);
            $info["service_timeout"] = d($info["service_timeout"]);
            $info["status_text"] = $status_list[$info["status"]];
            $info["pay_type_text"] = $info["status"]==2 ? $pay_type_list[$info["pay_type"]] : "--";
            $this->createSuccess($info,"获取订单详情");
        }else{
            $this->createError("获取订单详情失败");
        }
    }

    /**
     * @title 订单发起支付
     * @description 订单发起支付 返回组装好的json字符串
     * @author mifan89
     * @url /ap/user/pay
     * @method get
     * @param name:order_id type:int require:1 default: desc:订单id
     * @param name:pay_type type:string require:0 default:wechat desc:支付方式
     * @return config:返回组装好的json字符串@!
     * @config timeStamp: nonceStr: package: signType: paySign:
     */
    public function pay(){
        $order_id = input("order_id", 0,"int");
        $order_info = Db::name("order")->where("id", $order_id)->find();
        $sys_config = Db::name("xitong")->where("id",1)->field("wechat_pay_appid,wechat_pay_merchant,wechat_pay_key")->find();
        $openid = $this->getOpenid();
        $config = [
            // 必要配置
            'app_id'             => $sys_config['wechat_pay_appid'],
            'mch_id'             => $sys_config['wechat_pay_merchant'],
            'key'                => $sys_config['wechat_pay_key']
        ];
        try {
            $app = Factory::payment($config);
            $jssdk = $app->jssdk;
            $result = $app->order->unify([
                'body' => '咨询服务费',
                'out_trade_no' => $order_info["order_no"] . $this->getStringRand(6,6),
                'total_fee' => $order_info["c_amount"] * 100,
                'notify_url' => $this->getHost() . '/index.php/api/pay/wxNotify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
                'openid' => $openid,
            ]);
            if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                $config = $jssdk->sdkConfig($result['prepay_id']);
                $config["timeStamp"] = $config["timestamp"];
                $this->createSuccess(compact("config"),"发起微信支付");
            }else if ($result['return_code'] == 'FAIL' && array_key_exists('return_msg', $result)){
                $this->createError('请求微信服务器失败：'.$result['return_msg']);
            }
            $this->createError('发生意外错误');
        }catch (Exception $e){
            $this->createError("订单发起支付失败");
        }
    }


    /**
     * @title 优惠券列表
     * @description 查看用户的优惠券列表
     * @author mifan89
     * @url /api/user/couponList
     * @method get
     * @param name:page_size type:int require:0 default:10 desc:页大小
     * @param name:page type:int require:0 default:1 desc:页码
     * @param name:status type:int require:0 default:0 desc:状态：0=全部，1=未使用，2=已使用，3=已过期
     * @return list:优惠券列表@
     * @list id:ID name:优惠券名 status:优惠券状态(0=全部，1=未使用，2=已使用，3=已过期) type:优惠券类型（1=满减，2=无条件） fee:优惠券面值 rule:满减使用条件（满减券有效） create_time:赠送时间 end_time:截止时间
     */
    public function couponList(){
        Db::name("user_msg")->where(["user_id"=>$this->user_id,'type'=>'coupon'])->update(["is_read"=>1]);
        $page_size = input("page_size", 10, "int");
        $page = input("page", 1, "int");
        $status = input("status", 0, "int");
        $map = [];
        $map["user_id"] = ["eq", $this->user_id];
        if(in_array($status, [1,2,3])){
            $map["status"] = ["eq", $status];
        }
        $list = Db::name("user_coupon")->where($map)->order("id desc")->page($page,$page_size)->select();
        $list = array_map(function ($item){
            $item["date_region"] = date('Y.m.d',$item["create_time"]) . '-' . date('Y.m.d',$item["end_time"]);
            $item["fee"]  = floatval($item["fee"]);
            $item["rule"] = floatval($item["fee"]);
            $item["rule_text"] = $item["type"] == 1 ? "满 {$item["rule"]} 减 {$item["fee"]}" : "直减 {$item["fee"]}";
            $item["type_text"] = $item["type"] == 1 ? "满减券" : "通用券";
//            $item["fee_text"] = '¥'.$item["fee"];

            return $item;
        },$list);
        if ($status == 0){
            $list_waiting = [];
            $list_used = [];
            $list_timeout = [];
            foreach ($list as $val){
                if($val["status"] == 1){
                    array_push($list_waiting, $val);
                }
                if($val["status"] == 2){
                    array_push($list_used, $val);
                }
                if($val["status"] == 3){
                    array_push($list_timeout, $val);
                }
            }
            $this->createSuccess(compact("list_waiting","list_used","list_timeout"),"优惠券列表");
        }
        $this->createSuccess(compact("list"),"优惠券列表");
    }

    /**
     * @title 使用优惠券
     * @description 对已有订单使用优惠券
     * @author 不知道
     * @url /api/user/useCoupon
     * @method get
     * @param name:order_id require:1 default:0 desc:订单id
     * @param name:coupon_id require:1 default:0 desc:优惠券id
     */
    public function useCoupon(){
        $order_id = input("order_id",0,"int");
        $coupon_id = input("coupon_id", 0, "int");
        if($order_id<1 || $coupon_id<1){
            $this->createError("提交参数有误");
        }
        try {
            $map = [
                "id"      => $coupon_id,
                "user_id" => $this->user_id,
                "status"  => 1,
            ];
            $coupon_info = Db::name("user_coupon")->where($map)->findOrFail();
            $order_info = Db::name("order")->where("id",$order_id)->findOrFail();
            if ($coupon_info["status"] != 1){
                throw new Exception("优惠券不可使用");
            }
            if ($coupon_info["end_time"] <time()){
                throw new Exception("优惠券已过期");
            }
            if($coupon_info["type"] == 1){
                $coupon_rule = explode("-", $coupon_info["rule"]);
                if($order_info["price"] <$coupon_info[0] ){
                    throw new Exception("优惠券不满足使用条件");
                }
            }
            $ret = Db::name("order")->where("id",$order_id)->update(["coupon_id"=>$coupon_id, "c_amount"=>bcsub($order_info["price"],$coupon_info["fee"], 2)]);
            if($ret === false){
                $this->createError("应用优惠券失败");
            }else{
                $this->createSuccess("应用优惠券成功");
            }
        }catch (DataNotFoundException $e){
            $this->createError("查找信息失败");
        }catch (PDOException $e){
            $this->createError("数据库操作失败");
        }catch (Exception $e){
            $this->createError("优惠券不满足使用条件");
        }

    }

    /**
     * @title 获取问卷
     * @description 1:使用这个接口前务必判断登陆态，登陆之后才能使用 2:咨询师和问卷是一对多，所以返回的是权重最大的一个
     * @auth 不知道
     * @url /api/user/getTestPaper
     * @method get
     * @param name:tech_id type:int require:1 default: other: desc:咨询师id
     * @return id:问卷id
     * @return tech_id:咨询师ID
     * @return name:问卷标题
     * @return content:问卷内容@
     * @content xuhao:序号(int) type:类型[radio|checkbox] timu:题目(string) tinei:选项(数组)
     */
    public function getTestPaper(){
        $service_id = input("tech_id");
        $info = Db::name("service_test")->where("service_id", $service_id)->where("status", 1)->field("id,service_id as tech_id,name,content")->order("weigh desc")->find();
        if(empty($info)){
            $this->createError("获取问卷失败");
        }
        $info["content"] = json_decode($info["content"], true);

        $info["content"] = array_map(function($item){
            $i = ord('A');
            $item["tinei"] = array_map(function($item2) use (&$i){
                if(!empty($item2)){
                    $item2 = chr($i) . '：'. $item2;
                    $i++;
                    return $item2;
                }
            }, array_filter(explode(PHP_EOL, $item["tinei"])));
            $item["answer"] = [];
            return $item;
        }, $info["content"]);
        $this->createSuccess($info,"获取问卷成功");
    }

    /**
     * @title 提交问卷
     * @description 注意为了避免url长度溢出，使用post方法
     * @author mifan89
     * @url /api/user/postTestPaper
     * @method post
     * @param name:test_id require:1 default: desc:问卷id
     * @param name:order_id require:1 default: desc:订单id
     * @param name:content require:1 default: desc:试卷内容，和获取问卷的content结构类似，每一题添加answer=int[]字段，后台没有完整性校验
     */
    public function postTestPaper($msg = '')
    {
        $test_id = input("test_id", 0,"int");
        $order_id = input("order_id", 0,"int");
        $content = input("content", "");

        $test_info = Db::name("service_test")->alias("t")
            ->join("ke_service s", "s.id=t.service_id", "left")
            ->field("t.id,t.name as test_text,t.service_id, s.name as service_text")
            ->where("t.id", $test_id)
            ->find();
        if(empty($test_info)){
            $this->createError("提交数据不合法");
        }
        if($test_info["service_id"] == 0){
            $test_info["service_text"] = '基金咨询';
        }
        $this->checkOrder($order_id);
        $this->checkTestData($content);
        $temp_data = [
            "user_id"   =>   $this->user_id,
            "order_id"  =>   $order_id,
            "service_id"=>   $test_info["service_id"],
            "service_test_id" => $test_id,
            "content" => $content,
            "notes" => "",
            "create_time" => time(),
            "status"  =>  1,
            "service_text" => $test_info["service_text"],
        ];
        $ret = Db::name("service_test_log")->insert($temp_data);
        if($ret === false){
            $this->createError("提交失败");
        }else{
            $this->createSuccess("", "提交成功");
        }
    }

    /**
     * @title 用户意见反馈
     * @description 用户投诉意见
     * @author mifan89
     * @url /api/user/tousu
     * @method post
     * @param name:content type:string  require:1 default: desc:用户反馈内容
     */
    public function tousu(){
        $content = input("post.content", "");
        $data = [
            "user_id" => $this->user_id,
            "content" => $content,
            "phone"   =>  "",
            "img"     =>  "",
            "add_time"=>  time(),
            "mark"    =>  "",
            "status"  =>  1
        ];
        $ret = Db::name("user_yijian")->insert($data);
        if($ret){
            $this->createSuccess("", "提交成功");
        }
        $this->createError("提交失败");
    }

    /**
     * 虽然有安全问题，就这样吧
     * @param $data
     */
    private function checkTestData($data) {
        try {
            if(!is_array($data)){
                $data = json_decode($data, true);
            }
            foreach ($data as $val){
                if(empty($val["timu"]) || empty($val["tinei"]) || empty($val["answer"])){
                    throw new Exception("数据校验失败");
                }
//                if(count(explode(PHP_EOL, $val["tinei"]))>1 ||)
            }

        }catch (Exception $e){
            $this->createError("数据校验失败, 请检查是否有未作答的题目");
        }

    }

    /**
     * 检查订单和问卷发起人是否一致
     * @param int $order_id
     */
    private function checkOrder($order_id=0){
        $order_id = $order_id ?: input("order_id", 0,"int");
        if($order_id>0){
            $map = [
                "user_id"  =>  $this->user_id,
                "id"       =>  $order_id
            ];
            $info = Db::name("order")->where($map)->find();
            if(empty($info)){
                $this->createError("提交数据不合法");
            }
        }

    }

    /**
     * 生成订单编号
     * @param int type 1=数字加字母，2=纯数字
     * @return string
     * @throws Exception
     */
    private function getOrderNo($type=1){
		if($type == 1){
			$return = date('Ymd');
			$return .= $this->getStringRand(6,5);
		}else{
			$return = date('Ymd');
			$return .= $this->getStringRand(6,1);
		}
        
        $ret = Db::name("order")->where("order_no",$return)->find();
        if(empty($ret)){
            return $return;
        }else{
            return $this->getOrderNo($type);
        }
    }

    private function orderStatusList(){
        return [
            "1"  => "待付款",
            "2"  => "服务中",
            "3"  => "服务结束",
            "4"  => "已取消",
            "5"  => "退款中",
            "6"  => "已完成",
        ];
    }

    private function orderPayTypeList(){
        return [
            "wechat"  =>  "微信",
        ];
    }

}