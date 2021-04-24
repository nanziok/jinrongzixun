<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"F:\Work space\jinrongzixun\public_html/../application/admin\view\xitong\caidan_index.html";i:1570782739;}*/ ?>
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
    <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加菜单</button>
  </div>
</script>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit_id">编辑</a>
  <a class="layui-btn layui-btn-primary  layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="switchTpl">
  <input type="checkbox" name="sex" value="{{d.id}}" lay-skin="switch" lay-text="显示|隐藏" lay-filter="sexDemo" {{ d.is_show == 1 ? 'checked' : '' }}>
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
		url: "<?php echo url('wdl_caidan_index'); ?>", //数据接口
		cols: [[ //表头
		  {field: 'name', title: '菜单名'},
		  {field: 'm', title: '控制器', width:200,align:'center'},
		  {field: 'a', title: '方法', width:200,align:'center'},
		  {field: 'listorder', title: '排序', width:200,align:'center',edit:'text'},
          {field:'is_show', title:'是否显示', width:120, templet: '#switchTpl', unresize: true,align:'center'},
		  {fixed: 'right', title:'操作',width:200, align:'center', toolbar: '#barDemo'}
		]]
	  });
  }
  jiazai();
  form.on('switch(sexDemo)', function(obj){
      var id = this.value;
	  var value = obj.elem.checked;
	  if(value == true){
	    is_show = 1;
	  }else{
	    is_show = 0;
	  } 
	  var arr = {};
	  arr.id = id;
	  arr.is_show = is_show;
	  arr.table = 'user_admin_action';
	  set_data(arr);
  });
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'edit_id':
		open_window(obj.data.id,"wdl_caidan_edit",'编辑菜单','65%');
		break;
		case 'del':
		del(obj.data.id,"wdl_caidan_del");
		break;
		
	  };
  });
 table.on('toolbar(test)', function(obj){
	  switch(obj.event){
		case 'getCheckData':
		open_window(0,"wdl_caidan_add",'添加菜单','65%');
		break;
	  };
  });
 
   table.on('edit(test)', function(obj){
	    var id = obj.data.id;
        var listorder = obj.data.listorder;
		var arr = {};
		arr.id = id;
        arr.listorder = listorder;
		arr.table = 'user_admin_action';
		set_data(arr);
  });

});
</script>
</body>
</html>