<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"F:\Work space\test\public_html/../application/admin\view\goods\wdl_cate_add.html";i:1618983412;}*/ ?>
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
.cate-image-preview {
  width: 92px;
  height: 92px;
  margin: 0 10px 10px 0;
  background-image: url("/static/manage/images/add_fpic.gif");
  background-size: cover;
}
</style>
<form class="layui-form" id="form" method="post">
  <div class="layui-form-item">
    <label class="layui-form-label">分类名称</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="name" placeholder="请输入文章分类名称" value="" class="layui-input must">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">上级分类</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <select name="pid">
        <option value="0">作为顶级分类</option>
        <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): $i = 0; $__LIST__ = $cat_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
        <?php endforeach; endif; else: echo "" ;endif; ?>
      </select>
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">图标</label>
    <div class="layui-input-block">
      <input type="text" name="icon" placeholder="请输入分类图标" value="" class="layui-input layui-hide">
      <img class="cate-image-preview"/>
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
  layui.use(['form','upload'], function(){
    var form = layui.form, upload=layui.upload;
    submit_form();

    var uploadInst = upload.render({
      elem: '.cate-image-preview' //绑定元素
      ,url: "<?php echo url('admin/upload/up_do'); ?>"
      ,done: function(res){
        $(".cate-image-preview").attr("src",res.pic);
        $("input[name='icon']").val(res.pic);
      }
      ,error: function(){
        //请求异常回调
      }
    });
  })
</script>
</body>
</html>