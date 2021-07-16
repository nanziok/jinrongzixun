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
    public $base_url;

    public function _initialize()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $this->base_url = $protocol . $_SERVER['HTTP_HOST'];
    }

    /**
     * @title 首页模板数据
     * @description 首页模板数据，首页tip应该区分登录状态
     * @author 开发
     * @url /api/index/indextpl
     * @method get
     * @return news:首页文章@
     * @news id:ID title:标题
     * @return swipers:首页幻灯片@
     * @swipers title:标题 img:图片地址 type:类型2文章3咨询师9外链 exp_id:附加字段链接类型id
     * @return notice:首页公告@
     * @notice id:ID title:标题
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function indextpl(){
        $base_url = $this->base_url;
        $news = Db::name("article")->where(["cat_id"=>7,"is_show"=>1])
             ->field("id,title")
             ->order("listorder desc,id desc")
             ->limit(0,6)
             ->select();
        $swipers = Db::name("guanggao")->where("cat_id",1)
            ->where("is_show", 1)
            ->where("cat_id", 1)
            ->field("title, img, type, url as exp_id")
            ->order("listorder desc")
            ->limit(0,4)
            ->select();
        $swipers = array_map(function ($item) use ($base_url){
            $item["img"] = $base_url .$item["img"];
            return $item;
        },$swipers);
        $notice = Db::name("article")->where("cat_id",2)
            ->field("id,title")
            ->order("listorder desc")
            ->limit(0,3)
            ->select();
        $this->result(compact('news','swipers', 'notice'),1,"首页模板数据",'json');
    }

    /**
     * @title 公告列表
     * @description 通过http获取公告和消息，包含未读消息和已读消息，每类消息显示最近一条，不再另外提供内容页接口
     * @author mifan89 --2021-05-12
     * @url /api/index/notice
     * @mrthod get
     * @return notice:公告@
     * @notice id:ID title:标题 content:正文 add_time:添加时间戳（秒）
     */
    public function notice(){
        $notice = Db::name("article")->where("cat_id",2)
            ->field("id,title,content,add_time")
            ->order("listorder desc,id desc")
            ->limit(0,3)
            ->select();
        $list = array_map(function ($item){
            $item["add_time"] = day($item["add_time"]);
            $item["content"] = str_replace('/uploads/', $this->base_url . '/uploads/', $item["content"]);
            $item["description"] = mb_substr(strip_tags($item["content"]), 0, 72);
            return $item;
        },$notice);
        $this->result(compact("list"),1,"公告列表",'json');
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
     * @list id:ID title:标题
     */
    public function new_list(){
        $page_size=input("page_size",10,"int");
        $page=input("page",1,"int");
        $list = Db::name("article")->where("cat_id",7)
            ->field("id,title")
            ->order("listorder desc")
            ->page($page,$page_size)
            ->select();
        $this->result(compact('list'),1,'资讯列表', 'json');
    }

    /**
     * @title 资讯信息详情
     * @description 资讯信息详情
     * @author 开发
     * @url /api/index/article_info
     * @method get
     * @param name:id type:int require:1 default:0 desc:文章id,不传的时候返回code=0
     * @return id:ID
     * @return title:标题
     * @return content:内容（富文本）
     * @return add_time:日期（yyyy-mm-dd）
     */
    public function article_info(){
        $id = input("id",0,"int");
        if(intval($id)>0){
            $info = Db::name("article")->where("id",$id)->field("id,title,content,add_time")->find();
            if(!empty($info)) {
                $info["content"] = str_replace('/uploads/', $this->base_url . '/uploads/', $info["content"]);
                $info["add_time"] = day($info["add_time"]);
                $this->result($info, 1, '信息通告详情', 'json');
            }
        }
        $this->result('',0,'信息通告详情出错', 'json');
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
     * @list id:咨询师id name:名称 tags:标签
     * @return tech_shows:咨询介绍图片展示@!
     * @tech_shows chat_description:咨询师列表页展示 jijin_description:基金咨询页面展示
     */
    public function tech_list(){
        $page_size=input("page_size",10,"int");
        $page=input("page",1,"int");
        $list = Db::name("service")
            ->field("id,name,tags")
            ->where("status",1)
            ->order("weigh desc")
            ->page($page,$page_size)
            ->select();
        $list = array_map(function ($item){
            $item["tags"] = implode(" ", explode(",", $item["tags"]));
            return $item;
        }, $list);
        $jijin_setting = Db::name("xitong")->where("id",1)->value("jijin_setting");
        $tech_shows = array_filter(json_decode($jijin_setting, true), function ($k){ return in_array($k,['chat_description','jijin_description']);}, ARRAY_FILTER_USE_KEY);
        foreach ($tech_shows as $key=>$val){
            $tech_shows[$key] = $this->base_url . $val;
        }
        $this->result(compact("list", "tech_shows"),1,"金融咨询师列表",'json');
    }

    /**
     * @title 基金产品列表
     * @description 基金产品列表，不需要登录
     * @author mifan89 2021年5月14日
     * @url /api/index/jijin_sub_list
     * @method get
     * @param name:page type:int require:0 default:1 desc:页码
     * @param name:page_size type:int require:0 default:20 desc:页长度
     * @return list:列表@
     * @list id:id name:产品名 tags:标签 tags_text:格式化后的标签 description:简介(纯文本) create_time:添加时间(yyyy-mm-dd)
     * @return top_img:基金顶部介绍图片（地址）
     */
    public function jijin_sub_list(){
        $page_size=input("page_size",20,"int");
        $page=input("page",1,"int");
        $list = Db::name("jijin_sub")
            ->field("id,name,tags,description,create_time")
            ->where(["status"=>1])
            ->order("weigh desc")
            ->page($page,$page_size)
            ->select();
        $list = array_map(function ($item){
            $item["tags_text"] = implode(" ", explode(",", $item["tags"]));
            $item["create_time"] = day($item["create_time"]);
            $item["description"] = mb_substr($item["description"], 0, 120);
            return $item;
        },$list);
        $jijin_setting = Db::name("xitong")->where("id",1)->value("jijin_setting");
        $tech_shows = array_filter(json_decode($jijin_setting, true), function ($k){ return in_array($k,['chat_description','jijin_description']);}, ARRAY_FILTER_USE_KEY);
        foreach ($tech_shows as $key=>$val){
            $tech_shows[$key] = $this->base_url . $val;
        }
        $top_img = $tech_shows["jijin_description"];
        $this->result(compact("list","top_img"),1,"基金产品列表",'json');
    }

    /**
     * @title 基金产品详情
     * @description 基金产品详情
     * @author mifan89 2021年5月14日
     * @url /api/index/jijin_sub_info
     * @method get
     * @param name:id type:int require:1 default: desc:产品id
     * @return info:列表@!
     * @info id:id name:产品名 tags:标签 tags_text:格式化后的标签 description:简介(纯文本) content:正文（富文本） create_time:添加时间(yyyy-mm-dd)
     */
    public function jijin_sub_info(){
        $id = input("id",0,"int");
        if($id<0){
            $this->result([],0,"id不能为空",'json');
        }
        $info = Db::name("jijin_sub")
            ->field("id,name,tags,description,content,create_time")
            ->where(["id"=>$id,"status"=>1])
            ->find();
        if (empty($info)){
            $this->result([],0,"查无数据",'json');
        }
        $info["tags_text"] = implode(" ", explode(",", $info["tags"]));
        $info["create_time"] = day($info["create_time"]);
        $info["description"] = mb_substr($info["description"], 0, 120);
        $info["content"] = str_replace('/uploads/', $this->base_url . '/uploads/',$info["content"]);
        $this->result(compact("info"),1,"基金产品列表",'json');
    }

    /**
     * @title 金融咨询师详情
     * @description
     * @url /api/index/tech_info
     * @method get
     * @author 开发者
     * @param name:id type:int require:1 default: desc:咨询师id
     * @return name:咨询师名称
     * @return tags:半角逗号分割的字符串
     * @return content:金融咨询师富文本介绍
     * @return price:咨询价格
     */
    public function tech_info(){
        $id = input("id",0,"int");
        if($id>0) {
            $info = Db::name("service")->where("id", $id)->where("status",1)->field("id,name,tags,content,price")->find();
            if(!empty($info)) {
                $info["tags"] = implode(' ', explode(',', $info["tags"]));
                $info["price"] = '¥'.$info["price"];
                $info["content"] = str_replace('/uploads/', $this->base_url . '/uploads/', $info["content"]);
                $this->result($info, 1, "查看金融咨询师详情", 'json');
            }
        }
        $this->result("",0,'查看金融咨询师详情出错','json');
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

    /**
     * @title 隐私协议
     * @description 这是隐私协议
     * @author mifan89
     * @url /api/index/getAgreement
     * @method get
     * @return title:
     * @return content:正文
     * @return add_time:
     */
    public function getAgreement(){
        $this->get_article_detail(1);
    }
}
	