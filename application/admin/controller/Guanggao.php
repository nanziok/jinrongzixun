<?php
namespace app\admin\controller;
use think\Db;
class Guanggao extends Base {
	//广告管理start--------------------------------------------------------------------------------------------------------------------------------------
	// 广告列表
	public function index_list(){
		$cat_list = Db::name("guanggao_cat")->field('id,name')->select();
		$this -> assign("cat_list",$cat_list);

		return view();
	}
	//获取广告列表
    public function wdl_index(){
		$where = [];
		$cat_id = input("cat_id");
		if($cat_id){
			$where['g.cat_id'] = $cat_id;
		}
		$type = input("type");
		if($type){
			$where['g.type'] = $type;
		}
	    $count = Db::name("guanggao") ->alias('g')->join('guanggao_cat c','c.id=g.cat_id','left')->field('g.*,c.name')->where($where)->count();
		$list = Db::name("guanggao") ->alias('g')->join('guanggao_cat c','c.id=g.cat_id','left')->field('g.*,c.name')->where($where)->order('g.id desc')->paginate(15,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			$type= array("0"=>"未设置","1"=>"菜单链接","2"=>"文章","3"=>"咨询师","9"=>"外链");
			foreach($data as $k=>$v){
				$data[$k]['type'] = array_key_exists($v["type"], $type) ? $type[$v['type']] : '类型未设置';
				$data[$k]['add_time'] = d($v['add_time']);
			}
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));

    }
	// 添加广告
	public function wdl_add(){
		$cat_list = Db::name("guanggao_cat")->field('id,name')->select();
		$this -> assign("cat_list",$cat_list);
		return view();
	}
	// 编辑广告
	public function wdl_edit(){
		$id = input('id',0,'intval');
		
		$data = Db::name("guanggao")->where('id',$id)->find();
		$this -> assign("data",$data);
	
		$cat_list = Db::name("guanggao_cat")->field('id,name')->select();
		$this -> assign("cat_list",$cat_list);
		return view();
	}
	//处理图片的编辑和添加
	public function wdl_index_post(){
		$data = input();
        if(isset($data['file'])){
		  unset($data['file']);
		}
		if(isset($data['_'])){
	      unset($data['_']);
		}
		if(empty($data['img'])){
			$this->error('请添加广告图');
		}
		$id = input('id',0,'intval');
		if($id){
			$where['title'] = ['eq',$data['title']];
			$where['id'] = ['neq',$id];
		}else{
			$where['title'] = ['eq',$data['title']];
		}
		$find = Db::name("guanggao")->where($where)->find();
		if($find){
			$this->error('广告名称'.$data['title'].'已经存在');
		}
        if($id){
		   $img =  Db::name("guanggao")->where('id',$data['id'])->value('img');
		   if($data['img']){
                if ($img != ''  && $img != $data['img'] && is_file(ROOT_PATH . '/public_html' . $img)){
                   @unlink(ROOT_PATH . '/public_html' . $img);
                }
		   }else{
                if ($img != '' && is_file(ROOT_PATH . '/public_html' . $img)){
                   @unlink(ROOT_PATH . '/public_html' . $img);
                }
		   }
		}
		if($id){
			$ret = Db::name("guanggao")->update($data);
			if($ret){
			     $this->success("修改成功");
			}else{
				 $this->error("没有任何修改");
			}
		}else{
			$data['add_time'] = time();
			$ret = Db::name("guanggao")->insertGetId($data);
			if($ret){
			     $this->success("添加成功");
			}else{
				 $this->error("请稍后重试");
			}
		}
	}
	// 删除广告
	public function wdl_del(){
		$id=input("id",0,'intval');
		if($id == 0){
			 $this->error("请选择广告");
		}
		$img = Db::name("guanggao")->where("id",$id)->value('img');
		if ($img != '' && is_file(ROOT_PATH . '/public_html' . $img)){
           @unlink(ROOT_PATH . '/public_html' . $img);
        }
        $res =  Db::name("guanggao")->where("id",$id)->delete();
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
	}
	 //广告管理end-------------------------------------------------------------------------------------------------------------------------------------
	//广告分类start---------------------------------------------------------------------------------------------------------------------------------
	//分类列表
	public function cat(){
		return view("cat");
	}
	//获取分类列表
	public function wdl_cat(){
		$data = Db::name('guanggao_cat')->order('id desc')->select();
		if($data){
		    foreach($data as $k=>$v){
			    $data[$k]['count'] =  Db::name('guanggao')->where('cat_id',$v['id'])->count();
			}
		
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>count($data)));
	}
	//广告分类添加
	public function wdl_cat_add(){
		return view('cat_add');
	}
	//广告分类添加处理
	public function wdl_cat_add_do(){
		$data = input();
		$find = Db::name('guanggao_cat')->where("name",$data['name'])->find();
		if($find){
			$this->error($data['name'].'分类名称已存在！');
		}
	    $data['add_time'] = time();
		$rt = Db::name('guanggao_cat')->insert($data);
		if($rt){
			$this->success('添加成功');
		}else{
			$this->error('添加失败！');
		}
	}
	//广告分类编辑
	public function wdl_cat_edit(){
		$id = input("id");
		$data =  Db::name('guanggao_cat')->where("id",$id)->find();
		$this->assign('data',$data);
		return view('cat_edit');
	}
	//广告分类编辑处理
	public function wdl_cat_edit_do(){
		$data = input();
		$where['name'] = ['eq',$data['name']];
		$where['id'] = ['neq',$data['id']];
		$find = Db::name('guanggao_cat')->where($where)->find();
		if($find){
			$this->error($data['name'].'分类名称已存在！');
		}
		$rt = Db::name('guanggao_cat')->update($data);
		if($rt){
			$this->success('编辑成功');
		}else{
			$this->error('没有修改！');
		}
	}
	//广告分类删除
	public function wdl_cat_del(){
		$id=input("id",0,'intval');
		if($id == 0){
			 $this->error("请选择分类");
		}
		$count = Db::name('guanggao')->where('cat_id',$id)->count();
		if($count){
            $this->error('该位置存在广告');
		}
        $res = Db::name('guanggao_cat')->where("id",$id)->delete();
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
	}

	//广告分类end---------------------------------------------------------------------------------------------------------------------------------

	
}

