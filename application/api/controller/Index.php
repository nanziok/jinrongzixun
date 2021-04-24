<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Cookie;

/**
 * @title 首页接口
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller {
     /**
      * @title 获取用户协议
      * @author  开发者
      * @url get_article_xieyi
      * @method get
      */
     public function get_article_xieyi(){
     	$this->get_article_detail('2');
     }
     /**
     * @title 获取隐私政策
     * path: get_article_zhengce
     * method: get_article_zhengce
     */ 
     public function get_article_zhengce(){
     	$this->get_article_detail('1');
     }
     /**
     * @title 关于我们
     */
     public function get_article_about(){
     	$this->get_article_detail('4');
     }
      /**
     * @title 获取城市列表
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
     * @title  获取首页轮播图
     * @url /api/index/get_banner
     * @method get
     */ 
     public function get_banner(){
     	$this->get_guanggao_list(1,3);
     }
     /**
     * @title  获取首页中间图标
     * @url /api/index/get_banner_center
     * @method get
     */ 
     public function get_banner_center(){
     	$this->get_guanggao_list(2,5);
     }

    /**
     * @title  获取三级地区列表
     * @url /api/index/area_list
     * @method get
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
     * @title  获取城市ID
     * @url /api/index/get_city_id
     * @method get
     * @param: name:name type:string require:1 default: other: des:城市名（不带市）
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
     * @title 首页模板数据
     * @description 首页模板数据
     * @author 开发
     * @url /api/index/indextpl
     * @method get
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function indextpl(){
         $news = Db::name("article")->where("cat_id",1)->order("listorder desc")->limit(0,6)->select();
         $swipers = Db::name("guanggao")->where("cat_id",1)->order("listorder desc")->limit(0,4)->select();
         $this->result(compact('news','swipers'),1,"首页模板数据",'json');
    }

    /**
     * @title 资讯列表
     * @author 开
     * @url /api/index/new_list
     * @method get
     */
    public function new_list(){
        $page_size=input("page_size",10,"int");
        $page=input("page",1,"int");
        $count = Db::name("article")->where("cat_id",1)->order("listorder desc")->count();
        $list = Db::name("article")->where("cat_id",1)->order("listorder desc")->page($page,$page_size)->select();
        $this->result(compact('count','list'),1,'资讯列表', 'json');
    }

    /**
     * @title 信息通告详情
     * @description 信息通告详情
     * @author 开发
     * @url /api/index/article_info
     * @method get
     */
    public function article_info(){
        $id = input("id",0,"int");
        if(intval($id)>0){
            $info = Db::name("article")->where("id",$id)->find();
            $this->result($info,1,'信息通告详情', 'json');
        }
        $this->result('',0,'信息通告详情', 'json');
    }

    /**
     * @title 金融咨询师列表
     * @description
     * @url /api/index/tech_list
     * @method get
     * @author 开发者
     */
    public function tech_list(){

    }

    /**
     * @title 金融咨询师详情
     * @description
     * @url /api/index/tech_info
     * @method get
     * @author 开发者
     * @param name:id type:int require:1 default: desc:咨询师id
     */
    public function tech_info(){

    }

    /**
     * @param int $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_article_detail($id=0){
        $where['id'] = $id;
        $where['is_show'] = 1;
        $data = Db::name("article")->where($where)->field('title,content,add_time')->find();
        if($data){
            $data['add_time'] = day($data['add_time']);
            $data['content'] = str_replace("/uploads/","http://".$_SERVER['HTTP_HOST']."/uploads/",$data['content']);
            $this->result($data,1,'单页信息', 'json');
        }
        $this->result('',0,'单页信息', 'json');
    }
}
	