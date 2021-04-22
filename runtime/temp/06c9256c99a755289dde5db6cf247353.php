<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"F:\Work space\test\public_html/../application/admin\view\goods\cate.html";i:1619002423;}*/ ?>
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
.cate-icon{width: 38px;height: 38px;display: inline-block;margin: 0 auto;}
</style>

<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit_id">编辑</a>
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="search">
  <div class="layui-btn-container">
    <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加商品分类</button>
  </div>
</script>
<script type="text/html" id="cate-icon">
  <img src="{{  d.icon  }}" class="cate-icon" />
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
		url: "<?php echo url('wdl_cate'); ?>", //数据接口
        method: 'POST',
		cols: [[ //表头
		  {field: 'id', title: '商品分类ID', align:'center'},
		  {field: 'name', title: '分类名称', align:'left'},
		  {field: 'pid_text', title: '上级分类', align:'center'},
		  {field: 'icon', title: '图标', align:"center", templet:'#cate-icon',event:'showBigImg'},
		  {field: 'goods_count', title: '商品统计', align:'center'},
          {field: 'weigh', title: '排序', align:'center', edit:'text'},
		  {field: 'create_time', title: '添加时间', align:'center'},
          {fixed: 'right', title:'操作', width:200, align:'center', toolbar: '#barDemo'}
		]]
	  });
  }
  jiazai();
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'edit_id':
		open_window(obj.data.id,"wdl_cate_edit",'编辑文章分类','40%');
		break;
		case 'del':
		del(obj.data.id,"wdl_cate_del");
		break;
		case 'showBigImg':
          showBigImg(obj.data.icon);
	  };
  });
   table.on('toolbar(test)', function(obj){
	  switch(obj.event){
		case 'getCheckData':
		open_window(0,"wdl_cate_add",'添加文章分类','40%');
		break;
	  };
  });
  table.on('edit(test)', function(obj){
    console.log("单元格编辑",obj.data);
    var temp_data = {};
    temp_data["table"] = 'goods_category';
    temp_data["id"] = obj.data.id;
    temp_data[obj.field] = obj.value;
    set_data(temp_data, obj.value);
  });

});
  var showBigImg = function(src,name="",title=""){
    layer.open({
      type: 1,
      title: false,
      closeBtn: 0,
      shadeClose: true,
      content: "<img alt=" + name + " title=" + name + " src=" + src + " />"
    })
  }
</script>
</body>
</html>