<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Cookie;

class Base extends Controller{
	public $user;
	public $baseurl;
	public $xitong;
	public function re($data){
		 header('Content-Type:application/json; charset=utf-8');
		 $data['text_desc_code'] = "code 1有数据或成功状态 0 无数据或者失败状态 -1登录状态禁用 |msg提示语|data返回数据";
		 //unset($data['text_desc']);
         exit(json_encode($data));
	}
	public function _initialize(){
		 $this->xitong=Db::name("xitong")->where("id=1")->find();
		 if($this->xitong['app'] == 2){
		 	$ret['code']= 0;
		    $ret['msg']=$this->xitong['app_desc'];
		    $this->re($ret);
		 }
		 
	}
	
	public function _empty($name){
		echo 'Hi,no action';
	}
    //验证登录
   public function common(){
		$user_id=Cookie::get("user_id");
		if(!$user_id){
			$ret['code']=-1;
			$ret['msg']='尚未登录！';
			$this->re($ret);
		}
		$where['id'] = $user_id;
		$user=Db::name("user")->where($where)->field('id,status,phone')->find();
		if(!$user){
			Cookie::delete("user_id");
			$ret['code']=-1;
			$ret['msg']='用户不存在！';
			$this->re($ret);
		}else{
			if($user['status'] == 1){
				$this->user=$user;
			}else{
				Cookie::delete("user_id");
				$ret['code']=-1;
			    $ret['msg']='用户被禁用！';
			    $this->re($ret);
			}
		}
    }
    
    //检测用户信息
    function check_userinfo($id){
    	$info = Db::name("user")->where(array("id"=>$id,"status"=>1))->field('id')->find();
    	if(!$info){
	        $ret['code']=0;
		    $ret['msg']='用户信息不存在';
		    $this->re($ret);
        }
        
    }
    //检测发布信息
    function check_fabuinfo($id){
    	$info = Db::name("user_fabu")->where(array("id"=>$id,"is_delete"=>0))->field('id')->find();
    	if(!$info){
	        $ret['code']=0;
		    $ret['msg']='信息不存在';
		    $this->re($ret);
        }
        
    }
	     //获取文章列表
	    function  get_article_list($id,$start=0){
	    	 $ret['text_desc'] = 'id文章ID|title文章标题|cat_name分类名称';
			  $where['cat_id'] = $id;
			  $where['is_show'] = 1;
			  $list = Db::name("article")->where($where)->order('listorder asc')->field('id,title,add_time')->limit($start,20)->select();
			  if($list){
			  	foreach($list as $k=>$v){
			  		$list[$k]['add_time'] = day($v['add_time']);
			  	} 
			  	$ret['code'] = 1;
			  	$ret['data'] = $list;
			  	$ret['cat_name'] = Db::name("article_cat")->where("id",$id)->value("name");
			  }else{
			  	$ret['code'] = 0;
			  	$ret['msg'] = '暂无更多';
			  }
			  $this->re($ret);
	     }
	      //获取文章详情
	    function  get_article_detail($id){
	    	  $ret['text_desc'] = 'title文章标题|content文章内容|add_time文章添加时间';
			  $where['id'] = $id;
			  $where['is_show'] = 1;
			  $data = Db::name("article")->where($where)->field('title,content,add_time')->find();
			  if($data){
			  	$data['add_time'] = day($data['add_time']);
     	        $data['content'] = str_replace("/uploads/","http://".$_SERVER['HTTP_HOST']."/uploads/",$data['content']);
			  	$ret['code'] = 1;
			  	$ret['data'] = $data;
			  }else{
			  	$ret['code'] = 0;
			  	$ret['msg'] = '信息不存在';
			  }
			  $this->re($ret);
	     }
        //获取广告列表
	   function  get_guanggao_list($id,$limit=0){
	   		  $ret['text_desc'] = 'title广告标题|img广告图片|type广告类型1菜单链接2案例链接3网点链接6外联0未设置|curl广告类型对应的值|is_login 1需要登录 2无需登录';
			  $where['cat_id'] = $id;
			  $where['is_show'] = 1;
			  if($limit){
			  		$list = Db::name("guanggao")->where($where)->order('listorder asc')->field('title,img,type,url,is_login')->limit($limit)->select();
			  }else{
			  		$list = Db::name("guanggao")->where($where)->order('listorder asc')->field('title,img,type,url,is_login')->select();
			  }
			  if($list){
			  	foreach($list as $k=>$v){
			  		$list[$k]['curl'] = $v['url'];
			  		$list[$k]['img'] = $this->baseurl.$v['img'];
			  		unset($list[$k]['url']);
			  	} 
			  	$ret['code'] = 1;
			  	$ret['data'] = $list;
			  }else{
			  	$ret['code'] = 0;
			  }
			  $this->re($ret);
	     }
    	
    	
    	//$url = "http://106.ihuyi.com/webservice/sms.php?method=Submit&account=".$a."&password=".$b."&mobile=" . $phone . "&content=" . $content;
//      $data = file_get_contents($url);
//      $xml = simplexml_load_string($data);
//    
//      $tt = $xml->msg;  
    	
    	  
}
?>