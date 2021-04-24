<?php
namespace app\admin\controller;
use think\Db;
use fast\Tree;
class Article extends Base {
	//文章管理start--------------------------------------------------------------------------------------------------------------------------------------
	// 文章列表
	public function index_list(){
		$cat_list = Db::name("article_cat")->field('id,name')->select();
		$this -> assign("cat_list",$cat_list);

		return view();
	}
	//获取文章列表
    public function wdl_index(){
		$where = [];
		$is_show = input("is_show");
		if($is_show){
			$where['is_show'] = $is_show;
		}
		$cat_id = input("cat_id");
		if($cat_id){
			$where['cat_id'] = $cat_id;
		}
	    $count = Db::name("article") ->alias('a')->join('article_cat c','c.id=a.cat_id','left')->field('a.*,c.name')->where($where)->count();
		$list = Db::name("article") ->alias('a')->join('article_cat c','c.id=a.cat_id','left')->field('a.*,c.name')->where($where)->order('a.id desc')->paginate(10,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
				$data[$k]['add_time'] = d($v['add_time']);
			}
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));

    }
	// 添加文章
	public function wdl_add(){
        $cat_list = Db::name("article_cat")->field('id,name,pid')->select();
        $tree = Tree::instance();
        $tree->init(collection($cat_list)->toArray(), 'pid');
        $cat_list = $tree->getTreeList($tree->getTreeArray(0), 'name');
		$this -> assign("cat_list",$cat_list);
		return view();
	}
	//文章添加处理
	public function wdl_add_do(){
		
		$data=input();
		if(isset($data['is_show'])){
		  $data['is_show'] = 1;
		}else{
		  $data['is_show'] = 2;
		}
        $where['title'] = $data['title'];
		$find = Db::name("article")->where($where)->find();
		if($find){
			$this->error('文章标题'.$data['title'].'已经存在');
		}
		$data['add_time'] = time();
		$do=Db::name("article")->insert($data);	
		if($do){
			$this->success("添加成功");
		}else{
			$this->error('添加失败，请稍后重试！');
		}

	}
	// 编辑文章
	public function wdl_edit(){
		$id = input('id',0,'intval');
		$data = Db::name("article")->where('id',$id)->find();
		$this -> assign("data",$data);
	
		$cat_list = Db::name("article_cat")->field('id,name,pid')->select();
		$tree = Tree::instance();
        $tree->init(collection($cat_list)->toArray(), 'pid');
        $cat_list = $tree->getTreeList($tree->getTreeArray(0), 'name');
		$this -> assign("cat_list",$cat_list);
		return view();
	}

	//文章编辑处理
	public function wdl_edit_do(){
		$data=input();
		if(isset($data['is_show'])){
		  $data['is_show'] = 1;
		}else{
		  $data['is_show'] = 2;
		}
		$where['title'] = ['eq',$data['title']];
		$where['id'] = ['neq',$data['id']];
		$find = Db::name("article")->where($where)->where("id","neq",$data["id"])->find();
		if($find){
			$this->error('文章标题'.$data['title'].'已经存在');
		}
		$do=Db::name("article")->update($data);	
		if($do !== false){
			$this->success("编辑成功");
		}else{
			$this->error('没有修改');
		}

	}
	// 删除文章
	public function wdl_del(){
		$id=input("id",0,'intval');
		if($id == 0){
			 $this->error("请选择文章");
		}
        $res =  Db::name("article")->where("id",$id)->delete();
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
	}
	 //文章管理end-------------------------------------------------------------------------------------------------------------------------------------
	//文章分类start---------------------------------------------------------------------------------------------------------------------------------
	//分类列表
	public function cat(){
		return view("cat");
	}
	//获取分类列表
	public function wdl_cat(){
		$data = Db::name('article_cat')->order('id desc')->select();
        $tree = Tree::instance();
        $tree->init(collection($data)->toArray(), 'pid');
        $data = $tree->getTreeList($tree->getTreeArray(0), 'name');
//        foreach ($data as &$item) {
//                $item = str_replace('&nbsp;', ' ', $item);
//        }
		if($data){
		    foreach($data as $k=>$v){
			    $data[$k]['count'] =  Db::name('article')->where('cat_id',$v['id'])->count();
			}
		
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>count($data)));
	}
	//分类添加
	public function wdl_cat_add(){
        $data = Db::name('article_cat')->order('id desc')->select();
        $tree = Tree::instance();
        $tree->init(collection($data)->toArray(), 'pid');
        $data = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $this->assign("cat_list",$data);
		return view('cat_add');
	}
	//分类添加处理
	public function wdl_cat_add_do(){
		$data = input();
		$find = Db::name('article_cat')->where("name",$data['name'])->find();
		if($find){
			$this->error($data['name'].'分类名称已存在！');
		}
	    $data['add_time'] = time();
		$rt = Db::name('article_cat')->insert($data);
		if($rt){
			$this->success('添加成功');
		}else{
			$this->error('添加失败！');
		}
	}
	//分类编辑
	public function wdl_cat_edit(){
		$id = input("id");
		$data =  Db::name('article_cat')->where("id",$id)->find();
		$this->assign('data',$data);
        $data = Db::name('article_cat')->order('id desc')->select();
        $tree = Tree::instance();
        $tree->init(collection($data)->toArray(), 'pid');
        $data = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $this->assign("cat_list",$data);
		return view('cat_edit');
	}
	//分类编辑处理
	public function wdl_cat_edit_do(){
		$data = input();
		$where['name'] = ['eq',$data['name']];
		$where['id'] = ['neq',$data['id']];
		$find = Db::name('article_cat')->where($where)->find();
		if($find){
			$this->error($data['name'].'分类名称已存在！');
		}
		$rt = Db::name('article_cat')->update($data);
		if($rt){
			$this->success('编辑成功');
		}else{
			$this->error('没有修改！');
		}
	}
	//分类删除
	public function wdl_cat_del(){
		$id=input("id",0,'intval');
		if($id == 0){
			 $this->error("请选择分类");
		}
		$count = Db::name('article')->where('cat_id',$id)->count();
		if($count){
            $this->error('该分类下面有文章');
		}
        $res = Db::name('article_cat')->where("id",$id)->delete();
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
	}

	//文章分类end---------------------------------------------------------------------------------------------------------------------------------

	
}

