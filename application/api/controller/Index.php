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
     * @title 首页模板数据
     * @description 首页模板数据，首页tip需要适配登录状态
     * @author 开发
     * @url /api/index/indextpl
     * @method get
     * @return news:首页文章@
     * @news title:标题 content:内容（富文本） add_time:时间戳（秒）
     * @return swipers:首页幻灯片@
     * @swipers title:标题 img:图片地址
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
     * @author nnnn
     * @description 需要分页
     * @url /api/index/new_list
     * @method get
     * @param name:page type:int require:0 default:1 desc:页码
     * @param name:page_size type:int require:0 default:10 desc:页长度
     * @return list:资讯列表@
     * @list title:标题 content:内容（富文本） add_time:时间戳（秒）
     */
    public function new_list(){
        $page_size=input("page_size",10,"int");
        $page=input("page",1,"int");
        $list = Db::name("article")->where("cat_id",1)->order("listorder desc")->page($page,$page_size)->select();
        $this->result(compact('count','list'),1,'资讯列表', 'json');
    }

    /**
     * @title 资讯信息详情
     * @description 资讯信息详情
     * @author 开发
     * @url /api/index/article_info
     * @method get
     * @param name:id type:int require:1 default:0 desc:文章id,不传的时候返回code=0
     * @return title:标题
     * @return content:内容（富文本）
     * @return add_time:时间戳（秒）
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
     * @param name:page type:int require:0 default:1 desc:页码
     * @param name:page_size type:int require:0 default:10 desc:页长度
     * @return list:金融咨询师列表@
     * @list id:咨询师id name:名称 tags:标签 image:图片 price:价格
     */
    public function tech_list(){
        $page_size=input("page_size",10,"int");
        $page=input("page",1,"int");
        $list = Db::name("service")->field("id,name,tags,image")->where("status",1)->order("weigh desc")->page($page,$page_size)->select();
        $this->result(compact("list"),1,"金融咨询师列表",'json');
    }

    /**
     * @title 金融咨询师详情
     * @description
     * @url /api/index/tech_info
     * @method get
     * @author 开发者
     * @param name:id type:int require:1 default: desc:咨询师id
     * @return name:
     * @return tags:
     * @return image:
     * @return price:
     */
    public function tech_info(){
        $id = input("id",0,"int");
        if($id>0) {
            $info = Db::name("service")->where("id", $id)->where("status",1)->field("id,name,tags,content,image,price")->find();
            $this->result($info,1,"查看金融咨询师详情",'json');
        }
        $this->result("",0,'查看金融咨询师详情','json');
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
	