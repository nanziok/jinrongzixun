<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Cookie;
/**
 * swagger: api Index
 */
class Index extends Base{
     /**
     * post:获取用户协议
     * path: get_article_xieyi
     * method: get_article_xieyi
     */ 
     public function get_article_xieyi(){
     	$this->get_article_detail('2');
     }
     /**
     * post:获取隐私政策
     * path: get_article_zhengce
     * method: get_article_zhengce
     */ 
     public function get_article_zhengce(){
     	$this->get_article_detail('1');
     }
     /**
     * post:关于我们
     * path: get_article_about
     * method: get_article_about
     */ 
     public function get_article_about(){
     	$this->get_article_detail('4');
     }
      /**
     * post:获取城市列表
     * path: get_city_list
     * method: get_city_list
     */ 
     public function get_city_list(){
		$ret['text_desc'] = 'region_id城市ID|region_name城市名称';
     	$list = Db::name("region")->where("region_type = 2")->field("str")->order("str asc")->group("str")->select();
 	    if(count($list)>0){
 	      foreach($list as $k=>$v){
 	    		$list[$k]['son'] = Db::name("region")->where(array("region_type"=>2,"str"=>$v["str"]))->field("region_id,region_name,str")->select();
 	      }
		  $ret['code']=1;
		  $ret['data']=$list;
		}else{
		  $ret['code']=0;
		}
		$this->re($ret);
     }
     
     
       /**
     * post: 获取首页轮播图
     * path: get_banner
     * method: get_banner
     */ 
     public function get_banner(){
     	$this->get_guanggao_list(1,3);
     }
     /**
     * post: 获取首页中间图标
     * path: get_banner_center
     * method: get_banner_center
     */ 
     public function get_banner_center(){
     	$this->get_guanggao_list(2,5);
     }
   /**
     * post: 获取视力健康状态及调理进度
     * path: get_shili_tiaoli
     * method: get_shili_tiaoli
     */ 
     public function get_shili_tiaoli(){
     	 $shili = [['id'=>1,"name"=>'近视'],['id'=>2,"name"=>'远视'],['id'=>3,"name"=>'弱视'],['id'=>4,"name"=>'散光'],['id'=>5,"name"=>'其他']];
     	 $tiaoli = [['id'=>1,"name"=>'阶段一'],['id'=>2,"name"=>'阶段二'],['id'=>3,"name"=>'阶段三'],['id'=>4,"name"=>'阶段四']];
         $ret['shili']=$shili;
         $ret['tiaoli']=$tiaoli;
	     $this->re($ret);
     }
    /**
     * post: 获取三级地区列表
     * path: area_list
     * method: area_list
     */ 
    public function area_list(){
	   $list = Db::name('region')->where(array("region_type"=>1,'parent_id'=>1))->field('region_id, region_name')->select();
	   $arr = array();
	   foreach($list as $k=>$v){
		  	$arr[$k]['value']=$v['region_id'];
		  	$arr[$k]['text']= $v['region_name'];
		  	$arr[$k]['children']= get_city($v['region_id']);
	   }
        echo json_encode($arr);exit;
    }
    /**
     * post: 获取城市ID
     * path: get_city_id
     * method: get_city_id
     * param: name - {string} 城市名（不带市）
     */ 
     public function get_city_id(){
     	$name = input("name");
     	$region_id = Db::name("region")->where(array("region_name"=>$name,"region_type"=>2))->value('region_id');
     	if(empty($region_id)){
     		$ret['code']=0;
     		$ret['city_id']=0;

     	}else{
     		$ret['code']=1;
     		$ret['city_id']=$region_id;

     	}
	     $this->re($ret);
     }
    /**
     * post: 获取附近的人
     * path: get_near_people
     * method: get_near_people
     * param: city_id - {int} 城市ID
     * param: start - {int} 起始ID
     */ 
    public function get_near_people(){
		$ret['text_desc'] = 'id用户ID|headimg头像|username昵称|sex性别1男2女0未设置|fabu记录|fensi粉丝|area区域';
    	$city_id = input("city_id",0,"intval");
    	$start = input("start",0,"intval");
    	$user_id=Cookie::get("user_id");
		if($user_id){
			$where['id'] = ['neq',$user_id];
		}
		$where['city'] = ['eq',$city_id];
		$where['status'] = ['eq',1];
    	$list = Db::name("user")->where($where)->order("fabu desc,get_zan desc")->field("id,username,sex,fabu,fensi,city,district")->limit($start,10)->select();
    	if($list){
    		foreach($list as $k=>$v){
		  		$list[$k]['headimg'] = get_headimg($v['id']);
		  		$list[$k]['username'] = empty($v["username"])?"未设置":mb_substr($v["username"],0,6,'utf-8');
		  		$list[$k]['area'] = get_region_info($v['city']).'-'.get_region_info($v['district']);
		  		$list[$k]['fabu'] = get_user_fabu($v['id']);
		  		$list[$k]['id'] = rand(10000,99999).$v['id'];

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
     * post: 获取附近的动态
     * path: get_near_fabu
     * method: get_near_fabu
     * param: city_id - {int} 城市ID
     * param: start - {int} 起始ID
     */ 
	function get_near_fabu(){
		  $ret['text_desc'] = 'id动态记录ID|user_id用户ID|headimg头像|username昵称|sex性别1男2女0未设置|area区域|type1图片2视频|type_value图片视频值|content内容|add_time发布时间|zan点赞数|comment评论数';
		  $city_id = input("city_id",0,"intval");
	      $start = input('start',0,'intval');
          $where['u.city'] = ['eq',$city_id];
          $where['f.status'] = ['eq',1];
          $where['f.is_delete'] = ['eq',0];
          $user_id=Cookie::get("user_id");
		  if($user_id){
			$where['u.id'] = ['neq',$user_id];
		  }
	      $list = Db::name("user_fabu")->alias("f")->join("user u","u.id=f.user_id")->where($where)->order('f.id desc')
	      ->field('f.id,f.user_id,f.type,f.type_value,f.video,f.content,f.add_time,f.zan,f.comment,u.username,u.sex,u.city,u.district')->limit($start,10)->select();
		  if($list){
		  	foreach($list as $k=>$v){
		  		$list[$k]['user_id'] = rand(10000,99999).$v['user_id'];
		  		$list[$k]['headimg'] = get_headimg($v['user_id']);
		  		$list[$k]['username'] = empty($v["username"])?"未设置":mb_substr($v["username"],0,6,'utf-8');
		  		$list[$k]['area'] = get_region_info($v['city']).'-'.get_region_info($v['district']);
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
     * post: 获取他人主页信息
     * path: get_people_detail
     * method: get_people_detail
     * param: relative_id - {int} id用户ID
     */ 
	public function get_people_detail(){
		$ret['text_desc'] = 'headimg头像|username昵称|sex性别1男2女0未设置|guanzhu关注数|fensi粉丝数|getzan获赞数|fabu发布数|desc个人简介|is_guanzhu1已关注0未关注';
		$relative_id = input('relative_id',0,'intval');
	    $relative_id = substr($relative_id,5);
		$this->check_userinfo($relative_id);
		$info = Db::name("user")->where("id",$relative_id)->field('id,sex,username,desc,guanzhu,fensi,get_zan,fabu')->find();
		if($info){
			$info['fabu'] = get_user_fabu($info['id']);
			$info['headimg'] = get_headimg($relative_id);
			$info['username'] = empty($info["username"])?"未设置":$info["username"];
			$info['desc'] = empty($info["desc"])?"这个人什么都没有留下":$info['desc'];
			$info['id'] = rand(10000,99999).$info['id'];
			
			$user_id=Cookie::get("user_id");
			if($user_id){
		  		$find=Db::name("user_guanzhu")->where(array("user_id"=>$user_id,"relative_id"=>$relative_id))->field("id")->find();
		  		$info['is_guanzhu'] = empty($find)?0:1;
		    }else{
		 	    $info['is_guanzhu'] = 0;
		    }
			$ret['code'] = 1;
		  	$ret['data']= $info;
		  	$this->re($ret);
		}else{
			$ret['code'] = 0;
		  	$ret['msg']= '个人信息不存在';
		  	$this->re($ret);
		}
		
	}
		/**
     * post: 获取他人主页发布列表信息
     * path: get_people_detail_list
     * method: get_people_detail_list
     * param: start - {int} 起始ID
     * param: relative_id - {int} id用户ID
     */ 
	function get_people_detail_list(){
		  $ret['text_desc'] = 'id发布信息ID|headimg头像|username昵称|sex性别1男2女0未设置|area区域|type1图片2视频3纯文本|type_value图片视频值|content内容|add_time发布时间|status1审核成功2审核中|comment评论数|zan获赞数|is_zan当前登录账户是否点赞1已点赞0未点赞';
		  $relative_id = input('relative_id',0,'intval');
	      $relative_id = substr($relative_id,5);
		  $this->check_userinfo($relative_id);
	      $start = input('start',0,'intval');
          $where['user_id'] = ['eq',$relative_id];
		  $where['is_delete'] = ['eq',0];
		  $where['status'] = ['eq',1];
	      $list = Db::name("user_fabu")->where($where)->order('id desc')->limit($start,10)->select();
	      $info = get_userinfo($relative_id);
		  if($list){
		  	$user_id=Cookie::get("user_id");
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
		  		$list[$k]['is_zan'] = 0;
		  		if($user_id){
		  			$list[$k]['is_zan'] = check_zan($user_id,$v['id']);
		  		}
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
     * post: 获取分类
     * path: get_cat
     * method: get_cat
     */ 
     
     public  function get_cat(){
     	$age = [['id'=>1,"name"=>'0~8岁'],['id'=>2,"name"=>'9~10岁'],['id'=>3,"name"=>'11~12岁'],['id'=>4,"name"=>'13~16岁'],['id'=>5,'name'=>'17~18岁'],['id'=>6,'name'=>'18岁以上']];
     	$type = [['id'=>1,"name"=>'用户'],['id'=>2,"name"=>'动态']];
     	$shili = [['id'=>1,"name"=>'近视'],['id'=>2,"name"=>'远视'],['id'=>3,"name"=>'弱视'],['id'=>4,"name"=>'散光'],['id'=>5,"name"=>'其他']];
     	$tiaoli = [['id'=>1,"name"=>'阶段一'],['id'=>2,"name"=>'阶段二'],['id'=>3,"name"=>'阶段三'],['id'=>4,"name"=>'阶段四']];
     	$ret = ['age'=>$age,'type'=>$type,'shili'=>$shili,'tiaoli'=>$tiaoli];
     	$this->re($ret);
     }
     
     
    /**
     * post: 获取搜索结果
     * path: get_search
     * method: get_search
     * param: age - {int} 年龄ID 0=>全部1=>0~8岁，2=>9~10岁，3=>11~12岁，4=>13~16岁，5=>17~18岁，6=>18岁以上
     * param: type - {int} 类型ID 1=>用户，2=>动态
     * param: shili - {int} 视力ID 1=>近视，2=>远视，3=>弱视，4=>散光，5=>其他
     * param: tiaoli - {int} 调理ID 1=>阶段一，2=>阶段二，3=>阶段三，4=>阶段四
     * param: city_id - {int} 城市ID
     * param: start - {int} 起始ID
     * param: keywords - {string} 搜索关键词
     */ 
     public  function get_search(){
     	$age = input("age",0,"intval");
     	$type = input("type",0,"intval");
     	$shili = input("shili",0,"intval");
     	$tiaoli = input("tiaoli",0,"intval");
     	$city_id = input("city_id",0,"intval");
     	$start = input("start",0,"intval");
     	$keywords = input("keywords");
     	if($type == 0){
     		$ret['code']=0;
     		$ret['msg']="参数有误";
    	    $this->re($ret);
     	}
     	if($type == 1){
			$ret['text_desc'] = 'id用户ID|headimg头像|username昵称|sex性别1男2女0未设置|fabu记录|fensi粉丝|area区域';
	    	if($city_id>0){
	    		$where['city'] = ['eq',$city_id];
	    	}
            if($shili>0){
            	$where['shili'] = ['eq',$shili];
            }
            if($tiaoli>0){
            	$where['tiaoli'] = ['eq',$tiaoli];
            }
            if($keywords){
            	$where['username'] = ['like', '%' . $keywords . '%'];
            }
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
            }elseif($age == 6){
            	$where['age'] = ['gt',18];
            }else{
            	
            }
            $where['status'] = ['eq',1];
	    	$list = Db::name("user")->where($where)->order("fabu desc,get_zan desc")->field("id,username,sex,fabu,fensi,city,district")->limit($start,10)->select();
	    	if($list){
	    		foreach($list as $k=>$v){
	    			$list[$k]['id'] = rand(10000,99999).$v['id'];
			  		$list[$k]['headimg'] = get_headimg($v['id']);
			  		$list[$k]['username'] = empty($v["username"])?"未设置":mb_substr($v["username"],0,6,'utf-8');
			  		$list[$k]['area'] = get_region_info($v['city']).'-'.get_region_info($v['district']);
			  		$list[$k]['fabu'] = get_user_fabu($v['id']);

			  	}
			  	$ret['code'] = 1;
			  	$ret['data'] = $list;
	    		
	    	}else{
	    	    $ret['code'] = 0;
			  	$ret['msg']= '到底了';
			}
			$this->re($ret);
     	}else{
			  $ret['text_desc'] = 'id动态记录ID|headimg头像|username昵称|sex性别1男2女0未设置|area区域|type1图片2视频|type_value图片视频值|video视频值|content内容|add_time发布时间|zan点赞数|comment评论数|is_zan当前登录账户是否点赞1已点赞0未点赞';
	          $where['u.city'] = ['eq',$city_id];
	          $where['f.status'] = ['eq',1];
	          $where['f.is_delete'] = ['eq',0];
	          if($shili>0){
            	$where['u.shili'] = ['eq',$shili];
	          }
             if($tiaoli>0){
            	$where['u.tiaoli'] = ['eq',$tiaoli];
             }
             if($keywords){
            	$where['f.content'] = ['like', '%' . $keywords . '%'];
            }
             if($age == 1){
            	$where['u.age'] = ['between',[0,8]];
             }elseif($age == 2){
            	$where['u.age'] = ['between',[9,10]];
             }elseif($age == 3){
            	$where['u.age'] = ['between',[11,12]];
             }elseif($age == 4){
            	$where['u.age'] = ['between',[13,16]];
             }elseif($age == 5){
            	$where['u.age'] = ['between',[17,18]];
             }elseif($age == 6){
            	$where['u.age'] = ['gt',18];
             }else{
             	
             }
		      $list = Db::name("user_fabu")->alias("f")->join("user u","u.id=f.user_id")->where($where)->order('f.id desc')
		      ->field('f.id,f.user_id,f.type,f.type_value,f.video,f.content,f.add_time,f.zan,f.comment,u.username,u.sex,u.city,u.district')->limit($start,10)->select();
			  if($list){
			  	$user_id=Cookie::get("user_id");
			  	foreach($list as $k=>$v){
			  		$type_value = $v['type_value'];
			  		if($type_value){
			  			$type_value = explode(',',str_replace("/uploads/",$this->baseurl."/uploads/",$type_value));
			  		}
			  		$list[$k]['type_value'] = $type_value;
			  		$list[$k]['video'] = $v["type"]==2?$this->baseurl.$v['video']:'';
			  		$list[$k]['headimg'] = get_headimg($v['user_id']);
			  		$list[$k]['username'] = empty($v["username"])?"未设置":mb_substr($v["username"],0,6,'utf-8');
			  		$list[$k]['area'] = get_region_info($v['city']).'-'.get_region_info($v['district']);
			  		$list[$k]['add_time'] = date("Y-m-d H:i",$v["add_time"]);
			  		$list[$k]['is_zan'] = 0;
			  		if($user_id){
			  			$list[$k]['is_zan'] = check_zan($user_id,$v['id']);
			  		}
			  		
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
     }
    /**
     * post: 获取案例列表
     * path: get_anli_list
     * method: get_anli_list
     * param: start - {int} 起始ID
     */ 
    public function get_anli_list(){
		$ret['text_desc'] = 'id案例ID|title案例标题|img案例缩略图|add_time添加时间';
    	$start = input("start",0,"intval");
    	$list = Db::name("anli")->where("status",1)->order("listorder asc")->field("id,title,img,add_time")->limit($start,10)->select();
    	if($list){
    		foreach($list as $k=>$v){
		  		$list[$k]['img'] = empty($v['img'])?$this->baseurl."/uploads/default/img_default.png":$this->baseurl.$v['img'];
		  		$list[$k]['add_time'] = day($v['add_time']);
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
     * post: 获取案例详情
     * path: get_anli_detail
     * method: get_anli_detail
     * param: id - {int} 案例ID
     */ 
    public function get_anli_detail(){
	    	  $ret['text_desc'] = 'title案例标题|content案例内容|add_time案例添加时间';
	    	  $id = input('id',0,'intval');
			  $where['id'] = $id;
			  $where['status'] = 1;
			  $data = Db::name("anli")->where($where)->field('title,content,add_time')->find();
			  if($data){
			  	$data['add_time'] = date("Y-m-d H:i",$data['add_time']);
     	        $data['content'] = str_replace("/uploads/","http://".$_SERVER['HTTP_HOST']."/uploads/",$data['content']);
			  	$ret['code'] = 1;
			  	$ret['data'] = $data;
			  }else{
			  	$ret['code'] = 0;
			  	$ret['msg'] = '案例信息不存在';
			  }
			  $this->re($ret);
	     
    }
    /**
     * post: 获取服务店列表
     * path: get_service_list
     * method: get_service_list
     * param: start - {int} 起始ID
     */ 
    public function get_service_list(){
		$ret['text_desc'] = 'name门店名|code门店编号|img门店缩略图|phone门店电话|address门店地址';
    	$start = input("start",0,"intval");
    	$list = Db::name("service")->where("status",1)->order("listorder asc")->field("name,code,img,phone,address")->limit($start,10)->select();
    	if($list){
    		foreach($list as $k=>$v){
		  		$list[$k]['img'] = empty($v['img'])?$this->baseurl."/uploads/default/img_default.png":$this->baseurl.$v['img'];
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
     * post: 获取消息列表消息是否显示(前提必须是登录状态)
     * path: get_xiaoxi_num
     * method: get_xiaoxi_num
     */ 
    public function get_xiaoxi_num(){
    	$ret['text_desc'] = 'comment是否有评论未查看1有0无|comment是否有点赞未查看1有0无|comment是否有新的关注未查看1有0无|message是否有消息未查看1有0无';
    	$user_id=Cookie::get("user_id");
    	if($user_id){
    	    $relative_id_arr = Db::name("user_fabu")->where("user_id",$this->user['id'])->column("id");
            if(count($relative_id_arr)>0){
          	  $where['fabu_id'] = ['in',$relative_id_arr];
            }
            $where['is_read'] = 0;
    	    $comment = Db::name("user_fabu_comment")->where($where)->count();
    	    $zan = Db::name("user_zan")->where($where)->count();
    	    $guanzhu = Db::name("user_guanzhu")->where(array("relative_id"=>$user_id,"is_read"=>0))->count();
    	    
    	    $data['comment'] = $comment>0?1:0;
    		$data['zan'] = $zan>0?1:0;
    		$data['guanzhu'] = $guanzhu>0?1:0;
    	    $data['message'] = ($comment+$zan+$guanzhu)>0?1:0;
    	}else{
    		$data['comment'] = 0;
    		$data['zan'] = 0;
    		$data['guanzhu'] = 0;
    	    $data['message'] = 0;
    	}
    	$ret['code'] = 1;
		$ret['data'] = $data;
    	$this->re($ret);
    }
    
     /**
     * post: 获取ios上架版本号
     * path: get_hide
     * method: get_hide
     * param: version - {string} 版本号
     */ 
    public function get_hide(){
    		$ret['text_desc'] = 'code1显示微信登录及支付 0隐藏微信';
    	    $version = input('version');
    	    $ios1=$this->xitong['ios1'];
    	    if(!$version || !$ios1){
    	    	$ret['code']=0;
    	    	$this->re($ret);
    	    }
    	    $arr1 = explode('.',$version);
    	    $arr2 = explode('.',$ios1);
    	    if(count($arr1) != 3 || count($arr2) != 3){
    	    	$ret['code']=0;
    	    	$this->re($ret);
    	    }
    	    if(intval($arr1[0])<intval($arr2[0])){
    	    	$ret['code']=1;
    	    	$this->re($ret);
    	    }else{
    	    	if(intval($arr1[1])<intval($arr2[1])){
    	    	   $ret['code']=1;
    	    	   $this->re($ret);
	    	    }else{
	    	    	if(intval($arr1[2])<intval($arr2[2])){
	    	    	   $ret['code']=1;
	    	    	   $this->re($ret);
		    	    }else{
		    	       $ret['code']=0;
	    	    	   $this->re($ret);
	    	        }
	    	    }
    	    }
	}
	/**
     * post: 获取版本号
     * path: get_banben
     * method: get_banben
     * param: version - {string} 版本号
     * param: type - {int} 1安卓 2ios
     */ 
    public function get_banben(){
    	    $ret['text_desc'] = 'code1需要版本更新0不需要|android安卓版本号|android_url安卓下载链接|ios苹果版本号|ios_url苹果打开商店ID|gengxin更新提示语';
	      	$version = input('version');
	      	$type = input('type');
	        if(!$version || !$type){
    	    	$ret['code']=0;
    	    	$this->re($ret);
    	    }
	      	$info=Db::name("xitong")->where("id=1")->field('android,android_url,ios,ios_url,gengxin_desc as gengxin')->find();
	      	if($info['gengxin']){
	      		$gengxin = explode('|',$info['gengxin']);
	      		if(count($gengxin)>1){
	      			$a = '';
	      			foreach($gengxin as $k=>$v){
	      				if($k<count($gengxin)-1){
	      					$a.=($k+1).'、'.$v."\n";
	      				}else{
	      					$a.=($k+1).'、'.$v;
	      				}
	      			}
	      			$info['gengxin'] = $a;
	      		}
	      	}
	      	$arr1 = explode('.',$version);
            if($type == 1){
            	$arr2 = explode('.',$info['android']);
            	if(!$info['android_url']){
            		$ret['code']=0;
    	    	    $this->re($ret);
            	}
            }else{
            	$arr2 = explode('.',$info['ios']);
            	if(!$info['ios_url']){
            		$ret['code']=0;
    	    	    $this->re($ret);
            	}
            }
            if(count($arr1) != 3 || count($arr2) != 3){
    	    	$ret['code']=0;
    	    	$this->re($ret);
    	    }
            if(intval($arr1[0])<intval($arr2[0])){
    	    	$ret['code']=1;
    	    	$ret['data'] = $info;
    	    	$this->re($ret);
    	    }else{
    	    	if(intval($arr1[1])<intval($arr2[1])){
    	    	   $ret['code']=1;
    	    	   $ret['data'] = $info;
    	    	   $this->re($ret);
	    	    }else{
	    	    	if(intval($arr1[2])<intval($arr2[2])){
	    	    	   $ret['code']=1;
	    	    	   $ret['data'] = $info;
	    	    	   $this->re($ret);
		    	    }else{
		    	       $ret['code']=0;
	    	    	   $this->re($ret);
	    	        }
	    	    }
    	    }
	}

}
	