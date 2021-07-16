<?php
namespace app\admin\controller;
use think\Db;
class Tousu extends Base {
	//投诉管理start--------------------------------------------------------------------------------------------------------------------------------------
	// 投诉列表
	public function index(){
        $status = input("status",0,'intval');
        $this->assign('status',$status);
		return view();
	}
	//获取投诉列表
    public function wdl_index(){
		$where = [];
		$status = input("status",0,'intval');
		if($status){
			$where['y.status'] = $status;
		}
	    $count = Db::name("user_yijian")->alias('y')->join('user u','u.id=y.user_id','left')->field('y.*,u.phone as userphone')->where($where)->count();
		$list = Db::name("user_yijian")->alias('y')
            ->join('user u','u.id=y.user_id','left')
            ->field('y.*,u.username  as user_username,u.headimg as user_headimg, u.nickname as user_nickname')
            ->where($where)
            ->order('y.id desc')
            ->paginate(20,false,['query'=>input()]);
		$data = $list->items();
		if($data){
			foreach($data as $k=>$v){
				$data[$k]['add_time'] = d($v['add_time']);
				$data[$k]['status'] = $v['status'] == 1?"<em class='red'>待处理</em>":"<em class='green'>已完成</em>";
				$data[$k]['mark'] = empty($v['mark'])?"--":$v['mark'];
				$data[$k]['phone'] = empty($v['phone'])?"--":$v['phone'];
				$data[$k]['userphone'] = empty($v['userphone'])?"<b class='red'>删除用户</b>":$v['userphone'];
				$data[$k]['img'] =  empty($v["img"])?"":explode(',',$v['img']);
			}
		}
		echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));

    }
	// 备注投诉
	public function wdl_edit(){
		$id = input('id',0,'intval');
		$data = Db::name("user_yijian")->where('id',$id)->find();
		$this -> assign("data",$data);
		return view();
	}

	//备注投诉处理
	public function wdl_edit_do(){
		$data=input();
		$do=Db::name("user_yijian")->update($data);	
		if($do){
			$this->success("处理成功");
		}else{
			$this->error('没有修改');
		}
	}
	 //投诉管理end-------------------------------------------------------------------------------------------------------------------------------------

	
}

