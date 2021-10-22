<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"F:\Work space\jinrongzixun\public_html/../application/admin\view\guanggao\wdl_edit.html";i:1620198394;}*/ ?>
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
#ac img{height:100px;margin-left:20px;}
.delete{background: #666;color: #fff;height: 30px;line-height: 30px;width: 100px;text-align: center;margin-top: 10px;cursor:pointer;}
</style>
<form class="layui-form layui-form-pane" action="<?php echo url('wdl_index_post'); ?>" id="form" >
  <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
  <div class="layui-form-item">
    <label class="layui-form-label">广告位置</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <select name="cat_id" class="must" msg="请选择广告位置">
        <option value="0">请选择</option>
        <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): $i = 0; $__LIST__ = $cat_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	      <option value="<?php echo $vo['id']; ?>" <?php if($vo['id'] == $data['cat_id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
	    <?php endforeach; endif; else: echo "" ;endif; ?>
      </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">广告标题</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="title"  placeholder="请输入广告标题" value="<?php echo $data['title']; ?>" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">广告类型</label>
    <div class="layui-input-block">
      <select name="type" >
        <option value="0">请选择</option>
        <option value="1" <?php if($data['type'] == 1): ?>selected<?php endif; ?>>菜单链接</option>
		    <option value="2" <?php if($data['type'] == 2): ?>selected<?php endif; ?>>文章</option>
		    <option value="3" <?php if($data['type'] == 3): ?>selected<?php endif; ?>>咨询师</option>
		    <option value="9" <?php if($data['type'] == 9): ?>selected<?php endif; ?>>外链</option>
      </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">类型参数</label>
    <div class="layui-input-block">
      <input type="text" name="url" value="<?php echo $data['url']; ?>"  placeholder="请输入广告类型参数" class="layui-input">
    </div>
  </div>
  <p class="red" style="margin-bottom:15px;">温馨提示：<br>选择菜单链接，需要技术指导。选择文章，需要输入案例ID。<br>
    选择咨询师，需要输入咨询师ID。选择外链，需要输入http开头的标准链接地址。</p>
  <div class="layui-form-item">
    <label class="layui-form-label">排序</label>
    <div class="layui-input-block">
      <input type="number" name="listorder" value="<?php echo $data['listorder']; ?>"  class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">上传广告图</label>
     <button type="button" class="layui-btn" id="addimg">
	  <i class="layui-icon">&#xe67c;</i>上传图片
	</button>
  </div>
  <div class="layui-form-item" <?php if(empty($data['img'])): ?>style="display:none;"<?php endif; ?>  id="ac">
    <label class="layui-form-label">图片</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <img class="img"  <?php if($data['img']): ?>src="<?php echo $data['img']; ?>"<?php endif; ?>/>
	  <input id="img" name="img" type="hidden"  <?php if($data['img']): ?>value="<?php echo $data['img']; ?>"<?php endif; ?>/>
      <p class="delete">移除</p>
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
$(".delete").on('click',function(){
   $("#ac").hide();
   $(".img").attr("src","");
   $("#img").val('');
})
layui.use('form', function(){
  var form = layui.form;
  submit_form();
})
layui.use('upload', function(){
  var upload = layui.upload;
   
  //执行实例
  var uploadInst = upload.render({
    elem: '#addimg' //绑定元素
    ,url: "<?php echo url('admin/upload/up_do'); ?>" //上传接口
    ,done: function(ret){
       $("#ac").show();
       $(".img").attr('src',ret.pic);
	   $("#img").val(ret.pic);
    }
    ,error: function(){
       layer.msg('上传有误');
    }
  });
});
</script>
</body>
</html>