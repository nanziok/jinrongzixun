<?php
namespace app\admin\controller;
use think\Db;
use think\Exception;
class user extends Base
{


	//会员管理start-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //会员列表
    public function index(){
		$age = [['id'=>1,"name"=>'0~8岁'],['id'=>2,"name"=>'9~10岁'],['id'=>3,"name"=>'11~12岁'],['id'=>4,"name"=>'13~16岁'],['id'=>5,'name'=>'17~18岁'],['id'=>6,'name'=>'18岁以上']];
     	$shili = [['id'=>1,"name"=>'近视'],['id'=>2,"name"=>'远视'],['id'=>3,"name"=>'弱视'],['id'=>4,"name"=>'散光'],['id'=>5,"name"=>'其他']];
     	$tiaoli = [['id'=>1,"name"=>'阶段一'],['id'=>2,"name"=>'阶段二'],['id'=>3,"name"=>'阶段三'],['id'=>4,"name"=>'阶段四']];
		$this->assign('age',$age);
		$this->assign('shili',$shili);
		$this->assign('tiaoli',$tiaoli);
		return view('index');
    }
	//获取会员列表
    public function wdl_index(){
    	$tiaoli = ['未设置','阶段一','阶段二','阶段三','阶段四'];
    	$shili = ['未设置','近视','远视','弱视','散光','其他'];
		$where=[];
		$user_rank=input("user_rank");
		if($user_rank){
			$where['user_rank']=$user_rank;
		}
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
        $age = input('age',0,'intval');
        if($age>0){
        	 if($age == 1){
		        	$where['age'] = ['between',[0,8]];
		        }elseif($age == 2){
		        	$where['age'] = ['between',[9,10]];
		        }elseif($age == 3){
		        	$where['age'] = ['between',[11,12]];
		        }elseif($age == 4){
		        	$where['age'] = ['between',[13,16]];
		        }elseif($age == 5){
		        	$where['age'] = ['between',[17,18]];
		        }else{
		        	$where['age'] = ['gt',18];
		        }
        }
        $shili_a = input('shili',0,'intval');
        $tiaoli_a = input('tiaoli',0,'intval');
        if($shili_a>0){
            $where['shili'] = ['eq',$shili_a];
        }
        if($tiaoli_a>0){
        	$where['tiaoli'] = ['eq',$tiaoli_a];
        }
	    $count = Db::name('user')->where($where)->count();
		$list =  Db::name('user')->where($where)->order("id desc")->paginate(20,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
			   $data[$k]['headimg'] = get_headimg($v['id']);
			   $data[$k]['sex']= get_sex($v['sex']);
			   $data[$k]['username'] = empty($v['username'])?'--':$v['username'];
			   $data[$k]['add_time'] = d($v['add_time']);
			   $data[$k]['realname'] = empty($v['realname'])?'--':$v['realname'];
			   $data[$k]['shili'] = $shili[$v['shili']];
               $data[$k]['tiaoli'] = $tiaoli[$v['tiaoli']];
               $data[$k]['desc'] = '学校['.$v['school'].']-班级['.$v['banji'].']-简介['.$v['desc'].']-地区['.get_region_info($v['province']).get_region_info($v['city']).get_region_info($v['district']).']';
		       $data[$k]['shuju'] = '发布数<b class="red">['.$v['fabu'].'</b>-关注数<b class="red">['.$v['guanzhu'].']</b>-粉丝数<b class="red">['.$v['fensi'].']</b>-点赞数<b class="red">['.$v['zan'].']</b>-获赞数<b class="red">['.$v['get_zan'].']</b>';
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
	//会员发布管理start-------------------------------------------------------------------------------------------------------------------------------------
	//会员发布列表
	public function fabu(){
		return view();
	}
	//获取会员发布列表
	public function wdl_fabu(){
		$start_date = input("start_date");
		$end_date = input("end_date");
        if($start_date){
		  $where['f.add_time'] = ['gt',strtotime($start_date)];
		}
		if($end_date){
		  $where['f.add_time'] = ['between',[1,strtotime($end_date)]];
		}
		if($start_date && $end_date){
		  $where['f.add_time'] = ['between',[strtotime($start_date),strtotime($end_date)]];
		}
		$type = input("type",0,"intval");
		$status = input("status",0,"intval");
		if($type){
			$where['f.type'] = ['eq',$type];
		}
		if($status){
			$where['f.status'] = ['eq',$status];
		}
		$where['is_delete'] = ['eq',0];
	    $count = Db::name('user_fabu')->alias("f")->join("user u","u.id=f.user_id","left")->where($where)->field("f.*,u.phone,u.username")->count();
		$list =  Db::name('user_fabu')->alias("f")->join("user u","u.id=f.user_id","left")->where($where)->field("f.*,u.phone,u.username")->order("f.id desc")->paginate(10,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
			   $data[$k]['username'] = empty($v['username'])?'--':$v['username'];
			   $data[$k]['type_desc'] = $v['type'] == 1?"图片":($v["type"]==2?"视频":"纯文本");
			   $data[$k]['add_time'] = d($v['add_time']);
			   if($v['type'] == 1){
			   	 $data[$k]['type_value'] = explode(',',$v['type_value']);
			   }
		   } 
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
    
	}

	//会员发布删除
	public function wdl_fabu_del(){
		  $id = input("id",0,"intval");
		  $info =  Db::name("user_fabu")->where(array("id"=>$id))->find();
		  if(!$info){
		  	$this->error('信息有误');
		  }
		  Db::startTrans();
            try{
            	    $result1 = Db::name("user_fabu")->where(array("id"=>$id))->delete();
            	    $result2 = true;
            	    if($info['zan']>0){
            	    	$result2 = Db::name("user")->where("id",$info['user_id'])->setDec('get_zan',$info['zan']);
            	    }
	          	    $result3 = true;
	          	    $result6 = true;
	          	    $str_arr = Db::name("user_zan")->where(array("fabu_id"=>$id))->column("user_id");
	          	    if(count($str_arr)>0){
	          	    	$result3 = Db::name("user_zan")->where(array("fabu_id"=>$id))->delete();
	          	    	$result6 = Db::name("user")->where(array("id"=>['in',implode(",",$str_arr)]))->setDec('zan');
	          	    }
	          	    $result7 = true;
	          	    $comment_arr = Db::name("user_fabu_comment")->where(array("fabu_id"=>$id))->count();
	          	    if($comment_arr>0){
	          	    	$result7 = Db::name("user_fabu_comment")->where(array("fabu_id"=>$id))->delete();
	          	    }
		        	 if($result1 && $result2 && $result3 && $result6 && $result7){
		        	 	Db::commit();
		        	 }else{
		            	throw new Exception('删除失败,请稍后重试');
		        	 }

          	} catch (\Exception $e) {
		    // 回滚事务
			    Db::rollback();
				$message= $e -> getMessage();
				$this->error($message);
		    } 
            $this->success("删除成功");
	}
	
	//会员发布删除列表
	public function fabu_del_list(){
		return view();
	}
	//获取会员发布删除列表
	public function wdl_fabu_del_list(){
		$start_date = input("start_date");
		$end_date = input("end_date");
        if($start_date){
		  $where['f.add_time'] = ['gt',strtotime($start_date)];
		}
		if($end_date){
		  $where['f.add_time'] = ['between',[1,strtotime($end_date)]];
		}
		if($start_date && $end_date){
		  $where['f.add_time'] = ['between',[strtotime($start_date),strtotime($end_date)]];
		}
		$type = input("type",0,"intval");
		$status = input("status",0,"intval");
		if($type){
			$where['f.type'] = ['eq',$type];
		}
		if($status){
			$where['f.status'] = ['eq',$status];
		}
		$where['is_delete'] = ['eq',1];
	    $count = Db::name('user_fabu')->alias("f")->join("user u","u.id=f.user_id","left")->where($where)->field("f.*,u.phone,u.username")->count();
		$list =  Db::name('user_fabu')->alias("f")->join("user u","u.id=f.user_id","left")->where($where)->field("f.*,u.phone,u.username")->order("f.id desc")->paginate(10,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
			   $data[$k]['username'] = empty($v['username'])?'--':$v['username'];
			   $data[$k]['type_desc'] = $v['type'] == 1?"图片":($v["type"]==2?"视频":"纯文本");
			   $data[$k]['add_time'] = d($v['add_time']);
			   if($v['type'] == 1){
			   	 $data[$k]['type_value'] = explode(',',$v['type_value']);
			   }
		   } 
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
    
	}
	//会员发布管理end-------------------------------------------------------------------------------------------------------------------------------------
	//会员发布内容评价start-------------------------------------------------------------------------------------------------------------------------------------
    //获取会员发布内容评价列表
   public function wdl_fabu_comment(){
   	 $id = input('id');
   	 $this->assign('id',$id);
   	 
   	 return view();
   }
    //获取会员发布内容评价列表
	public function wdl_fabu_comment_list(){
		$id = input('id');
		$where['c.pid'] = ['eq',0];
		$where['c.fabu_id'] = ['eq',$id];
	    $count = Db::name('user_fabu_comment')->alias("c")->join("user u","u.id=c.user_id","left")->where($where)->field("c.id,c.content,c.add_time,u.phone,u.username")->count();
		$list =  Db::name('user_fabu_comment')->alias("c")->join("user u","u.id=c.user_id","left")->where($where)->field("c.id,c.content,c.add_time,u.phone,u.username")->order("c.id desc")->paginate(20,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
			   $data[$k]['username'] = empty($v['username'])?'--':$v['username'];
			   $data[$k]['add_time'] = d($v['add_time']);
			   $reply = Db::name('user_fabu_comment')->where('pid',$v['id'])->column('content');
			   if(count($reply)>0){
			   	$data[$k]['reply'] = '<b class="red">回复：</b>'.implode('；',$reply); 
			   }else{
			   	$data[$k]['reply'] = '--';
			   }
		   } 
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
	}
	
	
		//会员发布删除
	 public function wdl_fabu_comment_del(){
		  $id = input("id",0,"intval");
		  $info =  Db::name("user_fabu_comment")->where(array("id"=>$id))->find();
		  if(!$info){
		  	$this->error('信息有误');
		  }
		  Db::startTrans();
          try{
            	    $result1 = Db::name("user_fabu_comment")->where(array("id"=>$id))->delete();
            	    $count = Db::name("user_fabu_comment")->where(array("pid"=>$id))->count();
            	    $result2 = true;
            	    if($count>0){
            	    	$result2 = Db::name("user_fabu_comment")->where(array("pid"=>$id))->delete();
            	    }
            	    $new_comment = $count+1;
	          	    $result3 = Db::name("user_fabu")->where(array("id"=>$info['fabu_id']))->setDec('comment',$new_comment);
		        	 if($result1 && $result2 && $result3){
		        	 	Db::commit();
		        	 }else{
		            	throw new Exception('删除失败,请稍后重试');
		        	 }

          	} catch (\Exception $e) {
		    // 回滚事务
			    Db::rollback();
				$message= $e -> getMessage();
				$this->error($message);
		    } 
            $this->success("删除成功");
	}
	//会员发布内容评价end-------------------------------------------------------------------------------------------------------------------------------------

}

