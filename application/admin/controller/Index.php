<?php /** @noinspection ALL */

namespace app\admin\controller;
use think\Db;
class Index extends Base {
	public function index(){
		$map['is_show']=1;
		$map['fid']=0;
            $menu=Db::name("user_admin_action")->where($map)->order("listorder asc")->field('id,name,m,a,icon')->select();
		$idarray=Db::name("user_admin_auth")->where("role_id=".$this->admin['user_cat'])->column('action_id');
		if($menu){
		   foreach($menu as $k=>$v){
			    if(!in_array($v['id'],$idarray)){
					unset($menu[$k]);
				}else{
				   $map['fid'] = $v['id'];
		           $sonlist = Db::name("user_admin_action")->where($map)->order("listorder asc")->field('id,name,m,a')->select();
				   $son = '';
				   if($sonlist){
                      foreach($sonlist as $key=>$val){
					      if(!in_array($val['id'],$idarray)){
								unset($sonlist[$key]);
							}
					  }
				       $son = $sonlist;
				   }
				   $menu[$k]['son'] = $son;
				}
			   
		   }
		}
		$this->assign("menu",$menu);
		return view('index');
	}	
	public function main(){
	    return view();
	}

	    //��ȡͼƬ
    public function wdl_get_img(){
        $id=input("id",0,'intval');
        $start = input("start",0,'intval');
        $table = input("table",'','strval');
		$zid = input('zid');
		$field = input('field');
        $img= Db::name($table)->where($zid.'='.$id)->column($field);
        $newarr = array();
        if($img){
		   foreach($img as $k=>$v){
            $newarr[$k]['alt'] = '';
            $newarr[$k]['src'] = is_url($v)?$v:"http://".$_SERVER['SERVER_NAME'].$v;
            $newarr[$k]['thumb'] = is_url($v)?$v:"http://".$_SERVER['SERVER_NAME'].$v;
          }
		}
        if($start>0){
        	$start--;
        }
        $ret['start'] = $start;
        $ret['data'] = $newarr;
        echo json_encode($ret);exit;
    }


	
}