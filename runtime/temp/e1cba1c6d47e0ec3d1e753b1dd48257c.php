<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"F:\Work space\jinrongzixun\public_html/../application/admin\view\service\kyc_list.html";i:1619172926;}*/ ?>
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
.layui-table-cell{height: 60px;line-height: 60px;}
</style>
<div class="layui-form layui-border-box">
	<div class="layui-table-tool">
			问卷名称： <div class="layui-inline"> <input class="layui-input" id="name" placeholder="搜索问卷名称"/></div>
			&nbsp;&nbsp;咨询师名称： <div class="layui-inline"> <input class="layui-input" id="tech_name" placeholder="搜索咨询师名称"/></div>
			 &nbsp;&nbsp;状态：<div class="layui-inline">
		 <select id="status">
		   <option value="0">请选择</option>
			   <option value="1">显示</option>
			   <option value="2">隐藏</option>
          </select>
			  </div> 
			 <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button> 
			 <button class="layui-btn layui-btn-sm layui-right" onclick="openurl('<?php echo url('wdl_add_kyc'); ?>');">添加问卷</button>
	</div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit_id">编辑</a>
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="switchTpl">
  <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="显示|隐藏" lay-filter="sexDemo" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="img">
  <img src="{{d.img}}" onclick="showImg({{d.id}},0,'service','id','img')" style='height:50px;cursor:pointer;'>
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
		limit:10,
		defaultToolbar:false,
		url: "<?php echo url('wdl_kyc_list'); ?>", //数据接口
		cols: [[ //表头
		  {field: 'id', title: 'ID',width:120,align:'center'},
		  {field: 'name', title: '名称',align:'center'},
		  {field: 'service_name', title: '关联咨询师',align:'center'},
		  {field:'status', title:'状态', templet: '#switchTpl', unresize: true,align:'center'},
		  {field: 'weigh', title: '排序', align:'center'},
		  {fixed: 'right', title:'操作', align:'center', toolbar: '#barDemo'}
		]],
		id:'demo'
	  });
  }
  jiazai();
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'edit_id':
		openurl("<?php echo url('wdl_edit_kyc'); ?>?id="+obj.data.id);
		break;
		case 'del':
		del(obj.data.id,"wdl_del_kyc");
		break;
	  };
  });

   form.on('switch(sexDemo)', function(obj){
      var id = this.value;
	  var value = obj.elem.checked;
	  if(value == true){
	    status = 1;
	  }else{
	    status = 2;
	  } 
	  var arr = {};
	  arr.id = id;
	  arr.status = status;
	  arr.table = 'service_test';
	  set_data(arr);
  });

});
  function chongzai(){
		table.reload('demo', {
		  where: { 
			status: $("#status").val(),
			name:$("#name").val(),
			  tech_name: $('#tech_name').val()
		  }
		  ,page: {
			curr: 1 
		  }
	   })
  
  }
</script>
</body>
</html>