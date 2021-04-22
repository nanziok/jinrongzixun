<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Cookie;
use think\Db;
function sql(){
	return db()->getLastSql();
}

function count_size($bit){  
        $type = array('B','KB','MB','GB','TB');  
        for($i = 0; $bit >= 1024; $i++)//单位每增大1024，则单位数组向后移动一位表示相应的单位  
        {  
            $bit/=1024;  
        }  
        return (floor($bit*100)/100).$type[$i];//floor是取整函数，为了防止出现一串的小数，这里取了两位小数  
} 
function get_user_fabu($id){
	 $count1 = Db::name("user")->where('id',$id)->value('fabu');
	 $count2 = Db::name("user_fabu")->where(array("user_id"=>$id,"is_delete"=>1))->count();
	 return $count1-$count2;
}
function get_user_zan($id){
	 $count1 = Db::name("user")->where('id',$id)->value('zan');
	 $count2 = Db::name("user_zan")->alias("z")->join("user_fabu f","f.id=z.fabu_id")->where(array("z.user_id"=>$id,"f.is_delete"=>1))->count();
	 return $count1-$count2;
}

//获取会员头像
function get_headimg($id){
	 $base_url = $_SERVER['HTTP_HOST'];
     $headimg = Db::name("user")->where("id",$id)->value("headimg");
     if($headimg){
	   if(is_url($headimg)){
	      return $headimg;
	   }else{
	      return 'http://'.$base_url.$headimg;
	   }
	 }else{
	   return 'http://'.$base_url.'/uploads/default/head.png';
	 }
 }
 //获取会员昵称
function get_username($id){
     $username = Db::name("user")->where("id",$id)->value("username");
     if($username){
	   return mb_substr($username,0,6,'utf-8');
	 }else{
	   return '未设置';
	 }
 }
  //获取个人信息
 function get_userinfo($id){
 	$info = Db::name("user")->where("id",$id)->field("sex,city,district")->find();
	$info['headimg'] = get_headimg($id);
	$info['username'] = get_username($id);
	$info['area'] = get_region_info($info['city']).'-'.get_region_info($info['district']);
	return $info;
 }
 //检测用户是否点过赞
 function check_zan($user_id,$id){
 	$info = Db::name("user_zan")->where(array("user_id"=>$user_id,"fabu_id"=>$id))->find();
 	return empty($info)?0:1;
 }
/**
 * 是否是手机号码
 *
 * @param string $phone	手机号码
 * @return boolean
 */
function is_phone($phone) {
	if (strlen ( $phone ) != 11 || ! preg_match ( '/^1[3|5|7|8|9]\d{9}$/', $phone )) {
		return false;
	} else {
		return true;
	}
}
/**
 * 验证字符串是否为数字,字母,中文和下划线构成
 * @param string $username
 * @return bool
 */
function is_check_string($str){
	if(preg_match('/^[\x{4e00}-\x{9fa5}\w_]+$/u',$str)){
		return true;
	}else{
		return false;
	}
}
/**
 * 是否为一个合法的email
 * @param sting $email
 * @return boolean
 */
function is_email($email){
	if (filter_var ($email, FILTER_VALIDATE_EMAIL )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 是否为一个合法的url
 * @param string $url
 * @return boolean
 */
function is_url($url){
	if (filter_var ($url, FILTER_VALIDATE_URL )) {
		return true;
	} else {
		return false;
	}
}
/**
 * 是否为一个合法的ip地址
 * @param string $ip
 * @return boolean
 */
function is_ip($ip){
	if (ip2long($ip)) {
		return true;
	} else {
		return false;
	}
}
/**
 * 是否为整数
 * @param int $number
 * @return boolean
 */
function is_number($number){
	if(preg_match('/^[-\+]?\d+$/',$number)){
		return true;
	}else{
		return false;
	}
}
/**
 * 是否为正整数
 * @param int $number
 * @return boolean
 */
function is_positive_number($number){
	if(ctype_digit ($number)){
		return true;
	}else{
		return false;
	}
}
/**
 * 是否为小数
 * @param float $number
 * @return boolean
 */
function is_decimal($number){
	if(preg_match('/^[-\+]?\d+(\.\d+)?$/',$number)){
		return true;
	}else{
		return false;
	}
}
/**
 * 是否为正小数
 * @param float $number
 * @return boolean
 */
function is_positive_decimal($number){
	if(preg_match('/^\d+(\.\d+)?$/',$number)){
		return true;
	}else{
		return false;
	}
}
/**
 * 是否为英文
 * @param string $str
 * @return boolean
 */
function is_english($str){
	if(ctype_alpha($str))
		return true;
	else
		return false;
}
/**
 * 是否为中文
 * @param string $str
 * @return boolean
 */
function is_chinese($str){
	if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$str))
		return true;
	else 
		return false;
}
/**
 * 判断是否为图片
 * @param string $file	图片文件路径
 * @return boolean
 */
function is_image($file){
	if(file_exists($file)&&getimagesize($file===false)){
		return false;
	}else{
		return true;
	}
}
/**
 * 是否为合法的身份证(支持15位和18位)
 * @param string $card
 * @return boolean
 */
function is_card($card){
	if(preg_match('/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/',$card)||preg_match('/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/',$card))
		return true;
	else 
		return false;
}
/**
 * 验证日期格式是否正确
 * @param string $date
 * @param string $format
 * @return boolean
 */
function is_date($date,$format='Y-m-d'){
	$t=date_parse_from_format($format,$date);
	if(empty($t['errors'])){
		return true;
	}else{
		return false;
	}
}

//保留两位小数 只舍不入
function round_num($num){
	
	if($len = strpos($num,'.')){
		
		$dian_num = substr($num,$len+1,$len+3);//获取小数点后面的数字
		
		if(strlen($dian_num) >= 2){//判断小数点后面的数字长度是否大于2
		
		     $new_num = substr($num,0,$len+3);
		
		}else{ 
		
	    	$new_num = $num.'0';
		
		}
		
	}else{
	
	    $new_num = $num.'.00';
	
	}
	
	return $new_num;

}
function xiaoliang($num){
	if($num>10000){
		return round($num/10000,2) ."万";
	}
	return $num; 

}
//时间转换
function d($time){
	if($time){
		return date("Y-m-d H:i:s",$time);
	}else{
		return '--';
	}
	
}
//时间转换
function day($time){
	if($time){
		return date("Y-m-d",$time);
	}else{
		return '--';
	}
	
}
//地区名称
function get_region_info($id){
	if($id == 0){
		return '--';
	}
    return Db::name("region")->where("region_id=".$id)->value("region_name");
 }
 //获得城市
function get_city($id){
	 $list = Db::name('region')->where(array("region_type"=>2,'parent_id'=>$id))->field('region_id, region_name')->select();
	 $arr= array();
	  foreach($list as $k=>$v){
	  	$arr[$k]['value']=$v['region_id'];
	  	$arr[$k]['text']= $v['region_name'];
	  	$arr[$k]['children']= get_district($v['region_id']);
	  }
	 return  $arr;
 }
 //获得县区
 function get_district($id){
	 $list = Db::name('region')->where(array("region_type"=>3,'parent_id'=>$id))->field('region_id, region_name')->select();
	 $arr = array();
	  foreach($list as $k=>$v){
	  	$arr[$k]['value']=$v['region_id'];
	  	$arr[$k]['text']= $v['region_name'];
	  }
	 return  $arr;
 }

