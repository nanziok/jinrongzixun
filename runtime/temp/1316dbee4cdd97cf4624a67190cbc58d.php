<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"F:\Work space\test\public_html/../application/admin\view\goods\index.html";i:1619003031;}*/ ?>
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
<div class="layui-form layui-border-box" lay-filter="form-search">
	<div class="layui-table-tool">
			 商品分类：<div class="layui-inline">
			  <select id="cat_id">
			     <option value="0">请选择</option> 
				 <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): $i = 0; $__LIST__ = $cat_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				  <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
				 <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
			  </div> 
			 &nbsp;&nbsp;显示状态：<div class="layui-inline">
			  <select id="status">
			     <option value="0">全部</option> 
				 <option value="1">上架</option>
			     <option value="2">下架</option>
              </select>
			  </div>
			　&nbsp;&nbsp;规格类型：<div class="layui-inline">
				<select id="is-sku">
					<option value="-1">全部</option>
					<option value="2">单规格</option>
					<option value="1">多规格</option>
				</select>
				</div>
			 <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button> 
			 <button class="layui-btn layui-btn-sm layui-right" onclick="add();">添加商品</button>
		
	</div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit_id">编辑</a>
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="switchTpl">
  <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="上架|下架" lay-filter="swStatus" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="goodsImage">
	{{#  if(d.image){  }}
<img src="{{  d.image  }}" style="max-width: 45px;height:45px;"/>
	{{#  }  }}
</script>
<script type="text/html" id="goodsImages">
	{{#  var images=d.images!=null ? d.images.split(',') : [];  }}
	{{#  $.each(images,function(index,item){  }}
	<img src="{{  item  }}" style="max-width: 45px;height:45px;"/>
	{{#  });  }}
</script>
<script type="text/html" id="issku">
	{{#  if(d.issku==1){  }}
	<span style="color: #FFB800;cursor: pointer" title="点击筛选">多规格</span>
	{{#  }else{  }}
	<span style="color: #2F4056;cursor:pointer;" title="点击筛选">单规格</span>
	{{#  }  }}
</script>
<script>
var jiazai;
var table;
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;
});
layui.use(['table','form'], function(){
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
		url: "<?php echo url('index'); ?>", //数据接口
	    method: 'post',
		cols: [[ //表头
		  {field: 'id', title: '文章ID',align:'center'},
		  {field: 'cate_name', title: '分类',align:'center'},
		  {field: 'name', title: '商品名称',align:'center',edit: 'text'},
		  {field: 'lname', title: '商品文案',align:'center',edit: 'text'},
			{field: 'image', title: '商品主图',align:'center',templet: '#goodsImage',event: 'showBigImg'},
			{field: 'images', title: '轮播图',align:'center',templet: '#goodsImages',event: 'showBigImgs'},
			{field: 'issku', title: '规格类型',align:'center',templet: '#issku',event:"search-sku"},
			{field: 'price', title: '价格',align:'center',edit: 'text'},
			{field: 'mark_price', title: '市场价格',align:'center',edit: 'text'},
			{field: 'status', title:'状态', width:120, templet: '#switchTpl', unresize: true,align:'center'},
		  {field: 'create_time', title: '添加时间',align:'center'},
			{field: 'weigh', title: '排序',align:'center',edit: 'text'},
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
		case 'search-sku':
			searchData(obj.data,'issku');
			form.val("form-search", {
				issku: obj.value
			})
			break;
		case 'showBigImg':
			showBigImg(obj.data.image);
		case 'showBigImgs':
			layer.photos({
				photos: ""
			})
	  };
  });

   form.on('switch(swStatus)', function(obj){
      var id = this.value;
	  var value = obj.elem.checked;
	  var status;
	  if(value == true){
	    status = 1;
	  }else{
	  	status = 2;
	  } 
	  var arr = {};
	  arr.id = id;
	  arr.status = status;
	  arr.table = 'goods';
	  set_data(arr);
  });
   table.on('edit(test)', function(obj){
		console.log("单元格编辑",obj.data);
		var temp_data = {};
		temp_data["table"] = 'goods';
		temp_data["id"] = obj.data.id;
		temp_data[obj.field] = obj.value;
		set_data(temp_data, obj.value);
   });

});
  function add(){
	openurl("<?php echo url('wdl_add'); ?>");
  }
  function chongzai(){
	    var cate_id = $("#cat_id").val();
		var status = $("#status").val(),
				issku = $("#is-sku").val();
		table.reload('demo', {
		  where: { 
			cate_id: cate_id,
			status: status,
			  issku: issku
		  }
		  ,page: {
			curr: 1 
		  }
		  ,method: 'post',
	   })
  
  }

/**
 * @param data 行数据
 * @param col 检索列
 */
function searchData(data,col=''){
	var data_where = {};
	if(col in data) {
		data_where[col]=data[col];
		//快捷筛选字段，同步到搜索条件
		if(col=='issku') {
			$("#is-sku").val(data.issku);
		}
		table.reload('demo', {
			where: data_where
			, page: {
				curr: 1
			}
			, method: 'post',
		})
	}else{
		console.log(data,col);
		layer.msg("不支持的检索列");
	}
  }
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