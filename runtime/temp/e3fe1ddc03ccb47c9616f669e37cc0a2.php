<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"F:\Work space\test\public_html/../application/admin\view\xitong\region_list.html";i:1616586536;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body>
<link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" />
<script type="text/javascript" src="/static/manage/layui/layui.js"></script>
<link rel="stylesheet" type="text/css" href="/static/manage/css/style.css" />
<script type="text/javascript" src="/static/manage/js/public.js"></script>
<script type="text/javascript" src="/static/manage/js/jquery-1.8.1.min.js"></script>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="search">
  <div class="layui-btn-container">
     <?php if($region_type == 1): ?>
	 <button class="layui-btn layui-btn-sm" lay-event="getCheckData" id="city">添加省份</button>
	<?php elseif($region_type == 2): ?>
	<button class="layui-btn layui-btn-sm" lay-event="back">返回上一步</button>
	<button class="layui-btn layui-btn-sm" lay-event="getCheckData" id="city">添加城市</button>
    <?php else: ?>
	<button class="layui-btn layui-btn-sm" lay-event="back">返回上一步</button>
	<button class="layui-btn layui-btn-sm" lay-event="getCheckData" id="city">添加县区</button>
	<?php endif; ?>
  </div>
</script>
<script type="text/html" id="barDemo">
 <?php if($region_type != 3): ?>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="detail">地区管理</a>
  <?php endif; ?>
  <a class="layui-btn  layui-btn-xs" lay-event="edit_id">编辑</a>
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
var jiazai;
var table;
var parent_id = <?php echo $parent_id; ?>;
var region_type = <?php echo $region_type; ?>;
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;
});
layui.use('table', function(){
  table = layui.table;
  var form = layui.form;
  
  //第一个实例
 jiazai = function(){
	 table.render({
		elem: '#demo',
		hide:false,
		toolbar:'#search',
		defaultToolbar:false,
		url: "<?php echo url('wdl_region_index'); ?>?parent_id="+parent_id, //数据接口
		cols: [[ //表头
		  {field: 'region_id', title: '地区ID',width:100},
		  {field: 'region_name', title: '地区名'},
		  {field: 'str', title: '地区首字母'},
		  {fixed: 'right', title:'操作',width:200, align:'center', toolbar: '#barDemo'}
		]]
	  });
  }
  jiazai();
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'edit_id':
		open_window(obj.data.region_id,"wdl_region_edit",'编辑地区名称','30%');
		break;
		case 'del':
		del(obj.data.region_id,"wdl_region_del");
		break;
		case 'detail':
		openurl("<?php echo url('region_list'); ?>?parent_id="+obj.data.region_id);
		break;		
	  };
  });
  table.on('toolbar(test)', function(obj){
	  var desc = $("#city").html();
	  switch(obj.event){
		case 'getCheckData':
		open_window(parent_id,"wdl_region_add",desc);
		break;
		case 'back':
		window.history.back(-1); 
		break;
	  };
  });

});
</script>
</body>
</html>