<?php
namespace app\api\controller;
use think\Db;
use think\Cookie;
use think\Session;
use think\Request;
use think\Exception;
/**
 * swagger: api User
 */
class User extends Base{

	/**
     * post: 获取手机验证码
     * path: getcode
     * method: getcode
	 * param: phone - {tel} 手机号
	 * param: type - {int} 验证码类型1手机号必须存在,验证码登录使用 2手机号不能存在,注册时候使用
     */ 
    public function getcode(){
		$ret['text_desc'] = 'code1发送成功0发送失败|msg提示语';
		$phone=input('phone');
		$type = input('type');
		if(!is_phone($phone)){
			$ret['code']=0;
			$ret['msg']="手机号格式不正确";
			$this->re($ret);
		}
		if($type != 1 && $type != 2){
			$ret['code']=0;
			$ret['msg']="验证码获取类型有误";
			$this->re($ret);
		}
		$id=Db::name("user")->where('phone',$phone)->value('id');
		if($type == 1 && empty($id)){
            $ret['code']=0;
			$ret['msg']="手机用户".$phone."不存在";
			$this->re($ret);
		}
		if($type == 2 && !empty($id)){
            $ret['code']=0;
			$ret['msg']="手机用户".$phone."已经注册";
			$this->re($ret);
		}
		$code=rand(100000,999999);
		cache("code".$phone,$code,600);
		$a = $this->xitong['msg_zhanghao'];
		$b = $this->xitong['msg_secret'];
		$c = $this->xitong['msg_code'];
		$content = sprintf($c,$code);
	   
        $tt = '提交成功';
	    if($tt == '提交成功'){
			$ret['code']=1;
			$ret['msg']='发送成功！'.$code;
			$this->re($ret);
		}else{
			$ret['code']=0;
			$ret['msg']='发送失败,请稍后重试！';
			$this->re($ret);
		}
		
	 }
	 /**
     * post: 注册
     * path: register
     * method: register
	 * param: phone - {tel} 手机号
	 * param: code - {string} 手机验证码
	 * param: pwd - {string} 密码 
     */ 
   	public function register(){
   		if($this->xitong['reg'] == 2){
		 	$ret['code']= 0;
		    $ret['msg']="系统关闭注册通道";
		    $this->re($ret);
		 }
   		$ret['text_desc'] = 'code1注册成功0注册失败|msg提示语';
		$phone=input("phone");
		$code=input("code");
		$pwd=input("pwd");
		if(!is_phone($phone)){
			$ret['code']=0;
			$ret['msg']="手机号格式不正确";
			$this->re($ret);
		}
		if(!$code){
			$ret['code']=0;
			$ret['msg']="手机验证码不能为空";
			$this->re($ret);
		}
		$telcode=cache("code".$phone);
		if(!$telcode){
			$ret['code']=0;
			$ret['msg']="手机验证码已超时";
			$this->re($ret);
		}
		if($code!=$telcode){
			$ret['code']=0;
			$ret['msg']="手机验证码不正确";
			$this->re($ret);
		}
		if(strlen($pwd)<6){
			$ret['code']=0;
			$ret['msg']="密码至少六位数";
			$this->re($ret);
		}
		$user=Db::name("user")->where('phone',$phone)->field('id,status')->find();
		if($user){
			$ret['code']=0;
		    $ret['msg']="手机用户已存在,不可重复注册";
		    $this->re($ret);
		}else{
			$data['password'] = md5($pwd);
			$data['phone'] = $phone;
			$data['add_time'] = time();
			$id = Db::name("user")->insertGetId($data);
			if($id){
				cache("code".$phone,null);
				$ret['code']=1;
		        $ret['msg']="注册成功";
		        $this->re($ret);
			}else{
				$ret['code']=0;
		        $ret['msg']="注册失败,请稍后重试";
		        $this->re($ret);
			}
		}
		
	}
	   /**
     * post: 验证码登录
     * path: login_code
     * method: login_code
	 * param: phone - {tel} 手机号
	 * param: code - {int} 手机验证码
     */ 
   	public function login_code(){
   		$ret['text_desc'] = 'code1登录成功0登录失败|msg提示语|id用户登录ID';
		$phone=input("phone");
		$code=input("code");
		if(!is_phone($phone)){
			$ret['code']=0;
			$ret['msg']="手机号格式不正确";
			$this->re($ret);
		}
		if(!$code){
			$ret['code']=0;
			$ret['msg']="手机验证码不能为空";
			$this->re($ret);
		}
		$telcode=cache("code".$phone);
		if(!$telcode){
			$ret['code']=0;
			$ret['msg']="手机验证码已超时";
			$this->re($ret);
		}
		if($code!=$telcode){
			$ret['code']=0;
			$ret['msg']="手机验证码不正确";
			$this->re($ret);
		}
		$user=Db::name("user")->where('phone',$phone)->field('id,status')->find();
		if($user){
			if($user['status'] == 1){
				cache("code".$phone,null);
				Cookie::set("user_id",$user['id'],180*24*60*60);
				$ret['code']=1;
				$ret['id'] = rand(10000,99999).$user['id'];
				$ret['msg']='登录成功';
				$this->re($ret);
			}else{
				$ret['code']=0;
				$ret['msg']='手机用户'.$phone.'被禁用';
				$this->re($ret);
			}
		}else{
			$ret['code']=0;
		    $ret['msg']="手机用户不存在,请先注册";
		    $this->re($ret);
		}
		
	}
       /**
     * post: 账号密码登录
     * path: login_pwd
     * method: login_pwd
	 * param: phone - {tel} 手机号
	 * param: pwd - {string} 密码
     */ 
   	public function login_pwd(){
   		$ret['text_desc'] = 'code1登录成功0登录失败|msg提示语|id用户登录ID';
		$phone=input("phone");
		$pwd=input("pwd");
		if(!is_phone($phone)){
			$ret['code']=0;
			$ret['msg']="手机号格式不正确";
			$this->re($ret);
		}
		if(!$pwd){
			$ret['code']=0;
			$ret['msg']="密码不能为空";
			$this->re($ret);
		}
		$user=Db::name("user")->where('phone',$phone)->field('id,status,password')->find();
		if($user){
			if($user['password'] == md5($pwd)){
				if($user['status'] == 1){
					Cookie::set("user_id",$user['id'],180*24*60*60);
					$ret['code']=1;
					$ret['id'] = rand(10000,99999).$user['id'];
					$ret['msg']='登录成功';
					$this->re($ret);
				}else{
					$ret['code']=0;
					$ret['msg']='手机用户'.$phone.'被禁用';
					$this->re($ret);
				}
			}else{
				$ret['code']=0;
			    $ret['msg']="密码错误";
			    $this->re($ret);
			}
		}else{
				$ret['code']=0;
			    $ret['msg']="手机用户不存在";
			    $this->re($ret);
		}
		
	}
	 /**
     * post: 获取会员信息
     * path: get_userinfo
     * method: get_userinfo
     */ 
     public function get_userinfo(){
     	$ret['text_desc'] = "code 1正常-1禁用或者异常|phone手机号|headimg头像|status状态1正常2禁用|username昵称|realname真实姓名|sex性别|age年龄|shili视力状态|tiaoli调理进度|school学习|banji班级|desc简介|desc_short简介简写|province省份|city城市|district县区|guanzhu关注数|fensi粉丝数|zan点赞数|getzan获赞数|fabu发布数";
    	$this->common();
    	$data = Db::name("user")->where("id",$this->user['id'])->field('id,status,phone,username,realname,sex,age,shili,tiaoli,school,banji,desc,province,city,district,guanzhu,fensi,zan,get_zan,fabu')->find();
        $data['headimg'] = get_headimg($this->user['id']);
        $shili = ['未设置','近视','远视','弱视','散光','其他'];
        $data['shili'] = $shili[$data['shili']];
        $tiaoli = ['未设置','阶段一','阶段二','阶段三','阶段四'];
        $data['tiaoli'] = $tiaoli[$data['tiaoli']];
        $data['sex_name'] = $data['sex'] == 0?"未设置":($data['sex']==1?"男":"女");
        $data['province_name'] = get_region_info($data['province']);
        $data['city_name'] = get_region_info($data['city']);
        $data['district_name'] = get_region_info($data['district']);
        $data['fabu'] = get_user_fabu($data['id']);
        $data['zan'] = get_user_zan($data['id']);
        if($data['desc']){
        	$data['desc_short'] = mb_substr($data['desc'],0,10,'utf-8');
        }else{
        	$data['desc_short'] = "这个人什么都没有留下";
        }
        $data['username'] = empty($data['username'])?"未设置":$data['username'];
        $data['realname'] = empty($data['realname'])?"未设置":$data['realname'];
        $uid = substr($data['phone'],-6).$data['id'];
        unset($data['id']);
        $ret['code']=1;
 		$ret['data'] = $data;
		$this->re($ret);
    }
    /**
     * post: 退出登录
     * path: loginOut
     * method: loginOut
     */ 
	 public function loginOut(){
		Cookie::delete("user_id");
		$ret['code']=1;
		$ret['msg']="成功退出！";
		$this->re($ret);
	}
    /**
     * post: 设置头像
     * path: set_headimg
     * method: set_headimg
     * param: file - {file} 头像
     */
	 public function set_headimg(){
	    $this->common();
        $file = request()->file('file');
        if (empty($file)) {
             $ret['code']=0;
             $ret['msg']='您没有上传，请上传!';
             $this->re($ret);
         }
         $savePath =  './uploads/head/';
         $info = $file->move($savePath);
         if ($info) {
            $url= $savePath . $info->getSaveName();
            $ret['code']=1;
            $ret['msg']='上传成功!';
            $image = \think\Image::open($url);
		    $image->thumb(200, 200)->save($url,'jpg');
            $url = str_replace("./","/",$url);
            Db::name('user')->where('id='.$this->user['id'])->update(array("headimg"=>$url));
            $this->re($ret);
         }else{
             $ret['code']=0;
             $ret['msg']='上传失败!';
             $this->re($ret);
         }
	 }
	 /**
     * post: 设置昵称
     * path: set_username
     * method: set_username
     * param: username - {string} 昵称
     */
	 public function set_username(){
	    $this->common();
        $name = input('username');
        if (strlen($name)<4 ||strlen($name)>30) {
             $ret['code']=0;
             $ret['msg']='昵称范围在4~30个字符间!';
             $this->re($ret);
         }
         $id = Db::name('user')->where('username',$name)->value('id');
         if($id && $id != $this->user['id']){
         	 $ret['code']=0;
             $ret['msg']='昵称已存在';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('username'=>$name));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	$ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 
	 }
    /**
     * post: 设置真实姓名
     * path: set_realname
     * method: set_realname
     * param: realname - {string} 真实姓名
     */
	 public function set_realname(){
	    $this->common();
        $name = input('realname');
        if (strlen($name)<4 ||strlen($name)>30) {
             $ret['code']=0;
             $ret['msg']='真实姓名范围在4~30个字符间!';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('realname'=>$name));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	$ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 }
 
    /**
     * post: 设置性别
     * path: set_sex
     * method: set_sex
     * param: sex - {int} 性别 1男 2女
     */
	 public function set_sex(){
	     $this->common();
         $sex = input('sex');
         if($sex != 1 && $sex != 2){
         	 $ret['code']=0;
             $ret['msg']='上传信息有误';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('sex'=>$sex));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	 $ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 
	 } 
 
    /**
     * post: 设置年龄
     * path: set_age
     * method: set_age
     * param: age - {int} 年龄 1~100之间的正数
     */
	 public function set_age(){
	     $this->common();
         $age = input('age');
         if($age>100){
         	 $ret['code']=0;
             $ret['msg']='上传信息有误';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('age'=>$age));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	 $ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 } 
 
    /**
     * post: 设置视力健康状态
     * path: set_shili
     * method: set_shili
     * param: shili - {int} 视力健康状态状态1近视2远视3弱视4散光5其他
     */
	 public function set_shili(){
	     $this->common();
         $shili = input('shili',0,'intval');
         if($shili>5 || $shili == 0){
         	 $ret['code']=0;
             $ret['msg']='上传信息有误';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('shili'=>$shili));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	 $ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 } 
     /**
     * post: 设置视力调理进度
     * path: set_tiaoli
     * method: set_tiaoli
     * param: tiaoli - {int}视力调理进度1阶段一2阶段二3阶段三4阶段四
     */
	 public function set_tiaoli(){
	     $this->common();
         $tiaoli = input('tiaoli',0,'intval');
         if($tiaoli>4 || $tiaoli == 0){
         	 $ret['code']=0;
             $ret['msg']='上传信息有误';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('tiaoli'=>$tiaoli));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	 $ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 } 
 
      /**
     * post: 设置学校
     * path: set_school
     * method: set_school
     * param: school - {str}学校名称
     */
	 public function set_school(){
	     $this->common();
         $school = input('school');
        if (strlen($school)<4 ||strlen($school)>100) {
         	 $ret['code']=0;
             $ret['msg']='学校信息不符合规定';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('school'=>$school));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	 $ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 } 
   /**
     * post: 设置班级
     * path: set_banji
     * method: set_banji
     * param: banji - {str}班级名称
     */
	 public function set_banji(){
	     $this->common();
         $banji = input('banji');
        if (strlen($banji)<4 ||strlen($banji)>100) {
         	 $ret['code']=0;
             $ret['msg']='班级信息不符合规定';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('banji'=>$banji));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	 $ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 } 
    /**
     * post: 设置简介
     * path: set_desc
     * method: set_desc
     * param: desc - {str}简介信息
     */
	 public function set_desc(){
	     $this->common();
         $desc = input('desc');
        if (strlen($desc)<4 ||strlen($desc)>200) {
         	 $ret['code']=0;
             $ret['msg']='简介信息不符合规定';
             $this->re($ret);
         }
         $result = Db::name('user')->where('id',$this->user['id'])->update(array('desc'=>$desc));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	 $ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
	 } 
 
    
    /**
     * post: 设置所在地
     * path: set_address
     * method: set_address
     * param: province - {int} 省
     * param: city - {int} 市
     * param: district - {int} 县区
     */ 
     public function set_address() {
     	$this->common();
    	$province = input('province',0,'intval');
    	$city = input('city',0,'intval');
    	$district = input('district',0,'intval');
    	
    	if($province == 0 || $city == 0 || $district == 0){
    		$ret['code']=0;
			$ret['msg']='所在地信息不完整';
			$this->re($ret);
    	}
    	$result = Db::name('user')->where('id',$this->user['id'])->update(array('province'=>$province,'city'=>$city,'district'=>$district));  
         if($result){
         	 $ret['code']=1;
             $ret['msg']='设置成功';
             $this->re($ret);
         }else{
         	 $ret['code']=0;
             $ret['msg']='没有更改';
             $this->re($ret);
         }
    	
    }
 
     /**
     * post: 意见反馈
     * path: user_yijian
     * method: user_yijian
     * param: content - {str} 反馈内容
     * param: phone - {str} 联系方式
     * param: file - {file} 图片 最多三张file0,file1,file2命名
     */ 
    
    public function user_yijian(){
		    $this->common();
			$content = input('content','','strval');
			$phone = input('phone','','strval');
	        $savePath =  './uploads/yijian/';
	        $count = Db::name('user_yijian')->where("user_id",$this->user['id'])->whereTime('add_time', 'today')->count();
		 	if($count>0){
		 		$ret['code'] = 0;
	 	    	$ret['msg'] = '对不起,今天您已经提交过了';
	 	    	$this->re($ret);
		 	}
	        if(strlen($content)<10 ||strlen($content)>200){
	        	$ret['code']=0;
				$ret['msg']= "意见内容必须在10~200个字符串之间";
				$this->re($ret);	
	        }
	        if(strlen($phone)>20){
	        	$ret['code']=0;
				$ret['msg']= "联系方式不可大于20个字符串";
				$this->re($ret);	
	        }
        	$imgstr = array();
        	for($a=0;$a<3;$a++){
        		 $file = request()->file('file'.$a);
        		 if($file){
        		 	$info = $file->move($savePath);
			         if ($info) {
			         	 $name = $info->getSaveName();
			             $url= $savePath.$name;
			             $image = \think\Image::open($url);
						 $image->save($url,'jpg');
	
			             $url = str_replace("./","/",$url);
			             array_push($imgstr,$url);
			         }
        		 }
		        		 
		     }  	
		      if(count($imgstr)>0){
		      	$data["img"]=implode(",",$imgstr);
		      } 
		      $data['content'] = $content;
		      $data['phone'] = $phone;
		      $data['user_id'] = $this->user["id"];
		      $data['add_time'] = time();
              $id = Db::name('user_yijian')->insertGetId($data);
		 	  if($id){
		 	    	$ret['code'] = 1;
		 	    	$ret['msg'] = '提交成功';
		 	  }else{
		 	    	$ret['code'] = 0;
		 	    	$ret['msg'] = '提交失败,请稍后重试';
		 	  }
		 	  $this->re($ret);
    }
    /**
     * post: 修改登录密码
     * path: edit_pwd
     * method: edit_pwd
	 * param: code - {string} 手机验证码
	 * param: pwd - {string} 密码 
     */ 
   	public function edit_pwd(){
   		$this->common();
		$code=input("code");
		$pwd=input("pwd");
		if(!$code){
			$ret['code']=0;
			$ret['msg']="手机验证码不能为空";
			$this->re($ret);
		}
		$phone = $this->user['phone'];
		$telcode=cache("code".$phone);
		if(!$telcode){
			$ret['code']=0;
			$ret['msg']="手机验证码已超时";
			$this->re($ret);
		}
		if($code!=$telcode){
			$ret['code']=0;
			$ret['msg']="手机验证码不正确";
			$this->re($ret);
		}
		if(strlen($pwd)<6){
			$ret['code']=0;
			$ret['msg']="密码至少六位数";
			$this->re($ret);
		}
		$result=Db::name("user")->where('id',$this->user['id'])->update(array("password"=>md5($pwd)));
		if($result){
			$ret['code']=1;
		    $ret['msg']="修改成功";
		    $this->re($ret);
		}else{
			$ret['code']=0;
	        $ret['msg']="请输入与原密码不一致的密码";
	        $this->re($ret);
		}
		
	}
    
    
    /**
     * post: 发布动态
     * path: user_fabu
     * method: user_fabu
     * param: content - {str} 发布内容
     * param: type - {int} 1图片 2视频 3纯文本 
     * param: file - {file} 图片 最多三张file0,file1,file2命名 视频就是file
     */ 
    
    public function user_fabu(){
		    $this->common();
			$content = input('content','','strval');
			$type = input('type',0,'intval');
	        $savePath =  './uploads/fabu/';
	        if(strlen($content)<10 ||strlen($content)>500){
	        	$ret['code']=0;
				$ret['msg']= "发布内容必须在10~500个字符串之间";
				$this->re($ret);	
	        }
	        if($type != 1 && $type != 2 && $type != 3){
	        	$ret['code']=0;
				$ret['msg']= "发布类型有误";
				$this->re($ret);	
	        }
	        $count = Db::name('user_fabu')->where("user_id",$this->user['id'])->whereTime('add_time', 'today')->count();
		 	if($count == 5){
		 		$ret['code'] = 0;
	 	    	$ret['msg'] = '对不起,每天最多发布5篇内容';
	 	    	$this->re($ret);
		 	}
		  Db::startTrans();
          try{
			        if($type == 1){
			        	$imgstr = array();
			        	for($a=0;$a<3;$a++){
			        		 $file = request()->file('file'.$a);
			        		 if($file){
			        		 	$info = $file->move($savePath);
						         if ($info) {
						         	 $name = $info->getSaveName();
						             $url= $savePath.$name;
						             $image = \think\Image::open($url);
									 $image->save($url,'jpg');
				
						             $url = str_replace("./","/",$url);
						             array_push($imgstr,$url);
						         }
			        		 }
					        		 
					     }  	
					      if(count($imgstr)>0){
					      	$data["type_value"]=implode(",",$imgstr);
					      } 
			        	  $data['type'] = 1;
			        }elseif($type == 2){
			        		 $file = request()->file('file0');
			        		 if($file){
			        		 	$info = $file->move($savePath);
						         if ($info) {
						         	 $name = $info->getSaveName();
						             $url= $savePath.$name;
						             $url = str_replace("./","/",$url);
						             $data["video"]=$url;
						         }
			        		 }
					          $file1 = request()->file('file1');
			        		 if($file1){
			        		 	$info = $file1->move($savePath);
						         if ($info) {
						         	 $name = $info->getSaveName();
						             $url= $savePath.$name;
						             $url = str_replace("./","/",$url);
						             $data["type_value"]=$url;
						         }
			        		 }		 
					         $data['type'] = 2;
			        	
			        }else{
			        	$data['type'] = 3;
			        }
        	
				    $data['content'] = $content;
				    $data['user_id'] = $this->user["id"];
				    $data['add_time'] = time();
		            $id = Db::name('user_fabu')->insertGetId($data);
		            $result = Db::name('user')->where("id",$this->user["id"])->setInc("fabu");
				 	if($id && $result){
				 	    	$ret['code'] = 1;
				 	    	$ret['msg'] = '提交成功';
				 	    	Db::commit();
				 	    	$this->re($ret);
				 	}else{
				 	    	throw new Exception('提交失败,请稍后重试');
				 	}
		 	  
		 	  } catch (\Exception $e) {
			    Db::rollback();
			    $ret['code']=0;
				$ret['msg']= $e -> getMessage();
				$this->re($ret);
		    } 
 	  
    }
    
    
	/**
     * post: 获取个人关注信息列表
     * path: get_user_guanzhu
     * method: get_user_guanzhu
     * param: start - {int} 起始ID
     */ 
	function get_user_guanzhu(){
		  $ret['text_desc'] = 'id关注记录ID|headimg头像|username昵称|sex性别1男2女0未设置|fabu记录|fensi粉丝|area区域|is_guanzhu是否关注|relative_id被关注人ID';
		  $this->common();
	      $start = input('start',0,'intval');
          $where['user_id'] = ['eq',$this->user['id']];
	      $list = Db::name("user_guanzhu")->where($where)->order('id desc')->field('id,relative_id')->limit($start,10)->select();
		  if($list){
		  	foreach($list as $k=>$v){
		  		$info = Db::name("user")->where("id",$v["relative_id"])->field("sex,fabu,fensi,city,district")->find();
		  		$list[$k]['headimg'] = get_headimg($v['relative_id']);
		  		$list[$k]['username'] = get_username($v['relative_id']);
		  		$list[$k]['sex'] = $info['sex'];
		  		$list[$k]['fabu'] = get_user_fabu($v['relative_id']);
		  		$list[$k]['fensi'] = $info['fensi'];
		  		$list[$k]['area'] = get_region_info($info['city']).'-'.get_region_info($info['district']);
		  		$list[$k]['is_guanzhu'] = 1; 
		  		$list[$k]['relative_id'] = rand(10000,99999).$v['relative_id']; 
		  	}
		  	$ret['code'] = 1;
		  	$ret['data'] = $list;
		  }else{
		  	$ret['code'] = 0;
		  	$ret['msg']= '到底了';
		  }
		  $this->re($ret);
	}
	
	/**
     * post: 取消关注
     * path: quxiao_user_guanzhu
     * method: quxiao_user_guanzhu
     * param: id - {int} 关注用户ID
     */ 
	function quxiao_user_guanzhu(){
		  $this->common();
	      $id = input('id',0,'intval');
          $where['user_id'] = ['eq',$this->user['id']];
          $where['relative_id'] = ['eq',substr($id,5)];
          $info = Db::name("user_guanzhu")->where($where)->field("relative_id")->find();
          if(!$info){
          	$ret['code']=0;
	        $ret['msg']="此关注信息不存在";
	        $this->re($ret);
          }
          Db::startTrans();
          try{
	          	$result1 = Db::name("user_guanzhu")->where($where)->delete();
	          	$result2 = Db::name("user")->where("id",$this->user['id'])->setDec("guanzhu");
	          	$result3 = Db::name("user")->where("id",$info['relative_id'])->setDec("fensi");
	          	if($result1 && $result2 && $result3){
		 	    	$ret['code'] = 1;
		 	    	$ret['msg'] = '取消关注成功';
		 	    	Db::commit();
		 	    	$this->re($ret);
			 	}else{
			 	    throw new Exception('取消关注失败,请稍后重试');
			 	}
           } catch (\Exception $e) {
			    Db::rollback();
			    $ret['code']=0;
				$ret['msg']= $e -> getMessage();
				$this->re($ret);
		    } 

	}
	
    /**
     * post: 添加关注
     * path: add_user_guanzhu
     * method: add_user_guanzhu
     * param: relative_id - {int} 关注对象ID
     */ 
	function add_user_guanzhu(){
		  $this->common();
	      $relative_id = input('relative_id',0,'intval');
	      $relative_id = substr($relative_id,5);
	      $this->check_userinfo($relative_id);
	      if($relative_id == $this->user['id']){
          	$ret['code']=0;
	        $ret['msg']="自己不能关注自己哦";
	        $this->re($ret);
          }
          $where['user_id'] = ['eq',$this->user['id']];
          $where['relative_id'] = ['eq',$relative_id];
          $info = Db::name("user_guanzhu")->where($where)->field("id")->find();
          if($info){
          	$ret['code']=0;
	        $ret['msg']="已经关注过了";
	        $this->re($ret);
          }
          Db::startTrans();
          try{
	          	$result1 = Db::name("user_guanzhu")->insertGetId(array("user_id"=>$this->user['id'],"relative_id"=>$relative_id,"add_time"=>time()));
	          	$result2 = Db::name("user")->where("id",$this->user['id'])->setInc("guanzhu");
	          	$result3 = Db::name("user")->where("id",$relative_id)->setInc("fensi");
	          	if($result1 && $result2 && $result3){
		 	    	$ret['code'] = 1;
		 	    	$ret['msg'] = '关注成功';
		 	    	Db::commit();
		 	    	$this->re($ret);
			 	}else{
			 	    throw new Exception('关注失败,请稍后重试');
			 	}
           } catch (\Exception $e) {
			    Db::rollback();
			    $ret['code']=0;
				$ret['msg']= $e -> getMessage();
				$this->re($ret);
		    } 
	}
	
	/**
     * post: 获取个人粉丝列表
     * path: get_user_fensi
     * method: get_user_fensi
     * param: start - {int} 起始ID
     */ 
	function get_user_fensi(){
		  $ret['text_desc'] = '粉丝记录ID|user_id粉丝用户ID|headimg头像|username昵称|sex性别1男2女0未设置|fabu记录|fensi粉丝|area区域|is_guanzhu1已关注0未关注';
		  $this->common();
	      $start = input('start',0,'intval');
          $where['relative_id'] = ['eq',$this->user['id']];
	      $list = Db::name("user_guanzhu")->where($where)->order('id desc')->field('id,user_id')->limit($start,10)->select();
		  if($list){
		  	foreach($list as $k=>$v){
		  		Db::name("user_guanzhu")->where(array("id"=>$v['id'],"is_read"=>0))->update(array("is_read"=>1));
		  		$info = Db::name("user")->where("id",$v["user_id"])->field("sex,fabu,fensi,city,district")->find();
		  		$list[$k]['headimg'] = get_headimg($v['user_id']);
		  		$list[$k]['username'] = get_username($v['user_id']);
		  		$list[$k]['sex'] = $info['sex'];
		  		$list[$k]['fabu'] = get_user_fabu($v['user_id']);
		  		$list[$k]['fensi'] = $info['fensi'];
		  		$list[$k]['user_id'] = rand(10000,99999).$v['user_id'];
		  		$list[$k]['area'] = get_region_info($info['city']).'-'.get_region_info($info['district']);
		  		$id =  Db::name("user_guanzhu")->where(array("user_id"=>$this->user["id"],"relative_id"=>$v['user_id']))->value('id');
		  		$list[$k]['is_guanzhu'] = $id>0?1:0;
		  	}
		  	$ret['code'] = 1;
		  	$ret['data'] = $list;
		  }else{
		  	$ret['code'] = 0;
		  	$ret['msg']= '到底了';
		  }
		  $this->re($ret);
	}
	
	/**
     * post: 获取个人点赞列表
     * path: get_user_zan
     * method: get_user_zan
     * param: start - {int} 起始ID
     */ 
	function get_user_zan(){
		  $ret['text_desc'] = 'headimg头像|username昵称|sex性别1男2女0未设置|area区域|type1图片2视频3文字|type_value图片视频值|video视频地址|comment评论数|zan点赞数|content内容|add_time发布时间';
		  $this->common();
	      $start = input('start',0,'intval');
          $where['z.user_id'] = ['eq',$this->user['id']];
          $where['f.status'] = ['eq',1];
          $where['f.is_delete'] = ['eq',0];
	      $list = Db::name("user_zan")->alias("z")->join("user_fabu f","f.id=z.fabu_id")->where($where)->order('z.id desc')->field('f.id,f.user_id,f.type,f.type_value,f.video,f.comment,f.zan,f.content,f.add_time')->limit($start,10)->select();
		  if($list){
		  	foreach($list as $k=>$v){
		  		$info = Db::name("user")->where("id",$v["user_id"])->field("sex,city,district")->find();
		  		$list[$k]['headimg'] = get_headimg($v['user_id']);
		  		$list[$k]['username'] = get_username($v['user_id']);
		  		$list[$k]['sex'] = $info['sex'];
		  		$list[$k]['area'] = get_region_info($info['city']).'-'.get_region_info($info['district']);
		  		$list[$k]['add_time'] = date("Y-m-d H:i",$v["add_time"]);
		  		$type_value = $v['type_value'];
		  		if($type_value){
		  			$type_value = explode(',',str_replace("/uploads/",$this->baseurl."/uploads/",$type_value));
		  		}
		  		$list[$k]['type_value'] = $type_value;
		  		$list[$k]['video'] = $v["type"]==2?$this->baseurl.$v['video']:'';
		  	}
		  	$ret['code'] = 1;
		  	$ret['data'] = $list;
		  }else{
		  	$ret['code'] = 0;
		  	$ret['msg']= '到底了';
		  }
		  $ret['baseurl'] = $this->baseurl;
		  $this->re($ret);
	}
	/**
     * post: 获取个人发布列表
     * path: get_user_fabu
     * method: get_user_fabu
     * param: start - {int} 起始ID
     * param: start_time - {int} 开始时间时间戳
     * param: end_time - {int} 结束时间时间戳
     */ 
	function get_user_fabu(){
		  $ret['text_desc'] = 'id发布信息ID|headimg头像|username昵称|sex性别1男2女0未设置|area区域|type1图片2视频|type_value图片数组值|video视频地址|content内容|add_time发布时间|status1审核成功2审核中|comment评论数|zan获赞数';
		  $this->common();
	      $start = input('start',0,'intval');
          $where['user_id'] = ['eq',$this->user['id']];
          $start_time = input("start_time");
          $end_time = input("end_time");
          if($start_time){
		     $where['add_time'] = ['gt',strtotime($start_time)];
		  }
		  if($end_time){
		     $where['add_time'] = ['between',[1,strtotime($end_time)]];
		  }
		  if($start_time && $end_time){
			 $where['add_time'] = ['between',[strtotime($start_time),strtotime($end_time)]];
		  }
		  $where['is_delete'] = ['eq',0];
	      $list = Db::name("user_fabu")->where($where)->order('id desc')->limit($start,10)->select();
	       	
	      $info = get_userinfo($this->user['id']);
		  if($list){
		  	foreach($list as $k=>$v){
		  		$type_value = $v['type_value'];
		  		if($type_value){
		  			$type_value = explode(',',str_replace("/uploads/",$this->baseurl."/uploads/",$type_value));
		  		}
		  		$list[$k]['type_value'] = $type_value;
		  		$list[$k]['video'] = $v["type"]==2?$this->baseurl.$v['video']:'';
		  		$list[$k]['add_time'] = date("Y-m-d H:i",$v["add_time"]);
		  		$list[$k]['username'] = $info['username'];
		  		$list[$k]['headimg'] = $info['headimg'];
		  		$list[$k]['sex'] = $info['sex'];
		  		$list[$k]['area'] = $info['area'];
		  	}
		  	$ret['code'] = 1;
		  	$ret['data'] = $list;
		  }else{
		  	$ret['code'] = 0;
		  	$ret['msg']= '到底了';
		  }
		  $ret['baseurl'] = $this->baseurl;
		  $this->re($ret);
	}
	
	/**
     * post: 获取发布详情
     * path: get_fabu_detail
     * method: get_fabu_detail
     * param: id - {int} 信息ID
     */ 
	
	public function get_fabu_detail(){
		$id = input('id',0,'intval');
		$ret['text_desc'] = 'info用户信息|user_id用户ID|detail_info内容信息zan点赞数comment评论数|is_reply等于1说明是作者看详情可以回复评价0普通人查看|is_zan等于1已点赞0未点赞|zan点赞数|comment评价数';
		$detail_info = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0))->find();
		if(!$detail_info){
			$ret['code'] = 0;
		  	$ret['msg']= '信息不存在';
			$this->re($ret);
		}
		$user_id=Cookie::get("user_id");
		if($user_id && $user_id == $detail_info["user_id"]){
			$ret['is_reply'] = 1;
		}else{
			$ret['is_reply'] = 0;
		}
		if($user_id){
			$zan = Db::name("user_zan")->where(array("user_id"=>$user_id,"fabu_id"=>$id))->value("id");
			$ret['is_zan'] = $zan>0?1:0;
		}else{
			$ret['is_zan'] = 0;
		}
		unset($detail_info["is_delete"]);
		$info = get_userinfo($detail_info['user_id']);
		$detail_info['user_id'] = rand(10000,99999).$detail_info['user_id'];
		$detail_info["add_time"] = date("Y-m-d H:i",$detail_info["add_time"]);
		$type_value = $detail_info['type_value'];
  		if($type_value){
  			$type_value = explode(',',str_replace("/uploads/",$this->baseurl."/uploads/",$type_value));
  		}
  		$detail_info['type_value'] = $type_value;
  		$detail_info['video'] = $detail_info["type"]==2?$this->baseurl.$detail_info['video']:'';
		$ret['code'] = 1;
		$ret['info'] = $info;
		$ret['detail_info'] = $detail_info;
		$ret['baseurl'] = $this->baseurl;
		$this->re($ret);
	}
	/**
     * post: 获取发布详情的评价记录
     * path: get_fabu_detail_comment
     * method: get_fabu_detail_comment
     * param: id - {int} 发布信息ID
     * param: start - {int} 起始ID
     */ 
	
	public function get_fabu_detail_comment(){
		$start = input('start',0,'intval');
		$id = input('id',0,'intval');
		$ret['text_desc'] = 'id评论信息ID|user_id评论者ID|content评论信息|add_time评价时间|user_info评论者信息|reply回复信息';
		$list = Db::name("user_fabu_comment")->where(array("fabu_id"=>$id,'pid'=>0))->field("id,user_id,content,add_time")->order('id desc')->limit($start,10)->select();
		if($list){
		  	foreach($list as $k=>$v){
		  		$list[$k]["user_info"] = get_userinfo($v["user_id"]);
		  		$list[$k]['add_time'] = date("Y-m-d H:i",$v["add_time"]);
		  		$list[$k]['reply'] = Db::name("user_fabu_comment")->where(array("pid"=>$v['id']))->field("content")->select();
		  		$list[$k]['user_id'] = rand(10000,99999).$v['user_id'];
		  	}
		  	$ret['code'] = 1;
		  	$ret['data'] = $list;
		}else{
		  	$ret['code'] = 0;
		  	$ret['msg']= '到底了';
		}
		  $this->re($ret);
	}
	
	
	
    /**
     * post: 发布信息点赞
     * path: user_zan_add
     * method: user_zan_add
     * param: id - {int} 信息ID
     */ 
	function user_zan_add(){
		  $this->common();
	      $id = input('id',0,'intval');
	      $info_fabu = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0,"status"=>1))->field('id,user_id')->find();
	      if(!$info_fabu){
		        $ret['code']=0;
			    $ret['msg']='信息不存在';
			    $this->re($ret);
	       }
	        if($info_fabu['user_id'] == $this->user['id']){
		        $ret['code']=0;
			    $ret['msg']='自己不能给自己点赞哦';
			    $this->re($ret);
	       }
          $where['user_id'] = ['eq',$this->user['id']];
          $where['fabu_id'] = ['eq',$id];
          $info = Db::name("user_zan")->where($where)->field("id")->find();
          if($info){
          	$ret['code']=0;
	        $ret['msg']="已经点赞过了";
	        $this->re($ret);
          }
          Db::startTrans();
          try{
	          	$result1 = Db::name("user_zan")->insertGetId(array("user_id"=>$this->user['id'],"fabu_id"=>$id,"add_time"=>time()));
	          	$result2 = Db::name("user")->where("id",$this->user['id'])->setInc("zan");
	          	$result3 = Db::name("user")->where("id",$info_fabu['user_id'])->setInc("get_zan");
	          	$result6 = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0))->setInc("zan");
	       
	          	if($result1 && $result2 && $result3 && $result6){
		 	    	$ret['code'] = 1;
		 	    	$ret['msg'] = '点赞成功';
		 	    	Db::commit();
		 	    	$this->re($ret);
			 	}else{
			 	    throw new Exception('点赞失败,请稍后重试');
			 	}
           } catch (\Exception $e) {
			    Db::rollback();
			    $ret['code']=0;
				$ret['msg']= $e -> getMessage();
				$this->re($ret);
		    } 
	}
	
	
	/**
     * post: 发布信息点赞取消
     * path: user_zan_delete
     * method: user_zan_delete
     * param: id - {int} 发布信息ID
     */ 
	function user_zan_delete(){
		  $this->common();
	      $id = input('id',0,'intval');
	      $info_fabu = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0))->field('id,user_id')->find();
	      if(!$info_fabu){
		        $ret['code']=0;
			    $ret['msg']='信息不存在';
			    $this->re($ret);
	       }
          $where['user_id'] = ['eq',$this->user['id']];
          $where['fabu_id'] = ['eq',$id];
          Db::startTrans();
          try{
	          	$result1 = Db::name("user_zan")->where($where)->delete();
	          	$result2 = Db::name("user")->where("id",$this->user['id'])->setDec("zan");
	          	$result3 = Db::name("user")->where("id",$info_fabu['user_id'])->setDec("get_zan");
	          	$result6 = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0))->setDec("zan");
	          	if($result1 && $result2 && $result3 && $result6){
		 	    	$ret['code'] = 1;
		 	    	$ret['msg'] = '取消点赞成功';
		 	    	Db::commit();
		 	    	$this->re($ret);
			 	}else{
			 	    throw new Exception('取消点赞失败,请稍后重试');
			 	}
           } catch (\Exception $e) {
			    Db::rollback();
			    $ret['code']=0;
				$ret['msg']= $e -> getMessage();
				$this->re($ret);
		    } 
	}
	/**
     * post: 发布信息评价及回复
     * path: user_comment_add
     * method: user_comment_add
     * param: id - {int} 发布信息ID
     * param: content- {str} 评价信息 
     * param: pid- {int} 评论信息的ID用于回复使用
     */ 
	function user_comment_add(){
		  $this->common();
	      $id = input('id',0,'intval');
	      $pid = input('pid',0,'intval');
	      $content = input('content');
	      if(strlen($content)<2 ||strlen($content)>100){
	      	$ret['code'] = 0;
 	    	$ret['msg'] = '评论信息应在2~100个字符之间';
 	    	$this->re($ret);
	      }
	      $is_read = $pid>0?1:0;
	      $this->check_fabuinfo($id);
          Db::startTrans();
          try{
	          	$result1 = Db::name("user_fabu_comment")->insertGetId(array("user_id"=>$this->user['id'],"fabu_id"=>$id,"content"=>$content,"pid"=>$pid,"add_time"=>time(),"is_read"=>$is_read));
	          	$result6 = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0))->setInc("comment");
	          	if($result1 && $result6){
		 	    	$ret['code'] = 1;
		 	    	$ret['msg'] = '评价成功';
		 	    	Db::commit();
		 	    	$this->re($ret);
			 	}else{
			 	    throw new Exception('评价失败,请稍后重试');
			 	}
           } catch (\Exception $e) {
			    Db::rollback();
			    $ret['code']=0;
				$ret['msg']= $e -> getMessage();
				$this->re($ret);
		    } 
	}
	
	/**
     * post: 发布信息删除
     * path: user_fabu_delete
     * method: user_fabu_delete
     * param: id - {int} 发布信息ID
     */ 
	function user_fabu_delete(){
		  $this->common();
	      $id = input('id',0,'intval');
	      $info = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0,"user_id"=>$this->user['id']))->field('id,add_time,zan')->find();
    	  if(!$info){
	        $ret['code']=0;
		    $ret['msg']='信息不存在';
		    $this->re($ret);
          }
          Db::startTrans();
          try{
          	   if(time()-$info['add_time']<600){
	          	  	$result1 = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0))->delete();
	          	  	$result2 = true;
	          	  	if($info['zan']>0){
	          	  		$result2 = Db::name("user")->where("id",$this->user['id'])->setDec('get_zan',$info['zan']);
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
				 	    	$ret['code'] = 1;
				 	    	$ret['msg'] = '删除完成';
				 	    	Db::commit();
				 	    	$this->re($ret);
					}else{
					 	    throw new Exception('删除失败,请稍后重试');
					}
          	   }else{
          	   	    $result = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0))->update(array("is_delete"=>1));
          	   	    if($result){
				 	    	$ret['code'] = 1;
				 	    	$ret['msg'] = '删除信息提交成功';
				 	    	Db::commit();
				 	    	$this->re($ret);
					}else{
					 	    throw new Exception('删除失败,请稍后重试');
					}
          	   }
	
           } catch (\Exception $e) {
			    Db::rollback();
			    $ret['code']=0;
				$ret['msg']= $e -> getMessage();
				$this->re($ret);
		    } 
	}
	
	
	/**
     * post: 别人对自己发布信息的评论列表
     * path: user_comment_list
     * method: user_comment_list
     * param: start - {int} 起始ID
     */ 
	function user_comment_list(){
		  $this->common();
	      $start = input('start',0,'intval');
	      $where['f.user_id'] = ['eq',$this->user["id"]];
	      $where['f.is_delete'] = ['eq',0];
		  $ret['text_desc'] = 'id|评论ID|fabu_id发布信息ID|user_id评论者ID|headimg评论者头像|username评论者昵称|content评论信息|add_time评价时间|cont发布内容';
		  $list = Db::name("user_fabu_comment")->alias("c")->join("user_fabu f","f.id=c.fabu_id")->where($where)->field("c.id,c.fabu_id,c.user_id,c.content,c.add_time,f.content as cont")->order("c.id desc")->limit($start,10)->select();
		  if($list){
		  	    foreach($list as $k=>$v){
		  	       Db::name("user_fabu_comment")->where(array("id"=>$v['id'],"is_read"=>0))->update(array("is_read"=>1));
		  		   $list[$k]['headimg'] = get_headimg($v["user_id"]);
		  		   $list[$k]['username'] = "用户".get_username($v["user_id"]);
		  		   $list[$k]['add_time'] = date("Y-m-d H:i",$v["add_time"]);
		  		   $list[$k]['cont'] = "#".$v["cont"];
			    }
			  	$ret['code'] = 1;
			  	$ret['data'] = $list;
			}else{
			  	$ret['code'] = 0;
			  	$ret['msg']= '到底了';
			}
		  $this->re($ret);
	
	}
	
	/**
     * post: 别人对自己发布信息的点赞列表
     * path: user_zan_list
     * method: user_zan_list
     * param: start - {int} 起始ID
     */ 
	function user_zan_list(){
		  $this->common();
	      $start = input('start',0,'intval');
	      $where['f.user_id'] = ['eq',$this->user["id"]];
	      $where['f.is_delete'] = ['eq',0];
		  $ret['text_desc'] = 'id点赞记录ID|fabu_id发布信息ID|user_id点赞者ID|headimg点赞者头像|username点赞者昵称|add_time点赞时间|cont发布内容';
		  $list = Db::name("user_zan")->alias("z")->join("user_fabu f","f.id=z.fabu_id")->where($where)->field("z.id,z.fabu_id,z.user_id,z.add_time,f.content as cont")->order("z.id desc")->limit($start,10)->select();
		  if($list){
		  	    foreach($list as $k=>$v){
		  	       Db::name("user_zan")->where(array("id"=>$v['id'],"is_read"=>0))->update(array("is_read"=>1));
		  		   $list[$k]['headimg'] = get_headimg($v["user_id"]);
		  		   $list[$k]['username'] = "用户".get_username($v["user_id"]);
		  		   $list[$k]['add_time'] = date("Y-m-d H:i",$v["add_time"]);
		  		   $list[$k]['cont'] = "#".$v["cont"];
			    }
			  	$ret['code'] = 1;
			  	$ret['data'] = $list;
			}else{
			  	$ret['code'] = 0;
			  	$ret['msg']= '到底了';
			}
		  $this->re($ret);
	
	}
	
	/**
     * post: 最新关注（别人关注自己而自己没有关注别人）
     * path: get_user_newguanzhu
     * method: get_user_newguanzhu
     * param: start - {int} 起始ID
     */ 
	function get_user_newguanzhu(){
		  $ret['text_desc'] = '粉丝记录ID|user_id用户ID|headimg头像|username昵称|sex性别1男2女0未设置|fabu记录|fensi粉丝|area区域|is_guanzhu0未关注固定值';
		  $this->common();
	      $start = input('start',0,'intval');
          $relative_id_arr = Db::name("user_guanzhu")->where("user_id",$this->user['id'])->column("relative_id");
          if(count($relative_id_arr)>0){
          	$where['user_id'] = ['not in',$relative_id_arr];
          }
          $where['relative_id'] = ['eq',$this->user['id']];
	      $list = Db::name("user_guanzhu")->where($where)->order('id desc')->field('id,user_id')->limit($start,10)->select();
		  if($list){
		  	foreach($list as $k=>$v){
		  		Db::name("user_guanzhu")->where(array("id"=>$v['id'],"is_read"=>0))->update(array("is_read"=>1));
		  		$info = Db::name("user")->where("id",$v["user_id"])->field("sex,fabu,fensi,city,district")->find();
		  		$list[$k]['headimg'] = get_headimg($v['user_id']);
		  		$list[$k]['username'] = get_username($v['user_id']);
		  		$list[$k]['sex'] = $info['sex'];
		  		$list[$k]['fabu'] = get_user_fabu($v['user_id']);
		  		$list[$k]['fensi'] = $info['fensi'];
		  		$list[$k]['area'] = get_region_info($info['city']).'-'.get_region_info($info['district']);
		  		$list[$k]['user_id'] = rand(10000,99999).$v["user_id"];
		  		$list[$k]['is_guanzhu'] = 0;
		  	}
		  	$ret['code'] = 1;
		  	$ret['data'] = $list;
		  }else{
		  	$ret['code'] = 0;
		  	$ret['msg']= '到底了';
		  }
		  $this->re($ret);
	}
	
	
	 /**
     * post: 消息里面的系统通知
     * path: get_user_gonggao
     * method: get_user_gonggao
     * param: start - {int} 起始ID
     */ 
     public function get_user_gonggao(){
     	$start = input('start',0,'intval');
     	$this->get_article_list(2,$start);
     }
	
	/**
     * post: 消息里面的系统通知详情
     * path: get_user_gonggao_detail
     * method: get_user_gonggao_detail
     * param: id - {int} 消息ID
     */ 
     public function get_user_gonggao_detail(){
     	$id = input('id',0,'intval');
     	$this->get_article_detail($id);
     }
	
	
	
}