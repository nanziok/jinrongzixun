<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"F:\Work space\jinrongzixun\public_html/../application/admin\view\article\index_list.html";i:1619519387;}*/ ?>
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
<div class="layui-form layui-border-box">
	<div class="layui-table-tool">
			 文章分类：<div class="layui-inline">
			  <select id="cat_id">
			     <option value="0">请选择</option> 
				 <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): $i = 0; $__LIST__ = $cat_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				  <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
				 <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
			  </div> 
			 &nbsp;&nbsp;显示状态：<div class="layui-inline">
			  <select id="is_show">
			     <option value="0">全部</option> 
				 <option value="1">显示</option> 
			     <option value="2">隐藏</option> 
              </select>
			  </div> 
			 <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button> 
			 <button class="layui-btn layui-btn-sm layui-right" onclick="add();">添加文章</button> 
		
	</div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit_id">编辑</a>
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="switchTpl">
  <input type="checkbox" name="is_show" value="{{d.id}}" lay-skin="switch" lay-text="显示|隐藏" lay-filter="sexDemo" {{ d.is_show == 1 ? 'checked' : '' }}>
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
		url: "<?php echo url('wdl_index'); ?>", //数据接口
		cols: [[ //表头
		  {field: 'id', title: '文章ID',align:'center'},
		  {field: 'name', title: '文章分类',align:'center'},
		  {field: 'title', title: '文章标题',align:'center'},
		  {field:'is_show', title:'状态', width:120, templet: '#switchTpl', unresize: true,align:'center'},
		  {field: 'listorder', title: '排序',align:'center'},
		  {field: 'add_time', title: '添加时间',align:'center'},
		  {fixed: 'right', title:'操作',width:200, align:'center', toolbar: '#barDemo'}
		]],
		id:'demo'
	  });
  }
  jiazai();
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'edit_id':
		openurl("<?php echo url('wdl_edit'); ?>?id="+obj.data.id);
		break;
		case 'del':
		del(obj.data.id,"wdl_del");
		break;
	  };
  });

   form.on('switch(sexDemo)', function(obj){
      var id = this.data();
	  var value = obj.elem.checked;
	  if(value == true){
	    is_show = 1;
	  }else{
	    is_show = 2;
	  } 
	  var arr = {};
	  arr.id = id;
	  arr.is_show = is_show;
	  arr.table = 'article';
	  set_data(arr);
  });

});
  function add(){
	openurl("<?php echo url('wdl_add'); ?>");
  }
  function chongzai(){
	    var cat_id = $("#cat_id").val();
		var is_show = $("#is_show").val();
		table.reload('demo', {
		  where: { 
			cat_id: cat_id,
			is_show:is_show
		  }
		  ,page: {
			curr: 1 
		  }
	   })
  
  }
</script>
</body>
</html>