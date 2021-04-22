<?php
namespace app\admin\controller;
use think\Db;
class Guanliyuan extends Base
{


	//管理员管理start-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //管理员列表
    public function index(){
		$cat = Db::name('user_admin_cat')->select();
	    $this->assign('cat',$cat);
		return view('index');
    }
	//获取管理员列表
    public function wdl_index(){
		$map=[];
		$user_cat=input("user_cat");
		if($user_cat){
			$map['user_cat']=$user_cat;
		}
		$search=input("name");
		if ($search) {
            $map['name'] = ['like', '%' . $search . '%'];
        }
	    $count = Db::name('user_admin')->where($map)->count();
		$list =  Db::name('user_admin')->where($map)->order("id desc")->paginate(10,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
			   $data[$k]['user_cat']=Db::name('user_admin_cat')->where("id=".$v['user_cat'])->value('name');
		       $data[$k]['username'] = empty($v['username'])?'--':$v['username'];
		       $data[$k]['phone'] = empty($v['phone'])?'--':$v['phone'];
			   $data[$k]['add_time'] = d($v['add_time']);
		   } 
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));

    }

    //添加管理员
	public function wdl_add(){
		$cat = Db::name('user_admin_cat')->select();
	    $this->assign('cat',$cat);
		return view();
	}
	//处理管理员添加
	public function wdl_add_do(){
		$data=input();
		if(strlen($data['password'])<6){
			$this->error('密码长度至少6位数');
		}
        $find = Db::name('user_admin')->where("name",$data['name'])->find();
		if($find){
			$this->error('【'.$data['name'].'】已存在！');
		}
		$data['password']=md5($data['password']);	
		$data['add_time']=time();
		$do=Db::name('user_admin')->insert($data);
		if($do){
			$this->success("添加成功");
		}else{
			$this->error('添加失败，请稍后重试！');
		}
		
	}
	//管理员编辑
	public function wdl_edit(){
		$id=input("id");
		$data= Db::name('user_admin')->where("id=".$id)->find();
		$this->assign("data",$data);
		
		$cat = Db::name('user_admin_cat')->select();
	    $this->assign('cat',$cat);

		return view();
	}
	//管理员编辑处理
	public function wdl_edit_do(){
		$data=input();
	    $where['id'] = ['neq',$data['id']];
        $where['name'] = ['eq',$data['name']];
		$find = Db::name('user_admin')->where($where)->find();
		if($find){
			$this->error('【'.$data['name'].'】已存在！');
		}
		if($data['password']){
			if(strlen($data['password'])<6){
			  $this->error('密码长度至少6位数');
		    }
			$data['password']=md5($data['password']);	
		}else{
			unset($data['password']);
		}
		$do=Db::name('user_admin')->update($data);
		if($do){
			$this->success("修改成功");
		}else{
			$this->error('没有做出任何修改！');
		}
		
	}
	//管理员删除
	public function wdl_del(){
		$id = input("id");
		if(empty($id)){
			$this->error('请选择要操作的信息！');
		}
		$res = Db::name('user_admin')->where('id='.$id)->delete();
		if($res){
			$this->success("删除成功");
		}else{
			$this->error('删除失败');
		}

	}
	
    //管理员管理end--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//角色管理start-------------------------------------------------------------------------------------------------------------------------------------
	//角色列表
	public function cat(){
		return view("cat");
	}
	//获取角色列表
	public function wdl_cat(){
		$data = Db::name('user_admin_cat')->order('id desc')->select();
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>count($data)));
	}
	//角色添加
	public function wdl_cat_add(){
		return view('cat_add');
	}
	//角色添加处理
	public function wdl_cat_add_do(){
		$data = input();
		$find = Db::name('user_admin_cat')->where("name",$data['name'])->find();
		if($find){
			$this->error($data['name'].'名称已存在！');
		}
	    $data['add_time'] = time();
		$rt = Db::name('user_admin_cat')->insert($data);
		if($rt){
			$this->success('添加成功');
		}else{
			$this->error('添加失败！');
		}
	}
	//角色编辑
	public function wdl_cat_edit(){
		$id = input("id");
		$data =  Db::name('user_admin_cat')->where("id",$id)->find();
		$this->assign('data',$data);
		return view('cat_edit');
	}
	//角色编辑处理
	public function wdl_cat_edit_do(){
		$data = input();
		$where['name'] = ['eq',$data['name']];
		$where['id'] = ['neq',$data['id']];
		$find = Db::name('user_admin_cat')->where($where)->find();
		if($find){
			$this->error($data['name'].'角色已存在！');
		}
		$rt = Db::name('user_admin_cat')->update($data);
		if($rt){
			$this->success('编辑成功');
		}else{
			$this->error('没有修改！');
		}
	}
	//角色删除
	public function wdl_cat_del(){
		$id = input("id");
		if(empty($id)){
			$this->error('请选择要操作的信息！');
		}
		$where['user_cat'] = $id;
		$count = Db::name('user_admin')->where($where)->count();
		if($count>0){
			$this->error('该角色下面有管理员存在,不可删除');
		}
		Db::name('user_admin_cat')->where("id",$id)->delete();
		Db::name('user_admin_auth')->where("role_id",$id)->delete();
		$this->success('操作成功');
	}
	//角色管理end-------------------------------------------------------------------------------------------------------------------------------------
	//角色权限start-------------------------------------------------------------------------------------------------------------------------------------
     //权限列表
	public function wdl_quanxian(){
		$id=input("id");
		$arr_action_id=Db::name("user_admin_auth")->where("role_id=".$id)->column('action_id');
		$data=Db::name("user_admin_action")->where("fid=0")->order("listorder asc")->field('id,name as title')->select();
		foreach($data as $k=>$v){
            $children = Db::name("user_admin_action")->where("fid=".$v['id'])->order("listorder asc")->field('id,name as title')->select();
            if($children){
			   $data[$k]['spread'] = true;
			   foreach($children as $key=>$val){
			      if (in_array($val['id'],$arr_action_id)) { 
			          $children[$key]['checked'] = true;
			      }
			   }
			   $data[$k]['children'] = $children;
			}else{
			  if (in_array($v['id'],$arr_action_id)) { 
			      $data[$k]['checked'] = true;
			  }
			}
		}
			
		$this->assign("data",json_encode($data));
		$this->assign("role_id",$id);
		return view();
	}
	
	//权限授权
	public function wdl_shouquan(){
		$data = input();
		$newdata['role_id']=input("role_id");
		Db::name("user_admin_auth")->where($newdata)->delete();
		unset($data['role_id']);
		foreach($data as $k=>$v){
           $newdata['action_id']=$v;
		   Db::name("user_admin_auth")->insert($newdata);
		}
		$this->success("授权成功！");
	
	}
	//角色权限end-------------------------------------------------------------------------------------------------------------------------------------


}
