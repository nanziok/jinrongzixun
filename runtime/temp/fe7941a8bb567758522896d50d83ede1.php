<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"F:\Work space\jinrongzixun\public_html/../application/admin\view\tousu\index.html";i:1617680376;}*/ ?>
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
.layui-table tr{height:60px!important;}
.layui-table-cell{height:60px!important;line-height:60px!important;}
</style>
<div class="layui-form layui-border-box">
	<div class="layui-table-tool">
			 处理状态：<div class="layui-inline">
			   <select id="status">
			     <option value="0" <?php echo $status==0?'selected="selected"':''; ?>>全部</option> 
				   <option value="1" <?php echo $status==1?'selected="selected"':''; ?>>待处理</option> 
			     <option value="2" <?php echo $status==2?'selected="selected"':''; ?>>已完成</option> 
         </select>
			  </div> 
			 <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button> 
		
	</div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit_id">点击操作</a>
</script>
<script type="text/html" id="img">
	     {{#  if(d.img != ''){ }}
	      {{# layui.each(d.img, function(index, item){ }}
				<img src="{{item}}"/ style="width: 100%;height: auto;">
				{{# }); }}
     {{# }else{ }}
     --
    {{#  } }}
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
		page:true,
		limit:20,
		defaultToolbar:false,
		url: "<?php echo url('wdl_index'); ?>?status=<?php echo $status; ?>", //数据接口
		cols: [[ //表头
		  {field: 'id', title: '投诉ID',width:150,align:'center'},
		  {field: 'userphone', title: '注册电话',width:150,align:'center'},
		  {field: 'phone', title: '预留电话',width:150,align:'center'},
		  {field: 'content', title: '投诉内容',align:'center'},
		  {field: 'img', title: '相关图片',width:200,templet: '#img', unresize: true,align:'center'},
		  {field: 'add_time', title: '添加时间',width:200,align:'center'},
		  {field: 'status', title: '状态',width:150,align:'center'},
		  {field: 'mark', title: '处理备注',align:'center'},
		  {fixed: 'right', title:'操作',width:200, align:'center', toolbar: '#barDemo'}
		]],
		id:'demo'
	  });
  }
  jiazai();
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'edit_id':
		open_window(obj.data.id,"wdl_edit",'处理投诉','40%');
		break;
	  };
  });
});

  function chongzai(){
		var status = $("#status").val();
		table.reload('demo', {
		  where: { 
			status: status
		  }
		  ,page: {
			curr: 1 
		  }
	   })
  
  }
</script>
</body>
</html>