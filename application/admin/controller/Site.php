<?php
namespace app\admin\controller;
use think\Db;
class Site extends Base {
	//首页
	public function main(){
	    $start_date =date("Y-m-d H:i:s",mktime(0,0,0,date('m'),1,date('Y')));//当月开始时间
		$end_date =date("Y-m-d H:i:s",mktime(23,59,59,date('m'),date('t'),date('Y')));//当月结束时间
    	$begintime=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d'),date('Y')));//当天的开始时间
        $endtime=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1);//当天的结束时间

        $this->assign('start_date',$start_date);
        $this->assign('end_date',$end_date);
        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);
	    
	    //今日注册数
		$where['add_time'] = ['between',[strtotime($begintime),strtotime($endtime)]];
        $num1 = Db::name('user_fabu')->where($where)->count();
		$num2 = Db::name('user')->where($where)->count();
	    $this->assign('num1',$num1);
	    $this->assign('num2',$num2);
       	//服务器信息
		$server['os']=php_uname("s");
		$server['os_version']=php_uname("r");
		$server['php_version']=PHP_VERSION;
		$server['apache_version']=$_SERVER['SERVER_SOFTWARE'];
		$server['host']=$_SERVER["HTTP_HOST"];
		$server['ip']=GetHostByName($_SERVER['SERVER_NAME']);
		$this->assign('server',$server);

         //投诉处理
         $num3 = Db::name('user_yijian')->where('status=1')->count();
         $this->assign('num3',$num3);
		
		//访问情况
        		//统计
		$year = date('y',time());
		$this->assign("year",date('Y',time()));
		$member_arr = array();
		$goods_arr = array();
		for($a=1;$a<13;$a++){
			unset($where);
			$month = $a<10?'0'.$a:$a;
		    $where['FROM_UNIXTIME(add_time,\'%y-%m\')'] = $year.'-'.$month;
		    $count = Db::name('user')->where($where)->count();
		    $count1 = Db::name('user_fabu')->where($where)->count();
		    
            array_push($member_arr,$count);
            array_push($goods_arr,$count1);
		 }
		 $ru[0]['color'] = '#009688';
		 $ru[0]['name'] = '会员注册量';
		 $ru[0]['data'] = $member_arr;
		 $this->assign("data1",json_encode($ru));
		 $ru1[0]['color'] = '#009688';
		 $ru1[0]['name'] = '会员发布信息量';
		 $ru1[0]['data'] = $goods_arr;
         $this->assign("data2",json_encode($ru1));

	    return view('main');
	}


	
}