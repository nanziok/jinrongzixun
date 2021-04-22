<?php
namespace app\admin\controller;
use \tp5er\Backup;

class Data extends Base
{

	
	public $config=array(
		'path'     => './Data/',//数据库备份路径
		'part'     => 20971520,//数据库备份卷大小
		'compress' => 0,//数据库备份文件是否启用压缩 0不压缩 1 压缩
		'level'    => 9 //数据库备份文件压缩级别 1普通 4 一般  9最高
	);
	
	public $file;

	//数据库表文件
	public function table_list(){
		
		return view();
	}
	//获取数据库列表
	public function wdl_table_list(){
	
	    $db= new Backup($this->config);
		$data=$db->dataList();
		if($data){
		   foreach($data as $k=>$v){
		     $data[$k]['data_length'] = count_size($v['data_length']);
		   }
		}
	    echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>count($data)));
	
	}
	//数据库备份列表
	public function backup(){
	
		return view();
	}
    //获取备份列表
    public function wdl_backup(){
	
		$db= new Backup($this->config);
		$list=$db->fileList();
		if($list){
		  	foreach($list as $k=>$v){
			    $list[$k]['name']=str_replace(array("-",":"," "),"",$k).'.sql';
				$list[$k]['size']=count_size($v['size']);
				$list[$k]['add_time']=d($v['time']);
				$list[$k]['xiazai'] = date('Ymd-His',$v['time']).'-1.sql';
		    }
		    $list=array_reverse($list);
		}
	    echo json_encode(array("code"=>0,'data'=>$list,'message'=>'成功','count'=>count($list)));
	
	}

	//删除
	public function wdl_del(){
		
		$t=trim(input("id"));
		$db= new Backup($this->config);
		
		$db->delFile($t);
		
		$this->success("删除成功！");
		
	}
	
	
	//备份操作
	public function wdl_backup_do(){
	     
		 $db= new Backup($this->config);
		 $file=['name'=>date('Ymd-His'),'part'=>1];	
		
		 
		 $list=$db->dataList();
		 
		 foreach($list as $k=>$v){
 		 	$start= $db->setFile($file)->backup($v['name'], 0);
		 }
		$this->success("备份成功！");
	
	}

}
