<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
class Login extends Controller
{
   
    //登录页面
    public function index(){
    	$site = Db::name("xitong")->find();
    	$this->assign('remember',cookie("remember"));
    	$this->assign('name',cookie("name"));
    	$this->assign('site',$site);
	    return view('index');
    }
	
    //处理登录
	public function login_do(){
		$name=input("name");
		$password=input("password");
		//$captcha=input("captcha");
		if(!$name){
			$this->error("账户名不能为空！");
		}
		if(!$password){
			$this->error("密码不能为空！");
		}
		//if(!$captcha){
		//	$this->error("验证码不能为空！");
		//}
		//if(!captcha_check($captcha)){
		//	$this->error("验证码不正确！");
		//}
		$where['name'] = $name;
		$user=Db::name("user_admin")->where($where)->field("password,status,id")->find();
		if(!$user || empty($user)){
			$this->error("账号不存在！");
		}
		if($user['password'] == md5($password)){
			if($user['status']==2){
				$this->error("账户被禁用！");
			}else{
				cookie("adminid",$user['id']);
				if(input('remember')){
	        	  cookie("remember", 1);	
	        	  cookie("name", $name);	
	        	}else{
	        	  cookie("remember", null);	
	        	  cookie("name", null);
	        	}
				$this->success("登录成功！",url('/index/index'));
			}
		}else{
			$this->error("密码错误！");
		}

	}
	//退出登录
	public function out(){
		cookie("adminid",NULL);
		$this->redirect('admin/login/index');
	}
	
	
	
}
