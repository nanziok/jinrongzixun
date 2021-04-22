<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Base extends Controller
{
	
	 public $admin;
	 public function _initialize(){

			$adminid=cookie("adminid");

			if(!$adminid){
				$this->redirect('admin/login/index');
			}
			$admin=Db::name("user_admin")->field("id,name,user_cat,status")->where("id=".$adminid)->find();
			if(!$admin){
				cookie("adminid",NULL);
				$this->redirect('admin/login/index');

			}
			if($admin['status']== 2){
				cookie("adminid",NULL);
				$this->redirect('admin/login/index');
			}
			$this->admin=$admin;
			$this->assign("admin",$admin);
			$site=Db::name("xitong")->where("id=1")->find();
			$this->site=$site;
			$this->assign("site",$site);

			$request=  \think\Request::instance();
       		$m=$request->controller();
       		$a=$request->action();
			$idarray=Db::name("user_admin_auth")->where("role_id=".$admin['user_cat'])->column('action_id');
            
			if($m == 'Upload' && ($a == 'img' || $a == 'up_do')){
				$auth=true;
			}elseif($m == 'Index' && ($a == 'index'|| $a == 'main')){
			    $auth=true;
			}elseif(stristr($a,'wdl_')){
				$auth=true;
			}else{
				//进行验证
				$id=Db::name("user_admin_action")->where(array('m'=>$m,'a'=>$a))->value('id');
			    if($id){
			    	if(in_array($id,$idarray)){
			    		$auth=true;
			    	}else{
			    		$auth=false;
			    	}
			    }else{
			    	$auth=false;
			    }
			}  
			 if(!$auth){
			 	$this->error("您没有操作该模块的权限！");
			 }
     }
	
}
