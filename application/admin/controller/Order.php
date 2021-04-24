<?php


namespace app\admin\controller;


use think\Db;
use think\Exception;

class Order extends Base
{

    /**
     * 订单列表
     */
    public function index(){
        if(request()->isPost()){
            $condition = [];
            //筛选条件,订单编号, 咨询师，状态
            $order_no = input("order_no","");
            $tech_name = input("tech_name","");
            $status = input("status",0,"int");
            if(!empty($order_no)){
                $condition["o.order_no"] = ["eq", $order_no];
            }
            if (!empty($tech_name)){
                $ids = Db::name("service")->where("name","like", "%$tech_name%")->column("id");
                if(strpos($tech_name, "基金") !== false){
                    array_push($ids, "0");
                }
                $condition["o.service_id"] = ["in", $ids];
            }
            if(in_array($status,[1,2])){
                $condition["status"] = ["eq", $status];
            }
            $count = Db::name("order")->alias("o")->where($condition)->count();
            $list = Db::name("order")->alias("o")
                ->join("ke_service s", "s.id=o.service_id","left")
                ->join("ke_user u", "u.id=o.user_id","left")
                ->where($condition)->field("o.*, s.name as service_text,u.phone as user_text")->paginate(10,false,["query"=>input()]);
            $data = $list->items();
            if(!empty($data)){
                $data = array_map(function ($item){
                    $item["create_time"] = d($item["create_time"]);
                    $item["update_time"] = d($item["update_time"]);
                    $item["status_text"] = $this->getStatusText($item["status"]);
                    $item["pay_type_text"] = $this->getPayTypeText($item["pay_type"]);
                    $item["pay_time"] = $this->getPayTypeText($item["pay_type"]);
                    $item["test_times"] = Db::name("service_test_log")->where("order_id", $item["id"])->count();
                    return $item;
                },$data);
            }
            echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
        }else{
            $status_list = $this->statusList();
            $this->assign("status_list",$status_list);
            return view();
        }
    }

    public function wdl_edit(){
        if(request()->isPost()){
            $data = input();
            $data["update_time"] = time();
            $ret = Db::name("order")->strict(false)->update($data);
            if($ret===false){
                $this->error("修改失败");
            }else{
                $this->success("修改成功");
            }
        }else{
            $id = input("id",0,"int");
            $row = Db::name("order")->alias("o")
                ->join("ke_service s", "s.id=o.service_id","left")
                ->join("ke_user u", "u.id=o.user_id","left")
                ->where("o.id",$id)
                ->field("o.*,s.name as service_text, u.phone as user_text")
                ->find();
            $row["pay_type_text"] = $this->getPayTypeText($row["pay_type"]);
            $this->assign("status_list", $this->statusList());
            $this->assign("row",$row);
            return view();
        }
    }
    public function wdl_add(){
        die();
    }

    public function wdl_del(){
        if(request()->isAjax()){
            $id =input("id",0,"int");
            if(intval($id)<1){
                $this->error("删除失败");
            }
            $ret = Db::name("order")->where("id",$id)->delete();
            if($ret === false){
                $this->error("删除失败");
            }
            $this->success("删除成功");
        }
    }

    /**
     * 问卷记录列表
     */
    public function kyc_logs(){
        if (request()->isPost()){
            $condition = [];
            //筛选条件, 订单编号 、下单人、咨询师，回复状态
            $order_no = input("order_no","");
            $user_name = input("user_name","");
            $tech_name = input("tech_name","");
            $status = input("status",0,"int");
            if(!empty($order_no)){
                $order_id = Db::name("order")->where("order_no",$order_no)->value("id");
                $condition["l.order_id"] = ["eq", $order_id];
            }
            if(!empty($user_name)){
                $map = [];
                $map["u.username|u.phone|u.nickname"] = ["like", "%{$user_name}%"];
                $user_ids = Db::name("user")->where($map)->column("id");
                $condition["l.user_id"] = ["in", $user_ids];
            }
            if (!empty($tech_name)){
                $ids = Db::name("service")->where("name","like", "%$tech_name%")->column("id");
                if(strpos($tech_name, "基金") !== false){
                    array_push($ids, "0");
                }
                $condition["l.service_id"] = ["in", $ids];
            }
            //1=未解答，2=已解答
            if(in_array($status,[1,2])){
                $condition["l.status"] = ["eq", $status];
            }

            $count = Db::name("service_test_log")->alias("l")->where($condition)->count();
            $list = Db::name("service_test_log")->alias("l")
                ->join("ke_user u","u.id=l.user_id", "left")
                ->join("ke_service s", "s.id=l.service_id", "left")
                ->join("ke_service_test t","t.id=l.service_test_id","left")
                ->join("ke_order o","o.id=l.order_id","left")
                ->field("l.*,u.phone as user_text, s.name as service_text, t.name as service_test_text,o.order_no as order_no, o.status as order_status")
                ->where($condition)->paginate(10,false,["query"=>input()]);
            $data = $list->items();
            if (!empty($data)){
                $data = array_map(function ($item){
                    if($item["service_id"] === 0){
                        $item["service_text"] = "基金咨询";
                    }
                    $item["order_status_text"] = $this->getStatusText($item["order_status"]);
                    $item["create_time"] = d($item["create_time"]);
                    $item["review_time"] = d($item["review_time"]);
                    return $item;
                },$data);
            }
            echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
        }else{

            return view();
        }
    }

    /**
     * 处理问卷信息
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function wdl_edit_log(){
        if(request()->isPost()){

        }else{
            $id = input("id");
            $row = Db::name("service_test_log")->alias("l")
                ->join("ke_user u","u.id=l.user_id","left")
                ->join("ke_service s","s.id=l.service_id","left")
                ->where("l.id", $id)->field("l.id, l.content, l.notes, l.create_time, l.status, l.service_id, u.phone as user_text, s.name as service_text")
                ->find();
//            $row["content"] = json_decode($row["content"], true);
            if($row["service_id"] === 0){
                $row["service_text"] = "基金咨询";
            }
            $this->assign("row", $row);
            return view();
        }
    }
    public function kyc_edit_log(){
        if (request()->isPost()){

        }else{

        }
    }

    public function kyc_del_log(){
        if (request()->isAjax()){

        }
    }

    /**
     * 获得状态标志文本
     * @return array
     */
    private function statusList(){
        return  [
            "1" => "待付款",
            "2" => "服务中",
            "3" => "服务结束    ",
            "4" => "已取消",
            "5" => "退款中",
            "6" => "已完成",
        ];

    }

    private function getStatusText($key){
        $list = $this->statusList();
        return array_key_exists($key,$list) ? $list[$key] : '--';
    }
    /**
     * 生成订单编号
     * @param int type 1=数字加字母，2=纯数字
     * @return string
     * @throws Exception
     */
    private function getOrderNo($type=1){
        $string = '123456789oabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $return = date('Ymd');
        if($type==1) {
            for ($i = 0; $i < 6; $i++) {
                $return .= substr($string, mt_rand(0,strlen($string)),1);
            }

        }else{
            $return .= mt_rand(100000, 999999);
        }
        $ret = Db::name("order")->where("order_no",$return)->find();
        if(empty($ret)){
            return $return;
        }else{
            return $this->getOrderNo($type);
        }
    }

    private function getPayTypeText($key){
        $list = [
            "wechat" => "微信",
            "alipay" => "支付宝",
        ];
        return array_key_exists($key,$list) ? $list[$key] : 0;
    }
}