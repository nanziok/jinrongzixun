<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"F:\Work space\test\public_html/../application/admin\view\user\fabu.html";i:1618477772;}*/ ?>
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
.layui-table tr{height:100px!important;}
.layui-table-cell{height:100px!important;line-height:100px!important;}
</style>
<div class="layui-form layui-border-box">
	<div class="layui-table-tool">
	  <div class="layui-table-tool-temp"> 
		 <div class="demoTable"> 
			 发布类型：<div class="layui-inline">
			     <select id="type">
			     <option value="0">请选择</option> 
			     <option value="1">图片</option>
			     <option value="2">视频</option>
			     <option value="3">纯文本</option>
          </select>
			  </div> 
			   &nbsp;&nbsp;状态：<div class="layui-inline">
			     <select id="type">
			     <option value="0">请选择</option> 
			     <option value="1">通过</option>
			     <option value="2">审核中</option>
          </select>
			  </div>
			   &nbsp;&nbsp;发布时间：<div class="layui-inline"> <input class="layui-input" id="start_date" value="<?php echo input('start_date'); ?>" placeholder="请输入"/></div>
			~~&nbsp;<div class="layui-inline"> <input class="layui-input" id="end_date" value="<?php echo input('end_date'); ?>" placeholder="请输入"/></div>
			 <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button> 
		</div> 
	 </div>
	</div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="view_comment">查看评论</a>
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="switchTpl">
  <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="通过|审核中" lay-filter="sexDemo" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="type_value">
	   {{#  if(d.type == 1){ }}
	      {{# layui.each(d.type_value, function(index, item){ }}
				<img src="{{item}}"/ style="width: 100%;height: auto;">
				{{# }); }}
     {{# }else if(d.type == 2){ }}
      <video src="{{d.video}}" controls="controls" style="width: 100%;height: 100%;"></video>
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
layui.use('laydate', function(){
  var laydate = layui.laydate;
  laydate.render({
    elem: '#start_date',
    type: 'datetime'//指定元素
  });
  laydate.render({
    elem: '#end_date',
    type:'datetime'//指定元素
  });
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
		url: "<?php echo url('wdl_fabu'); ?>?start_date=<?php echo input('start_date'); ?>&end_date=<?php echo input('end_date'); ?>", //数据接口
		cols: [[ //表头
		  {field: 'id', title: '发布ID',width:100,align:'center'},
		  {field: 'username', title: '发布者昵称',width:120,align:'center'},
		  {field: 'phone', title: '发布者手机号',width:120,align:'center'},
		  {field: 'type_desc', title: '发布类型',width:100,align:'center'},
		  {field: 'type_value', title: '类型内容',width:200,templet: '#type_value', unresize: true,align:'center'},
		  {field: 'content', title: '发布内容',align:'center'},
		  {field: 'add_time', title: '发布时间',width:200,align:'center'},
		  {field: 'zan', title: '点赞数',width:100,align:'center'},
		  {field: 'comment', title: '评论数',width:100,align:'center'},
		  {field:'status', title:'状态', width:120, templet: '#switchTpl', unresize: true,align:'center'},
		  {fixed: 'right', title:'操作', width:160,align:'center', toolbar: '#barDemo'}
		]],
		id:'demo'
	  });
  }
  jiazai();
  table.on('tool(test)', function(obj){
	  switch(obj.event){
		case 'del':
		del(obj.data.id,"wdl_fabu_del");
		break;
		case 'view_comment':
		open_window(obj.data.id,"wdl_fabu_comment",'查看评价['+obj.data.content+']','80%','80%');
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
	  arr.table = 'user_fabu';
	  set_data(arr);
  });

});

  function chongzai(){
		table.reload('demo', {
		  where: { 
		   type:$("#type").val(),
			 start_date:$("#start_date").val(),
			 end_date:$("#end_date").val(),
			 status:$("#status").val()
		  }
		  ,page: {
			curr: 1 
		  }
	   })
  
  }

</script>
</body>
</html>