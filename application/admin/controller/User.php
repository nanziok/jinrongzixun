<?php
namespace app\admin\controller;
use think\Db;
use think\Exception;
use think\Queue;

class user extends Base
{


	//会员管理start-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //会员列表
    public function index(){
		return view('index');
    }
	//获取会员列表
    public function wdl_index(){
		$where=[];
		$phone=input("phone");
		if ($phone) {
            $where['phone'] = ['like', '%' . $phone . '%'];
        }
        $start_date = input("start_date");
		$end_date = input("end_date");
        if($start_date){
		  $where['add_time'] = ['gt',strtotime($start_date)];
		}
		if($end_date){
		  $where['add_time'] = ['between',[1,strtotime($end_date)]];
		}
		if($start_date && $end_date){
		  $where['add_time'] = ['between',[strtotime($start_date),strtotime($end_date)]];
		}

	    $count = Db::name('user')->where($where)->count();
		$list =  Db::name('user')->where($where)->order("id desc")->paginate(20,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
			   $data[$k]['headimg'] = get_headimg($v['id']);
			   $data[$k]['sex'] = get_sex($v['sex']);
			   $data[$k]['username'] = empty($v['username'])?'--':$v['username'];
			   $data[$k]['add_time'] = d($v['add_time']);
			   $data[$k]['realname'] = empty($v['realname'])?'--':$v['realname'];
		   }
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
    }
    //添加会员
	public function wdl_add(){
		return view();
	}
	//处理会员添加
	public function wdl_add_do(){
		$data=input();
		if(!is_phone($data['phone'])){
			$this->error('请填写正确格式的手机号');
		}
		if(strlen($data['password'])<6){
			$this->error('密码长度至少6位数');
		}
        $find = Db::name('user')->where("phone",$data['phone'])->find();
		if($find){
			$this->error('手机用户【'.$data['phone'].'】已存在！');
		}
		$data['password']=md5($data['password']);	
		$data['add_time']=time();
		$do=Db::name('user')->insert($data);
		if($do){
			$this->success("添加成功");
		}else{
			$this->error('添加失败，请稍后重试！');
		}
		
	}
	//会员编辑
	public function wdl_edit(){
		$id=input("id");
		$data= Db::name('user')->where("id=".$id)->find();
		$this->assign("data",$data);

		return view();
	}
	//管理员编辑处理
	public function wdl_edit_do(){
		$data=input();
		if(!is_phone($data['phone'])){
			$this->error('请填写正确格式的手机号');
		}

	    $where['id'] = ['neq',$data['id']];
        $where['phone'] = ['eq',$data['phone']];
		$find = Db::name('user')->where($where)->find();
		if($find){
			$this->error('手机用户【'.$data['phone'].'】已存在！');
		}
        
		if($data['password']){
			if(strlen($data['password'])<6){
			  $this->error('密码长度至少6位数');
		    }
			$data['password']=md5($data['password']);	
		}else{
			unset($data['password']);
		}
		$do=Db::name('user')->update($data);
		if($do){
			$this->success("修改成功");
		}else{
			$this->error('没有做出任何修改！');
		}
		
	}
	//会员删除
	public function wdl_del(){
		$id = input("id");
		if(empty($id)){
			$this->error('请选择要操作的信息！');
		}
		$res = Db::name('user')->where('id='.$id)->delete();
		if($res){
			$this->success("删除成功");
		}else{
			$this->error('删除失败');
		}

	}

    //会员管理end--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 赠券
     * @return \think\response\View
     */
    public function wdl_add_coupon(){
        if(request()->isPost()){
            $data = input("post.");
            $data["create_time"] = time();
            $data["status"] = 1;
            $data["end_time"] = strtotime($data["end_time"]);
            if($data["type"] == 1 && $data["rule"]<$data["fee"]){
                $this->error("设置满减活动的满减条件不能小于满减金额");
            }
            $ret = Db::name("user_coupon")->strict(false)->insert($data);
            if($ret === false){
                $this->error("添加失败");
            }
            $params = [
                "user_id" => $data["user_id"]
            ];
            Queue::push("app\job\User@endGiveCoupon", $params);
            $delay = autocomplete -  $data["create_time"] - 7*24*60*60;
            if($delay>0){
                Queue::later($delay, "app\job\User@couponTimeout", $params);
            }
            $this->success("添加成功");
        }else {
            $user_id = input("id", 0,"int");
            $user_text = Db::name("user")->where("id",$user_id)->value("phone");
            $this->assign("user_text", $user_text);
            $this->assign("type_list", $this->couponTypesList());
            return view('give_coupon');
        }
    }

    /**
     * 用户券列表
     */
    public function couponList(){
        $coupon_status_list = $this->couponStatusList();
        $coupon_type_list = $this->couponTypesList();

        if (request()->isPost()) {
            //筛选条件
            $map = [];
            $name = input("name", "");
            $status = input("status", 0, "int");
            $type = input("type", 0, "int");
            if (!empty($name)) {
                $condition = [];
                $condition["username|phone|nickname"] = ["like", "%$name%"];
                $user_ids = Db::name("user")->where($condition)->column("id");
                $map["c.user_id"] = ["in", $user_ids];
            }
            if (in_array($status, [1, 2, 3])) {
                $map["c.status"] = ["eq", $status];
            }
            if (in_array($type, [1, 2])) {
                $map["c.type"] = ["eq", $type];
            }
//            dump($map);
            $count = Db::name("user_coupon")->alias("c")->where($map)->count();
            $list = Db::name("user_coupon")->alias("c")
                ->join("ke_user u", "u.id=c.user_id", "left")
                ->field("c.*,u.phone as user_text")
                ->where($map)
                ->order("id")
                ->paginate(10, false, ["query" => input()]);
            $data = $list->items();

            $data = array_map(function ($item) use ($coupon_status_list, $coupon_type_list) {
                $item["create_time"] = d($item["create_time"]);
                $item["end_time"] = d($item["end_time"]);
                $item["status_text"] = $coupon_status_list[$item["status"]];
                $item["type_text"] = $coupon_type_list[$item["type"]];
                return $item;
            }, $data);
            echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
        }else{
            $this->assign("status_list", $coupon_status_list);
            $this->assign("type_list", $coupon_type_list);
            return view("user_coupon");
        }
    }

    /**
     * 删除一张用户优惠券
     */
    public function wdl_del_coupon(){
        $id = input("id",0,"int");
        $ret = Db::name("user_coupon")->where("id",$id)->delete();
        if($ret !== false){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }

    private function couponStatusList(){
        return [
            "1" => "未使用",
            "2" => "已使用",
            "3" => "已过期",
        ];
    }

    private function couponTypesList(){
        return [
            "1" => "满减券",
            "2" => "无条件优惠券",
        ];
    }


}

