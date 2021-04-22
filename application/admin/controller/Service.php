<?php
namespace app\admin\controller;
use think\Db;
class Service extends Base {
	//服务点管理start--------------------------------------------------------------------------------------------------------------------------------------
	//服务点列表
	public function index(){
	
		return view();
	}
	//获取服务点列表
    public function wdl_index(){
		$where = [];
		$status = input("status");
		if($status){
			$where['status'] = $status;
		}
		$name=input("name");
		if ($name) {
            $where['name'] = ['like', '%' . $name . '%'];
        }
	    $count = Db::name("service")->where($where)->count();
		$list = Db::name("service") ->where($where)->order('listorder asc,id desc')->paginate(10,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
				$data[$k]['add_time'] = d($v['add_time']);
				$data[$k]['img'] = empty($v['img'])?"/uploads/default/img_default.png":$v["img"];
			}
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));

    }
	// 添加服务点
	public function service_add(){
	return view();
	}
	// 编辑服务点
	public function wdl_edit(){
		$id = input('id',0,'intval');
		$data = Db::name("service")->where('id',$id)->find();
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
			$where['name'] = ['eq',$data['name']];
			$where['id'] = ['neq',$id];
		}else{
			$where['name'] = ['eq',$data['name']];
		}
		$find = Db::name("service")->where($where)->find();
		if($find){
			$this->error('服务点名称'.$data['name'].'已经存在');
		}
        if($id){
		   $img =  Db::name("service")->where('id',$data['id'])->value('img');
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
			$ret = Db::name("service")->update($data);
			if($ret){
			     $this->success("修改成功");
			}else{
				 $this->error("没有任何修改");
			}
		}else{
			$data['add_time'] = time();
			$ret = Db::name("service")->insertGetId($data);
			if($ret){
			     $this->success('添加成功');
			}else{
				 $this->error("请稍后重试");
			}
		}
	}
	// 删除服务点
	public function wdl_del(){
		$id=input("id",0,'intval');
		if($id == 0){
			 $this->error("请选择案例");
		}
		$img = Db::name("service")->where("id",$id)->value('img');
		if ($img != '' && is_file(ROOT_PATH . '/public_html' . $img)){
           @unlink(ROOT_PATH . '/public_html' . $img);
        }
        $res =  Db::name("service")->where("id",$id)->delete();
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
	}
	 //服务点管理end-------------------------------------------------------------------------------------------------------------------------------------

	
}

