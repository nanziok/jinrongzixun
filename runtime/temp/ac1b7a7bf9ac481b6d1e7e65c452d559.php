<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"F:\Work space\jinrongzixun\public_html/../application/admin\view\data\table_list.html";i:1570689487;}*/ ?>
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
	 <button class="layui-btn layui-btn-sm" lay-event="getCheckData">立即备份</button>
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
		toolbar:'#search',
		defaultToolbar:false,
		url: "<?php echo url('wdl_table_list'); ?>", //数据接口
		cols: [[ //表头
		    {field: 'name', title: '表名'},
		    {field: 'engine', title: '引擎'},
		    {field: 'collation', title: '字符集'},
			{field: 'data_length', title: '大小'}
		]]
	  });
  }
  jiazai();
  table.on('toolbar(test)', function(obj){
	  var desc = $("#city").html();
	  switch(obj.event){
		case 'getCheckData':
		beifen();
		break;
	  };
  });

});

function beifen(){
	 layer.load(0, {shade: [0.1,'#fff']});
	  $.ajax({
            url: 'wdl_backup_do',
            success: function (txt) {
            	layer.msg(txt.msg);
				layer.closeAll();

            }
        })
}
</script>
</body>
</html>