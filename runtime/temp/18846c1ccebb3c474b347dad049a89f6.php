<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"F:\Work space\test\public_html/../application/admin\view\article\wdl_edit.html";i:1570946885;}*/ ?>
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
<script type="text/javascript" src="/static/manage/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/manage/ueditor/ueditor.all.js"></script>
<style>
.layui-form{margin: 0 auto;width: 100%;}
.layui-form .layui-input-block{width:20%;}
</style>
<div class="layui-form layui-border-box">
	<div class="layui-table-tool">
		编辑文章
		<button class="layui-btn layui-btn-sm layui-right" onclick="window.history.back(-1);">返回文章列表</button> 
	</div>
</div>
<form class="layui-form" action="<?php echo url('wdl_add_do'); ?>" id="form" style="margin-top:10px;width:90%;">
  <div class="layui-form-item">
    <label class="layui-form-label">文章分类：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <select name="cat_id" class="must" msg="请选择文章分类">
        <option value="0">请选择</option>
        <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): $i = 0; $__LIST__ = $cat_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	      <option value="<?php echo $vo['id']; ?>"  <?php if($data['cat_id'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
	    <?php endforeach; endif; else: echo "" ;endif; ?>
      </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">文章标题：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="title"  placeholder="请输入文章标题" value="<?php echo $data['title']; ?>" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">文章内容：</label>
    <div class="layui-input-block" style="width:50%;z-index:0;">
      <textarea name="content" id="container" placeholder="请输入内容" style="min-height:300px;"><?php echo $data['content']; ?></textarea>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">排序：</label>
    <div class="layui-input-block">
      <input type="number" name="listorder" value="<?php echo $data['listorder']; ?>" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">是否显示：</label>
    <div class="layui-input-block">
      <input type="checkbox" <?php if($data['is_show'] == 1): ?>checked=""<?php endif; ?> name="is_show" lay-skin="switch" lay-filter="switchTest" lay-text="显示|隐藏">
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
    submit_form_back();
   var ue = UE.getEditor('container', {
    autoHeightEnabled: true,
    autoFloatEnabled: true,
});



})

</script>
</body>
</html>