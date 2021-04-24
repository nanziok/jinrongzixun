<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"F:\Work space\jinrongzixun\public_html/../application/admin\view\order\index.html";i:1619248029;}*/ ?>
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
        订单编号： <div class="layui-inline"> <input class="layui-input" id="order_no" placeholder="搜索订单编号"/></div>
        &nbsp;&nbsp;咨询师： <div class="layui-inline"> <input class="layui-input" id="tech_name" placeholder="搜索咨询师"/></div>
        &nbsp;&nbsp;状态：<div class="layui-inline">
        <select id="status">
            <option value="0">请选择</option>
            <?php if(is_array($status_list) || $status_list instanceof \think\Collection || $status_list instanceof \think\Paginator): $key = 0; $__LIST__ = $status_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?>
            <option value="{key}"><?php echo $vo; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
    </div>
        <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button>
    </div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit_id">查看</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="显示|隐藏" lay-filter="sexDemo" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="img">
    <img src="{{d.img}}" onclick="showImg({{d.id}},0,'service','id','img')" style='height:50px;cursor:pointer;'>
</script>
<script type="text/html" id="row-status">
    {{#var color_text}}
    {{#  switch(d.status){ case '1':color_text='#5FB878';break;case '2':color_text='#009688';break;case '3':color_text='#c2c2c2';break;case '4':color_text='#e2e2e2';break;case '5':color_text='#FF5722';break;case '6':color_text='#1E9FFF';break; }  }}

    <span style="color:{{color_text}}">{{d.status_text}}</span>

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
                url: "<?php echo url('index'); ?>", //数据接口
                method: 'post',
                cols: [[ //表头
                    {field: 'id', title: 'ID',width:80,align:'center'},
                    {field: 'order_no', title: '订单编号',align:'center'},
                    {field: 'user_text', title: '下单用户',align:'center'},
                    {field:'service_text', title:'咨询师', align:'center'},
                    {field:'price', title:'价格', align:'center'},
                    {field:'c_amount', title:'应付款', align:'center'},
                    {field:'t_amount', title:'实付款', align:'center'},
                    {field:'pay_type_text', title:'支付方式', align:'center'},
                    {field:'payment_code', title:'支付平台订单号', align:'center'},
                    {field:'create_time', width:160, title:'下单时间', align:'center'},
                    {field: 'test_times', title: '服务次数', align: 'center'},
                    {field: 'status', title: '状态', templet:'#row-status', align:'center'},
                    {fixed: 'right', title:'操作', align:'center', toolbar: '#barDemo'}
                ]],
                id:'demo'
            });
        }
        jiazai();
        table.on('tool(test)', function(obj){
            switch(obj.event){
                case 'edit_id':
                    open_window(obj.data.id,"wdl_edit",'查看订单','80%','75%');
                    break;
                case 'del':
                    del(obj.data.id,"wdl_del");
                    break;
            };
        });

    });
    function chongzai(){
        table.reload('demo', {
            where: {
                status: $("#status").val(),
                order_no: $("#order_no").val(),
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