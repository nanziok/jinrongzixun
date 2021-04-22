<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"F:\Work space\test\public_html/../application/admin\view\service\service_add.html";i:1617678864;}*/ ?>
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
.layui-form{margin: 0 auto;width: 100%;margin-top: 10px;}
.layui-form-pane .layui-input-block{width:50%;}
#ac{display:none;}
#ac img{height:100px;margin-left:20px;}
.delete{background: #666;color: #fff;height: 30px;line-height: 30px;width: 100px;text-align: center;margin-top: 10px;cursor:pointer;}
.layui-form-item{margin-left: 15px;}
</style>
<div class="layui-form layui-border-box">
	<div class="layui-table-tool">服务点添加</div>
</div>
<form class="layui-form layui-form-pane" action="<?php echo url('wdl_index_post'); ?>" id="form" >
  <div class="layui-form-item">
    <label class="layui-form-label">门店名称</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="name"  placeholder="请输入门店名称" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">门店编号</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="code"  placeholder="请输入门店编号" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">门店电话</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="phone"  placeholder="请输入门店电话" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">门店地址</label>
    <div class="layui-input-block">
      <textarea name="address" placeholder="请输入门店地址" class="layui-textarea must"></textarea>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">排序</label>
    <div class="layui-input-block">
      <input type="number" name="listorder" value="50" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">缩略图</label>
     <button type="button" class="layui-btn" id="addimg">
	  <i class="layui-icon">&#xe67c;</i>上传图片
	</button>
  </div>
   <div class="layui-form-item" id="ac">
    <label class="layui-form-label">缩略图</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <img class="img"/>
	  <input id="img" name="img" type="hidden"/>
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
   var b=$("#img").val();
   $("#img").val('');
   del_img(b);
})
layui.use('form', function(){
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
				setTimeout(function(){
				 window.location.href = window.location.href;
				},2000)
			}else{
				layer.alert(txt.msg,{icon: 2,title:'提示'});
            	return false;
			}
		});
	})

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