<?php
namespace app\admin\controller;
use think\Db;
use think\Exception;

class Xitong extends Base
{
	public function common(){
		$this->assign("title","系统设置");

	}
	//设置数据
	public  function wdl_setdata(){
	  $data = input();
	  $table = $data['table'];
	  unset($data['table']);
      $res =Db::name($table)->update($data);
	  if ($res) {
		$this->success('设置成功');
	  } else {
		$this->error('设置失败');
	  }
	}
	/*系统设置start--------------------------------------------------------------------------------------------------*/
    //设置首页
    public function index(){
        $this->common();
		$data=Db::name("xitong")->where("id=1")->find();
		$data["jijin_setting"] = json_decode($data["jijin_setting"], true);
		$array = [
            "jijin_switch"  => "percent",
            "jijin_fee_percent"  => "1",
            "jijin_fee_ladder1"  => "",
            "jijin_fee_ladder2"  => "",
            "jijin_fee_ladder3"  => "",
            "jijin_fee_ladder4"  => "",
            "jijin_fee_ladder5"  => "",
            "jijin_fee_ladder6"  => "",
            "jijin_service_time" => "year",
            "chat_description"  => "",
            "jijin_description"  => ""
        ];
        $data["jijin_setting"] += $array;
        $this->assign("data",$data);
	    return view('index');
    }
	
	//设置首页修改
	public function wdl_edit_do(){
	
		$data=input();

		if(isset($data['app'])){
		  $data['app'] = 1;
		}else{
		  $data['app'] = 2;
		}

		if(isset($data['reg'])){
		  $data['reg'] = 1;
		}else{
		  $data['reg'] = 2;
		}
		$this->editJijinSet($data);

            $do=Db::name("xitong")->strict(false)->update($data);
		if($do){
			$this->success("修改成功！");
		}else{
			$this->error("没有做出任何修改！");
		}
	}
	/*系统设置end--------------------------------------------------------------------------------------------------*/
	/*菜单设置start--------------------------------------------------------------------------------------------------*/
	//菜单列表
	public function caidan_index(){
		return view();
	}
    //获取菜单列表
	public function wdl_caidan_index(){
	    $list=Db::name("user_admin_action")->where("fid=0")->order("listorder asc")->select();
		$data = [];
		$kk = 0;
		foreach($list as $k=>$v){
              $data[$kk]['id'] = $v['id'];
			  $data[$kk]['name'] = $v['name'];
			  $data[$kk]['m'] = $v['m'];
			  $data[$kk]['a'] = $v['a'];
			  $data[$kk]['listorder'] = $v['listorder'];
			  $data[$kk]['is_show'] = $v['is_show'];
			  $kk++;
			  $son=Db::name("user_admin_action")->where("fid=".$v['id'])->order("listorder asc")->select();
			  if($son){
				  foreach($son as $k1=>$v1){
				      $data[$kk]['id'] = $v1['id'];
					  $data[$kk]['name'] = '------  '.$v1['name'];
					  $data[$kk]['m'] = $v1['m'];
					  $data[$kk]['a'] = $v1['a'];
					  $data[$kk]['listorder'] = $v1['listorder'];
					  $data[$kk]['is_show'] = $v1['is_show'];
					  $kk++;
				  }
			  }

		}
		$count = Db::name("user_admin_action")->count();
	    echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
	}
	//菜单添加
	public function wdl_caidan_add(){
		$cat=Db::name("user_admin_action")->where("fid=0")->order("listorder asc")->select();
		$this->assign("cat",$cat);

		return view('caidan_add');
	}
	//菜单添加处理
	public function wdl_caidan_add_do(){
		
		$data=input();
		if(isset($data['is_show'])){
		  $data['is_show'] = 1;
		}else{
		  $data['is_show'] = 0;
		}
		$do=Db::name("user_admin_action")->insert($data);	
		if($do){
			$this->success("添加成功");
		}else{
			$this->error('添加失败，请稍后重试！');
		}

	}
    //菜单编辑
	public function wdl_caidan_edit(){
		$id=input("id");
		$data=Db::name("user_admin_action")->where("id=".$id)->find();
		$this->assign("data",$data);
		
		$cat=Db::name("user_admin_action")->where("fid=0")->order("listorder asc")->select();
		$this->assign("cat",$cat);
		
		return view('caidan_edit');
	
	}
	//菜单编辑处理
	public function wdl_caidan_edit_do(){
		$data=input();
		if(isset($data['is_show'])){
		  $data['is_show'] = 1;
		}else{
		  $data['is_show'] = 0;
		}
		$do=Db::name("user_admin_action")->update($data);
		if($do){
			$this->success("修改成功");
		}else{
			$this->error('没用修改！');
		}
	
	}
	//菜单删除处理
	public function wdl_caidan_del(){
		$id=input("id");
		$do = Db::name("user_admin_action")->where("id='$id' or fid='$id'")->delete();
		Db::name("user_admin_auth")->where("action_id='$id'")->delete();
	    if($do){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
	
	 //设置地区start---------------------------------------------------------------------------
    //设置地区index
    public function region_list() {
        $parent_id = input('parent_id',1,'intval');
        $this->assign('parent_id',$parent_id);

		$region_type = Db::name("region")->where("region_id=".$parent_id)->value('region_type');
        $this->assign('region_type',$region_type+1);
    	return view();
    }
	//获取地区信息
	public function wdl_region_index(){
		$parent_id = input('parent_id');
	    $data=Db::name("region")->where("parent_id=".$parent_id)->order("region_id asc")->select();
		$count = Db::name("region")->where("parent_id=".$parent_id)->count();
	    echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
	}
    //添加地区
    public function wdl_region_add() {
		$parent_id = input('id');
		$this->assign('parent_id',$parent_id);
        $region_type = Db::name("region")->where("region_id=".$parent_id)->value('region_type');
        $this->assign('region_type',$region_type+1);
    	return view('region_add');
    }
    //添加省份执行
    public function wdl_region_add_do() {
    	$data = input();
    	$where['region_name'] = ['eq',$data['region_name']];
    	$where['parent_id'] = ['eq',$data['parent_id']];
    	$find = Db::name('region')->where($where)->find(); 
    	if($find) {
    		$this->error('地区'.$data['region_name'].'在本省或市已存在');
    	}   
    	if(empty($data['parent_id'])){
    		$this->error('保存参数有误');
    	}
    	$res = Db::name('region')->insert($data);
    	if ($res) {
    		$this->success('地区添加成功');
    	} else {
    		$this->error('地区添加失败');
    	}
    }
    
    //编辑省及市、县份
    public function wdl_region_edit() {
    	$id = input('id');
    	$data = Db::name('region')->where('region_id', $id)->find();
    	$this->assign('data', $data);
    	return view('region_edit');
    }
    
    //编辑省及市、县份执行
    public function wdl_region_edit_do() {
    	$data = input();
    	$where['region_name'] = ['eq',$data['region_name']];
    	$where['parent_id'] = ['eq',$data['parent_id']];
    	$where['region_id'] = ['neq',$data['region_id']];
    	$find = Db::name('region')->where($where)->find(); 
    	if($find) {
    		$this->error('地区'.$data['region_name'].'在本省或市已存在');
    	}   	
    	$res = Db::name('region')->update($data);
    	if ($res) {
    		$this->success('修改成功');
    	} else {
    		$this->error('没有做修改');
    	}
    }
    
    //删除省份及市、县
    public function wdl_region_del() {
    	$id = input('id');
    	$arr = Db::name('region')->where('parent_id', $id)->select();
    	if($arr){
			$this->error('删除失败,该地区下有市或县区');
    	}

        $find = Db::name('shipping_area_region')->where('region_id',$id)->count();
        if($find){
			$this->error('删除失败,该地区已被设置成配送区域');
    	}
    	$del = Db::name('region')->where('region_id', $id)->delete();
    	if($del){
    		$this->success('删除成功');
    	} else {
    		$this->error('删除失败');
    	}
    }
    
    //设置地区end------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   //快递管理start------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   //快递列表
	public function shipping() {

		return view();
   }	
   public  function wdl_shipping_index(){
	    $data=Db::name("shipping")->order("shipping_id asc")->select();
		$count = Db::name("shipping")->count();
	    echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));
   }
	//添加快递名称
	public function wdl_shipping_add() {
		return view('shipping_add');
	}
	
	//添加快递名称执行
	public function wdl_shipping_add_do() {
		$data = input();
		$data['add_time'] = time();		
        $find =  Db::name('shipping')->where('shipping_name',$data['shipping_name'])->find();
        if ($find) {
            $this->error($data['shipping_name'].'已经添加过了');
        }
		$res =  Db::name('shipping')->insert($data);
		if ($res) {
            $this->success('添加成功');
        } else {
            $this->error('添加失败');
        }
        
	}
	
	//编辑快递名称
	public function wdl_shipping_edit() {
		$id = input('id');
		$data = Db::name('shipping')->where('shipping_id', $id)->find();
		$this->assign('data', $data);
		return view('shipping_edit');
	}
	
	//编辑快递名称执行
	public function wdl_shipping_edit_do() {
		$data = input();
		$data['add_time'] = time();
        $where['shipping_name'] = ['eq',$data['shipping_name']];
        $where['shipping_id'] = ['neq',$data['shipping_id']];
        $find = Db::name('shipping')->where($where)->find();
        if ($find) {
            $this->error($data['shipping_name'].'已经添加过了');
        }
        $do = Db::name("shipping")->update($data);
        if ($do) {
            $this->success('修改成功');
        } else {
            $this->error('暂无修改');
        }
	}
	//删除快递名称
	public function wdl_shipping_del() {
		$id = input("id");
		$find = Db::name("shipping_area")->where('shipping_id', $id)->find();
		if($find){
			$this->error('此快递设置有配送信息,不可删除');
		}
        $res = Db::name("shipping")->where('shipping_id', $id)->delete();
        if ($res)
            $this->success('删除成功');
        else
            $this->error('删除失败');
	}
	
	//快递区域区域列表
	public function wdl_shipping_region_list(){
	  $id = input('id');
	  $this->assign('id',$id);
	  return view('shipping_region_list');
	}
	public function wdl_shipping_region_index() {
			$id = input('id');//快递ID
			$shipping_name = Db::name("shipping")->where('shipping_id', $id)->value('shipping_name');
			$data = Db::name('shipping_area')->where('shipping_id='.$id)->select();
			$count = Db::name('shipping_area')->where('shipping_id='.$id)->count();
	        if($data){
	        	foreach($data as $k=>$v){
					$region_str='';
	        		unset($array);
	        		$array = [];
	        		$region_list = Db::name('shipping_area_region')->field('region_id')->where('shipping_area_id',$v['shipping_area_id'])->select();
	        		if($region_list){
	        			foreach($region_list as $key=>$val){
	        			 $region_name=get_region_info($val['region_id']);
	        			 array_push($array,$region_name);
	        			}
	        			$region_str = implode(',',$array);
	        		}
	        		$data[$k]['region_str'] = $region_str;
					$data[$k]['shipping_name'] = $shipping_name;
	        	}
	        }	
			
		  echo json_encode(array("code"=>0,'data'=>$data,'message'=>'成功','count'=>$count));

}

  //添加快递区域名称
   public function wdl_shipping_region_add() {
		$id = input('id');
		$shipping_area_arr = Db::name('shipping_area')->where('shipping_id', $id)->column('shipping_area_id');//第一步
		$region_str = implode(',',$shipping_area_arr);
		$region_arr_no = Db::name('shipping_area_region')->where('shipping_area_id in ('.$region_str.')')->column('region_id');//第二步

		$data = Db::name('region')->where('parent_id in(0,1)')->order('region_id asc')->select();
		foreach($data as $k=>$v){
			$data[$k]['is_close'] = in_array($v['region_id'], $region_arr_no)?1:0;//判断是否可选
		}
		$this->assign('id', $id);
		$this->assign('data', $data);
		return view('shipping_region_add');
	}	
	
	//添加快递名称执行
	public function wdl_shipping_region_add_do() {
	    $data = input();
		if(!isset($data['area'])){
        	$this->error('请选择区域');
		}
		$area = $data['area'];
		unset($data['area']);
        //判断同一个名称的是否添加过了
		$map['shipping_id'] = $data['shipping_id'];
		$map['name'] = $data['name'];
		
		$find = Db::name('shipping_area')->where($map)->find();
        if($find){
        	$this->error($data['name'].'已经添加过了');
        }
        $data['add_time'] = time();
		Db::startTrans();
		try{
            $shipping_area_id = Db::name('shipping_area')->insertGetId($data);
			if($shipping_area_id){
			   $data_region['shipping_area_id'] = $shipping_area_id;
               foreach ($area as $k => $v) {
					$data_region['region_id'] = $k;
					$res = Db::name('shipping_area_region')->insert($data_region);
				    if(!$res){
	        	 		throw new Exception('添加失败,请稍后重试');
					}
				}
				Db::commit();
			}else{
			  throw new Exception('添加失败,请稍后重试');
			}

	     }catch (\Exception $e) {
			    // 回滚事务
				    Db::rollback();
					$message= $e -> getMessage();
					$this->error($message);
		} 
		$this->success("添加成功");
		
	}
	
	//编辑配送区域列表
	public function wdl_shipping_region_edit() {
		$id = input('id');
        $info = Db::name('shipping_area')->where('shipping_area_id', $id)->find();

		$region_arr = Db::name('shipping_area_region')->where('shipping_area_id', $id)->column('region_id');//找已经选过的
		$data = Db::name('region')->where('parent_id in(0,1)')->order('region_id asc')->select();
        

        $shipping_area_arr = Db::name('shipping_area')->where('shipping_id='.$info['shipping_id'].' and shipping_area_id !='.$id)->column('shipping_area_id');//第一步
		$region_str = implode(',',$shipping_area_arr);
		$region_arr_no = Db::name('shipping_area_region')->where('shipping_area_id in ('.$region_str.')')->column('region_id');//第二步

		foreach($data as $k=>$v){
			$data[$k]['is_open'] = in_array($v['region_id'], $region_arr)?1:0;
			$data[$k]['is_close'] = in_array($v['region_id'], $region_arr_no)?1:0;
		}
        
		$this->assign('info', $info);
		$this->assign('id', $id);
		$this->assign('data', $data);
		
		return view('shipping_region_edit');
		
	}
	
	//编辑配送区域列表执行
	public function wdl_shipping_region_edit_do() {
	    $data = input();
		if(!isset($data['area'])){
        	$this->error('请选择区域');
		}
		$area = $data['area'];
		unset($data['area']);
        
        //判断同一个名称的是否添加过了
		$map['shipping_id'] = ['eq', $data['shipping_id']];
		$map['name'] = ['eq', $data['name']];
		$map['shipping_area_id'] = ['neq', $data['shipping_area_id']];
		$find = Db::name('shipping_area')->where($map)->find();
        if($find){
        	$this->error($data['shipping_name'].'已经添加过了');
        }
        $data['add_time'] = time();
		Db::startTrans();
		try{
            $result = Db::name('shipping_area')->update($data);
			$de = Db::name('shipping_area_region')->where('shipping_area_id='.$data['shipping_area_id'])->delete();
			if(!$de){
			   throw new Exception('修改失败,请稍后重试');
			}
			if($result){
			   $data_region['shipping_area_id'] = $data['shipping_area_id'];
               foreach ($area as $k => $v) {
					$data_region['region_id'] = $k;
					$res = Db::name('shipping_area_region')->insert($data_region);
				    if(!$res){
	        	 		throw new Exception('修改失败,请稍后重试');
					}
				}
				Db::commit();
			}else{
			  throw new Exception('修改失败,请稍后重试');
			}

	     }catch (\Exception $e) {
			    // 回滚事务
				Db::rollback();
				$message= $e -> getMessage();
				$this->error($message);
		} 
		$this->success("修改成功");
	
  }
	
	//删除配送区域列表
	public function wdl_shipping_region_del() {
		$id = input('id');
        Db::startTrans();
	    try{
           $res1 = Db::name('shipping_area')->where('shipping_area_id', $id)->delete();
		   if(!$res1){
		      	throw new Exception('删除失败,请稍后重试');
		   }
		   $res2 = Db::name('shipping_area_region')->where('shipping_area_id', $id)->delete();
		   if(!$res1){
		      	throw new Exception('删除失败,请稍后重试');
		   }
		   Db::commit();
		}catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
			$message= $e -> getMessage();
			$this->error($message);
		} 
		$this->success("删除成功");

	}
  //快递管理end------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  public function wdl_clear_cache(){
  	 $path = "../runtime/";
        if(is_dir($path)){
			    //扫描一个文件夹内的所有文件夹和文件并返回数组
		   $p = scandir($path);
		   foreach($p as $val){
		    if($val !="." && $val !=".."){
		     //如果是目录则递归子目录，继续操作
		     if(is_dir($path.$val)){
		      //子目录中操作删除文件夹和文件
		      $this->deldir($path.$val.'/');
		      //目录清空后删除空文件夹
		      @rmdir($path.$val.'/');
		     }else{
		      //如果是文件直接删除
		      unlink($path.$val);
		     }
		    }
		   }
      }
     
  	 $this->success('清除成功');
  }
  
  
  public function deldir($path){
   //如果是目录则继续
	if(is_dir($path)){
	    //扫描一个文件夹内的所有文件夹和文件并返回数组
	   $p = scandir($path);
	   foreach($p as $val){
	    //排除目录中的.和..
	    if($val !="." && $val !=".."){
	     //如果是目录则递归子目录，继续操作
	     if(is_dir($path.$val)){
	      //子目录中操作删除文件夹和文件
	      $this->deldir($path.$val.'/');
	      //目录清空后删除空文件夹
	      @rmdir($path.$val.'/');
	     }else{
	      //如果是文件直接删除
	      unlink($path.$val);
	     }
	    }
	   }
	
	 }
	}

	private function editJijinSet(&$data){
        $temp_array = [];
        $temp_array["jijin_switch"] = $data["jijin_switch"];
        $temp_array["jijin_fee_percent"] = $data["jijin_fee_percent"];
        $temp_array["jijin_fee_ladder1"] = $data["jijin_fee_ladder1"];
        $temp_array["jijin_fee_ladder2"] = $data["jijin_fee_ladder2"];
        $temp_array["jijin_fee_ladder3"] = $data["jijin_fee_ladder3"];
        $temp_array["jijin_fee_ladder4"] = $data["jijin_fee_ladder4"];
        $temp_array["jijin_fee_ladder5"] = $data["jijin_fee_ladder5"];
        $temp_array["jijin_fee_ladder6"] = $data["jijin_fee_ladder6"];

        $temp_array["jijin_service_time"] = $data["jijin_service_time"];
        $temp_array["jijin_description"] = $data["jijin_description"];
        $temp_array["chat_description"] = $data["chat_description"];

        unset($data["jijin_switch"], $data["jijin_fee_percent"], $data["jijin_fee_ladder1"], $data["jijin_fee_ladder2"], $data["jijin_fee_ladder3"], $data["jijin_fee_ladder4"], $data["jijin_fee_ladder5"], $data["jijin_fee_ladder6"], $data["jijin_service_time"], $data["chat_description"], $data["jijin_description"]);
        //校验数据正确性
        if($temp_array["jijin_switch"] == 'ladder'){
            if(!is_numeric($temp_array["jijin_fee_ladder1"]) || !is_numeric($temp_array["jijin_fee_ladder2"]) || !is_numeric($temp_array["jijin_fee_ladder3"]) || !is_numeric($temp_array["jijin_fee_ladder4"]) || !is_numeric($temp_array["jijin_fee_ladder5"]) || !is_numeric($temp_array["jijin_fee_ladder6"])){
                $this->error("阶梯收费标准写错错误");
            }
        }else if($temp_array["jijin_switch"] == 'percent'){
            if(empty($temp_array["jijin_fee_percent"])){
                $this->error("基金收费比例不能为空");
            }
            if(!is_numeric($temp_array["jijin_fee_percent"])){
                $temp_array["jijin_fee_percent"] = str_replace("%","",$temp_array["jijin_fee_percent"]);
                if(!is_numeric($temp_array["jijin_fee_percent"])){
                    $this->error("基金收费比例不能包含除%之外的特殊字符");
                }
            }
        }else{
            $this->error("基金计费类型有误");
        }
        if (!in_array($temp_array["jijin_service_time"], ['year','month','week','day'])){
            $this->error("选择的服务周期有误");
        }
        $temp_array["jijin_service_time"] = $temp_array["jijin_service_time"];
        $data["jijin_setting"] = json_encode($temp_array);
    }
	  
}
