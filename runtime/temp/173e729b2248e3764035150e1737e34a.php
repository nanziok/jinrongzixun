<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"F:\Work space\test\public_html/../application/admin\view\guanggao\cat_edit.html";i:1616657833;}*/ ?>
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
.layui-input-block{width:50%;}
</style>
<form class="layui-form" action="<?php echo url('wdl_cat_edit_do'); ?>" id="form" >
  <input type="hidden" name="id" value="<?php echo $data['id']; ?>")>
  <div class="layui-form-item">
    <label class="layui-form-label">位置名称</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="name" placeholder="请输入广告位置名称" value="<?php echo $data['name']; ?>" class="layui-input must">
    </div>
  </div>
   <div class="layui-form-item">
    <label class="layui-form-label">备注</label>
    <div class="layui-input-block">
      <textarea name="desc" placeholder="请输入内容" class="layui-textarea"><?php echo $data['desc']; ?></textarea>
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