<?php


namespace app\admin\controller;

use think\Db;
use think\Exception;

class Service extends Base
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function cat(){
        return view();
    }

    public function wdl_cat(){
        //筛选条件
        $status = input("status",0,"int");
        $name = input("name","");
        $condition = [];
        if (!empty($name)){
            $condition["name"] = ["like","%{$name}%"];
        }
        if (in_array($status,[1,2])){
            $condition["status"] = $status;
        }
        $count = Db::name("service")->where($condition)->order("id desc")->count();
        $list = Db::name("service")->field('*')->where($condition)->order("id desc")->paginate(10,false,['query'=>input()]);
        $data = $list->items();
        if($data){
            foreach($data as $k=>$v){
                $data[$k]['create_time'] = d($v['create_time']);
            }
        }
        echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
    }

    public function wdl_add_cat(){
        if(request()->isPost()){
            $data = input();
            $data["status"] = (array_key_exists("status",$data) && in_array($data["status"],[1,2])) ? $data["status"] : 2;
            $data["create_time"] = time();
            $ret = Db::name("service")->strict(false)->insert($data);
            if($ret===false){
                $this->error("添加失败");
            }else{
                $this->success("添加成功");
            }
        }else{
            $this->serviceTimeList();
            return view();
        }
    }

    public function wdl_edit_cat(){
        if(request()->isPost()){
            $data = input();
            $data["status"] = (array_key_exists("status",$data) && in_array($data["status"],[1,2])) ? $data["status"] : 2;
            $data["create_time"] = time();
            $ret = Db::name("service")->strict(false)->update($data);
            if($ret===false){
                $this->error("编辑失败");
            }else{
                $this->success("编辑成功");
            }
        }else{
            $id = input("id",0,"int");
            $row = Db::name("service")->where("id",$id)->find();
            $this->assign("row",$row);
            $this->serviceTimeList();
            return view();
        }
    }

    public function wdl_del_cat(){
        if(request()->isAjax()){
            $id =input("id",0,"int");
            if(intval($id)<1){
                $this->error("删除失败");
            }
            $ret = Db::name("service")->where("id",$id)->delete();
            if($ret === false){
                $this->error("删除失败");
            }
            $this->success("删除成功");
        }
    }

    public function kyc_list(){
        return view();
    }

    public function wdl_kyc_list(){
        //筛选条件
        $condition = [];
        $name = input("name","");
        $tech_name = input("tech_name","");
        $status = input("status",0,"int");
        if(!empty($name)){
            $condition["t.name"] = ["like","%{$name}%"];
        }
        if(!empty($tech_name)){
            $tech_ids = Db::name("service")->where("name","like","%$tech_name%")->column("id");
            $condition["t.service_id"] = ["in",$tech_ids];
        }
        if(in_array($status,[1,2])){
            $condition["t.status"] = ["eq",$status];
        }
        $count = Db::name("service_test")->where($condition)->count();
        $list = Db::name("service_test")->alias("t")
            ->join("ke_service s","s.id=t.service_id","left")
            ->where($condition)
            ->field("t.*,s.name as service_name")
            ->order("id desc")
            ->paginate(10,false,["query"=>input()]);
        $data = $list->items();
        if($data){
            foreach($data as $k=>$v){
//                $data[$k]['create_time'] = d($v['create_time']);
                if($v["service_id"] == 0){
                    $data[$k]['service_name'] = '基金咨询';
                }
            }
        }
        echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
    }

    public function wdl_add_kyc(){
        if(request()->isPost()){
            $data = input();
            $data["status"] = (array_key_exists("status",$data) && in_array($data["status"],[1,2])) ? $data["status"] : 2;
            $this->checkTestItem();
            $if_exits = Db::name("service_test")->where("name",$data["name"])->find();
            if(!empty($if_exits)){
                $this->error("同名问卷已存在，请修改后重试！");
            }
            $ret = Db::name("service_test")->strict(false)->insert($data);
            if($ret === false){
                $this->error("添加失败");
            }
            $this->success("添加成功");
        }else{
            $this->getServices();
            return view();
        }
    }

    public function wdl_edit_kyc(){
        if(request()->isPost()){
            $data = input();
            $data["status"] = (array_key_exists("status",$data) && in_array($data["status"],[1,2])) ? $data["status"] : 2;
            $this->checkTestItem();
            $if_exits = Db::name("service_test")->where("name",$data["name"])->where("id","neq",$data["id"])->find();
            if(!empty($if_exits)){
                $this->error("同名问卷已存在，请修改后重试！");
            }
            $ret = Db::name("service_test")->strict(false)->update($data);
            if($ret === false){
                $this->error("编辑失败");
            }
            $this->success("编辑成功");
        }else{
            $this->getServices();
            $id= input("id",0, "int");
            $row = Db::name("service_test")->where("id", $id)->find();
            $row["content_array"] = json_decode($row["content"],true);
            if(empty($row)) $this->error("查无数据");
            $this->assign("row",$row);
            return view();
        }
    }
    public function wdl_del_kyc(){
        if(request()->isAjax()){
            $id =input("id",0,"int");
            if(intval($id)<1){
                $this->error("删除失败");
            }
            $ret = Db::name("service_test")->where("id",$id)->delete();
            if($ret === false){
                $this->error("删除失败");
            }
            $this->success("删除成功");
        }
    }

    public function jijin(){
        if (request()->isAjax()){
            //过滤条件
            $map = [];
            $name = input("name", "");
            $status = input("status", 0, "int");
            if (!empty($name)){
                $map["name"] = ["like", "%$name%"];
            }
            if(in_array($status, [1,2])){
                $map["status"] = ["eq", $status];
            }
            $count = Db::name("jijin_sub")->where($map)->count();
            $list = Db::name("jijin_sub")
                ->where($map)
                ->order("id desc")
                ->paginate(10, false, ["query"=>input()]);
            $data = $list->items();
            $data = array_map(function($item){
                $item["create_time"] = d($item["create_time"]);
                return $item;
            },$data);
            echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
        }else{
            return view();
        }
    }

    public function wdl_add_jijin(){
        if(request()->isPost()){
            $data = input();
            $data["status"] = (array_key_exists("status",$data) && in_array($data["status"],[1,2])) ? $data["status"] : 2;
            $data["create_time"] = time();
            $ret = Db::name("jijin_sub")->strict(false)->insert($data);
            if($ret===false){
                $this->error("添加失败");
            }else{
                $this->success("添加成功");
            }
        }else{
            return view();
        }
    }

    public function wdl_edit_jijin(){
        if(request()->isPost()){
            $data = input();
            $data["status"] = (array_key_exists("status",$data) && in_array($data["status"],[1,2])) ? $data["status"] : 2;
            $data["create_time"] = time();
            $ret = Db::name("jijin_sub")->strict(false)->update($data);
            if($ret===false){
                $this->error("编辑失败");
            }else{
                $this->success("编辑成功");
            }
        }else{
            $id = input("id",0,"int");
            $row = Db::name("jijin_sub")->where("id",$id)->find();
            $this->assign("row",$row);
            return view();
        }
    }

    public function wdl_del_jijin(){
        if(request()->isAjax()){
            $id =input("id",0,"int");
            if(intval($id)<1){
                $this->error("删除失败");
            }
            $ret = Db::name("jijin_sub")->where("id",$id)->delete();
            if($ret === false){
                $this->error("删除失败");
            }
            $this->success("删除成功");
        }
    }

    private function getServices(){
        $list = Db::name("service")->field("id,name")->order("id asc")->select();
        $this->assign("service_list", $list);
    }

    private function checkTestItem(){
        $test_item = input("content");
        try {
            $content = json_decode($test_item, true);
            if(empty($content)) throw new Exception("问卷必须有问题");
            foreach ($content as $value){
                if(empty($value["timu"]) || empty($value["tinei"])){
                    throw new Exception("问题和选项不能为空");
                }
            }
        }catch (Exception $e){
            $this->error("问卷出题有误[". $e->getMessage(). "]，请检查后重试");
        }
    }

    private function serviceTimeList(){
        $list = [
            "year"   =>  "一年",
            "month"  =>  "一个月",
            "week"   =>  "一周",
            "day"    =>  "一天",
        ];
        $this->assign("service_time_list", $list);
    }


}