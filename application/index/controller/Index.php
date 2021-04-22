<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Cookie;
use think\Session;
class Index extends Controller {
	
	public function index(){
		$data = Db::name('xitong')->where('id=1')->field('android_url,ios_url')->find();
		$this->assign('android_url',$data['android_url']);
		$this->assign('ios_url',$data['ios_url']);
		return view();
	}
	public function dengdai(){
		
		return view();
	}
	public function aa(){
		
    $this->getVideoCover('/uploads/fabu/20210329/9e719f58dda9723c02eeff22f6b46856.mp4',1,'abc');
		
		
	}
	
	
			function getVideoCover($file,$time,$name) {

						if(empty($time))$time = '1';//默认截取第一秒第一帧
						
						$strlen = strlen($file);
						
						// $videoCover = substr($file,0,$strlen-4);
						
						// $videoCoverName = $videoCover.'.jpg';//缩略图命名
						
						//exec("ffmpeg -i ".$file." -y -f mjpeg -ss ".$time." -t 0.001 -s 320x240 ".$name."",$out,$status);
						
						$str = "ffmpeg -i ".$file." -y -f mjpeg -ss 3 -t ".$time." -s 320x240 ".$name;
						
						//echo $str."";
						
						$result = system($str);
						print_r($result);exit;
						}



}