<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"F:\Work space\test\public_html/../application/admin\view\xitong\index.html";i:1616725181;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body>
<link rel="stylesheet" type="text/css" href="/static/manage/css/style.css" />
<script type="text/javascript" src="/static/manage/js/jquery-1.8.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" />
<script type="text/javascript" src="/static/manage/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/public.js"></script>
<script type="text/javascript" src="/static/manage/js/jquery.form.js"></script>

<style>
.layui-form{margin: 0 auto;width: 98%;margin-top: 10px;}
.layui-form-pane .layui-input-block{margin-left:160px;width:30%;}
.layui-form-pane .layui-form-label {width:160px;}
.require-field{margin-right:-260px;}
</style>
<form class="layui-form  layui-form-pane" action="<?php echo url("wdl_edit_do"); ?>" method="post" id="form">
	<input name="id" type="hidden" value="<?php echo $data['id']; ?>" />
    <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
	  <ul class="layui-tab-title">
		<li class="layui-this">网站设置</li>
		<li>版本设置</li>
		<li>短信设置</li>
	  </ul>
	  <div class="layui-tab-content" style="height: 100px;">
		<div class="layui-tab-item layui-show">
		     <div class="layui-form-item">
			    <label class="layui-form-label">网站名称</label>
			    <div class="layui-input-block">
			      <input type="text" name="name" value="<?php echo $data['name']; ?>" placeholder="请输入网站名称" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">客服电话</label>
			    <div class="layui-input-block">
			      <input type="text" name="phone" value="<?php echo $data['phone']; ?>" placeholder="请输入网站名称" class="layui-input">
			    </div>
			 </div>
			  <div class="layui-form-item">
			    <label class="layui-form-label">系统版权</label>
			    <div class="layui-input-block">
			      <input type="text" name="banquan" value="<?php echo $data['banquan']; ?>" placeholder="请输入系统版权" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
				<label class="layui-form-label">app运行状态</label>
				<div class="layui-input-block">
				  <input type="checkbox" <?php if($data['app'] == 1): ?>checked=""<?php endif; ?> name="app" lay-skin="switch" lay-filter="switchTest" lay-text="运行|关闭">
				  <span class="require-field">此按钮关闭会禁用所有接口使用</span>
				</div>
			  </div>
			 
		      <div class="layui-form-item">
			    <label class="layui-form-label">app关闭原因</label>
			    <div class="layui-input-block">
			      <input type="text" name="app_desc" value="<?php echo $data['app_desc']; ?>" placeholder="请输入app关闭原因" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
				<label class="layui-form-label">注册状态</label>
				<div class="layui-input-block">
				  <input type="checkbox" <?php if($data['reg'] == 1): ?>checked=""<?php endif; ?> name="reg" lay-skin="switch" lay-filter="switchTest" lay-text="开启|关闭">
				  <span class="require-field">此按钮关闭会员注册渠道</span>
				</div>
			  </div>
		</div>
		<div class="layui-tab-item">
		     <div class="layui-form-item">
			    <label class="layui-form-label">android版本号：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="android" value="<?php echo $data['android']; ?>" placeholder="请输入android版本号" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">android下载地址：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="android_url" value="<?php echo $data['android_url']; ?>" placeholder="请输入android下载地址" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">ios版本号：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="ios" value="<?php echo $data['ios']; ?>" placeholder="请输入ios版本号" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">ios上架版本号：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="ios1" value="<?php echo $data['ios1']; ?>" placeholder="请输入ios上架版本号" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">ios下载地址：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="ios_url" value="<?php echo $data['ios_url']; ?>" placeholder="请输入ios下载地址" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">更新提示语：</label>
			    <div class="layui-input-block">
			      <input type="text" name="gengxin_desc" value="<?php echo $data['gengxin_desc']; ?>" placeholder="请输入更新提示语" class="layui-input">
			    </div>
			  </div>
		</div>

		<div class="layui-tab-item">
		     <div class="layui-form-item">
			    <label class="layui-form-label">互亿无线账号：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="msg_zhanghao" value="<?php echo $data['msg_zhanghao']; ?>" placeholder="请输入互亿无线账号" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">互亿无线秘钥：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="msg_secret" value="<?php echo $data['msg_secret']; ?>" placeholder="请输入互亿无线秘钥" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">发送验证码模板：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="msg_code" value="<?php echo $data['msg_code']; ?>" placeholder="请输入发送验证码模板" class="layui-input">
			    </div>
			 </div>
		</div>
	  </div>
	</div> 
    <div class="layui-form-item" style="padding-top:30px;">
		<div class="layui-input-block">
		  <button type="button" class="layui-btn"  lay-filter="formDemo" id="form_btn">立即提交</button>
		  <button type="reset" class="layui-btn layui-btn-primary">重置</button>
		</div>
  </div>
</form>
<script>
layui.use(['element','form'], function(){
  var $ = layui.jquery,element = layui.element;
  var form = layui.form;

	$("#form_btn").click(function(){
		var leg = $(".must").length;
    	for(var a=0;a<leg;a++){
    	  var val=$(".must").eq(a).val();
    	   if(val == "" || val == 0){
			 var msg = $(".must").eq(a).attr('msg');
			 if(!msg){
				 msg = $(".must").eq(a).attr('placeholder'); 
			 }
			layer.alert(msg,{icon: 2,title:'提示'});
			return false;
		  }
    	}
		$("#form").ajaxSubmit(function(txt){
			if(txt.code==1){
				layer.msg(txt.msg);
				reload();
			}else{
				layer.alert(txt.msg,{icon: 2,title:'提示'});
            	return false;
			}
		});
	})



});

</script>
</body>
</html>