<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"/home/wwwroot/admin_com/public_html/../application/admin/view/user/rank.html";i:1570975870;}*/ ?>
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
<style>
.layui-table-tool-temp{padding-right:0px!important;}
</style>

<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit_id">编辑</a>
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="search">
  <div class="layui-btn-container">
    <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加会员等级</button>
  </div>
</script>
<script>
var jiazai;
var table;
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
		page:false,
		toolbar:'#search',
		defaultToolbar:false,
		url: "<?php echo url('wdl_rank'); ?>", //数据接口
		cols: [[ //表头
		  {field: 'rank_name', title: '等级名称'},
		  {field: 'count', title: '会员数量'},
		  {fixed: 'right', title:'操作',width:200, align:'center', toolbar: '#barDemo'}
		]],
	  });
  }
  jiazai();
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'edit_id':
		open_window(obj.data.rank_id,"wdl_rank_edit",'编辑会员等级','30%');
		break;
		case 'del':
		del(obj.data.rank_id,"wdl_rank_del");
		break;
	  };
  });
   table.on('toolbar(test)', function(obj){
	  switch(obj.event){
		case 'getCheckData':
		open_window(0,"wdl_rank_add",'添加会员等级','30%');
		break;
	  };
  });

});
</script>
</body>
</html>