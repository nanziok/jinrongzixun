<?php
namespace app\admin\controller;
use think\Db;
class Anli extends Base {
	//案例管理start--------------------------------------------------------------------------------------------------------------------------------------
	// 案例列表
	public function index(){
	
		return view();
	}
	//获取案例列表
    public function wdl_index(){
		$where = [];
		$status = input("status");
		if($status){
			$where['status'] = $status;
		}
		$title=input("title");
		if ($title) {
            $where['title'] = ['like', '%' . $title . '%'];
        }
	    $count = Db::name("anli")->where($where)->count();
		$list = Db::name("anli") ->where($where)->order('listorder asc,id desc')->paginate(10,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
				$data[$k]['add_time'] = d($v['add_time']);
				$data[$k]['img'] = empty($v['img'])?"/uploads/default/img_default.png":$v["img"];
			}
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));

    }
	// 添加案例
	public function anli_add(){
	return view();
	}
	// 编辑案例
	public function wdl_edit(){
		$id = input('id',0,'intval');
		$data = Db::name("anli")->where('id',$id)->find();
		$this -> assign("data",$data);

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
		$id = input('id',0,'intval');
		if($id){
			$where['title'] = ['eq',$data['title']];
			$where['id'] = ['neq',$id];
		}else{
			$where['title'] = ['eq',$data['title']];
		}
		$find = Db::name("anli")->where($where)->find();
		if($find){
			$this->error('案例名称'.$data['title'].'已经存在');
		}
        if($id){
		   $img =  Db::name("anli")->where('id',$data['id'])->value('img');
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
			$ret = Db::name("anli")->update($data);
			if($ret){
			     $this->success("修改成功");
			}else{
				 $this->error("没有任何修改");
			}
		}else{
			$data['add_time'] = time();
			$ret = Db::name("anli")->insertGetId($data);
			if($ret){
			     $this->success('添加成功');
			}else{
				 $this->error("请稍后重试");
			}
		}
	}
	// 删除案例
	public function wdl_del(){
		$id=input("id",0,'intval');
		if($id == 0){
			 $this->error("请选择案例");
		}
		$img = Db::name("anli")->where("id",$id)->value('img');
		if ($img != '' && is_file(ROOT_PATH . '/public_html' . $img)){
           @unlink(ROOT_PATH . '/public_html' . $img);
        }
        $res =  Db::name("anli")->where("id",$id)->delete();
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
	}
	 //案例管理end-------------------------------------------------------------------------------------------------------------------------------------

	
}

