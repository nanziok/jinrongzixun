<?php
use think\Db;
use think\Exception;



   //获取商品缩略图 
  function get_goods_img($id){
  
    $img = db('goods_img')->where('goods_id',$id)->order('add_time desc')->limit(1)->value('img');
    $newimg = empty($img)?"/uploads/default/img_default.png":$img;
    return $newimg;
  }
  //获取会员等级
  function get_rankname($id){
   $rank_name = Db::name('user_rank')->where("rank_id=".$id)->value('rank_name');
   return empty($rank_name)?"--":$rank_name;
  }
  //获取性别
  function get_sex($id){
    switch($id){
	   case 0:
	   return '--';
	   break;
	   case 1:
	   return '男';
	   break;
	   case 2:
	   return '女';
	   break;
	
	}
  }
 
?>