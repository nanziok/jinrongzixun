<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"F:\Work space\jinrongzixun\public_html/../application/admin\view\xitong\caidan_add.html";i:1570782746;}*/ ?>
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
<form class="layui-form layui-form-pane" action="<?php echo url('wdl_caidan_add_do'); ?>" id="form" >
  <div class="layui-form-item">
    <label class="layui-form-label">所属菜单</label>
    <div class="layui-input-block">
      <select name="fid">
        <option value="0">顶级菜单</option>
        <?php if(is_array($cat) || $cat instanceof \think\Collection || $cat instanceof \think\Paginator): $i = 0; $__LIST__ = $cat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	      <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
	    <?php endforeach; endif; else: echo "" ;endif; ?>
      </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">菜单名称</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="name"  placeholder="请输入菜单名称" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">控制器</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="m"  placeholder="请输入菜单控制器" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">方法</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="a" placeholder="请输入菜单方法" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">排序</label>
    <div class="layui-input-block">
      <input type="number" name="listorder" value="50" placeholder="请输入排序" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">图标</label>
    <div class="layui-input-block">
      <input type="text" name="icon"  placeholder="请输入图标" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">是否显示</label>
    <div class="layui-input-block">
      <input type="checkbox" checked="" name="is_show" lay-skin="switch" lay-filter="switchTest" lay-text="显示|隐藏">
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