<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"F:\Work space\jinrongzixun\public_html/../application/admin\view\user\wdl_add.html";i:1617437076;}*/ ?>
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
.layui-form{margin: 0 auto;width: 90%;margin-top: 10px;}
.layui-form-pane .layui-input-block{width:50%;}
</style>
<form class="layui-form layui-form-pane" action="<?php echo url('wdl_add_do'); ?>" id="form" >
  <div class="layui-form-item">
    <label class="layui-form-label">手机号</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="number" name="phone"  placeholder="请输入会员手机号" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">登录密码</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="password" name="password"  placeholder="请输入登录密码,至少6位数" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button type="button" class="layui-btn" id="form_btn">立即提交</button>
	  <button type="reset" class="layui-btn layui-btn-normal">重置</button>
    </div>
  </div>
</form>
<script>
layui.use('form', function(){
  var form = layui.form;
  submit_form();
})
</script>
</body>
</html>