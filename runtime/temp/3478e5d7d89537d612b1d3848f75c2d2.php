<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"/home/wwwroot/admin_com/public_html/../application/admin/view/guanliyuan/index.html";i:1570803908;}*/ ?>
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
	  <div class="layui-table-tool-temp"> 
		 <div class="demoTable"> 
			 搜索登录账号： <div class="layui-inline"> <input class="layui-input" id="name" placeholder="请输入" autocomplete="off"> </div> 
			 <div class="layui-inline">
			  <select id="user_cat">
			     <option value="0">请选择角色</option> 
				 <?php if(is_array($cat) || $cat instanceof \think\Collection || $cat instanceof \think\Paginator): $i = 0; $__LIST__ = $cat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				  <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
				 <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
			  </div> 
			 <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button> 
			 <button class="layui-btn layui-btn-sm layui-right" onclick="add();">添加管理员</button> 
		</div> 
	 </div>
	</div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit_id">编辑</a>
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="switchTpl">
  <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="sexDemo" {{ d.status == 1 ? 'checked' : '' }}>
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
		  {field: 'user_cat', title: '角色',align:'center'},
		  {field: 'name', title: '登录账号',align:'center'},
		  {field: 'username', title: '姓名',align:'center'},
		  {field: 'phone', title: '手机号',align:'center'},
		  {field: 'add_time', title: '添加时间',align:'center'},
		  {field:'status', title:'状态', width:120, templet: '#switchTpl', unresize: true,align:'center'},
		  {fixed: 'right', title:'操作',width:200, align:'center', toolbar: '#barDemo'}
		]],
		id:'demo'
	  });
  }
  jiazai();
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'edit_id':
		open_window(obj.data.id,"wdl_edit",'编辑管理员','50%');
		break;
		case 'del':
		del(obj.data.id,"wdl_del");
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
	  arr.table = 'user_admin';
	  set_data(arr);
  });

});
  function add(){
    open_window(0,"wdl_add",'添加管理员','50%');
  }
  function chongzai(){
	    var user_cat = $("#user_cat").val();
		var name = $("#name").val();
		table.reload('demo', {
		  where: { 
			user_cat: user_cat,
			name:name
		  }
		  ,page: {
			curr: 1 
		  }
	   })
  
  }
</script>
</body>
</html>