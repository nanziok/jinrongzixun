<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"F:\Work space\jinrongzixun\public_html/../application/admin\view\order\kyc_logs.html";i:1619252872;}*/ ?>
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
        &nbsp;&nbsp;用户： <div class="layui-inline"> <input class="layui-input" id="user_name" placeholder="搜索用户"/></div>
        &nbsp;&nbsp;咨询师： <div class="layui-inline"> <input class="layui-input" id="tech_name" placeholder="搜索咨询师"/></div>
        &nbsp;&nbsp;状态：<div class="layui-inline">
        <select id="status">
            <option value="">请选择</option>
            <option value="1">未解决</option>
            <option value="2">已解决</option>
        </select>
    </div>
        <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button>
    </div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit_id">处理</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="status" value="1" lay-skin="switch" lay-text="未处理|已处理" lay-filter="sexDemo" {{ d.status == 2 ? 'checked' : '' }}>
</script>
<script type="text/html" id="row-status">
    {{#  if(d.status == 1){  }}
    未解决
    {{#  }else{  }}
    已解决
    {{#  }  }}


</script>

<script type="text/html" id="order_status">
{{#  if(d.order_status==2){  }}
    <span style="color: red">{{d.order_status_text}}</span>
{{#  }else{  }}
    <span>未付款</span>
{{#  }  }}

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
                url: "<?php echo url('kyc_logs'); ?>", //数据接口
                method: 'post',
                cols: [[ //表头
                    {field: 'id', title: 'ID',width:80,align:'center'},
                    {field: 'order_no', title: '订单编号',align:'center'},
                    {field: 'user_text', title: '下单用户',align:'center'},
                    {field:'service_text', title:'咨询师', align:'center'},
                    {field:'service_test_text', title:'问卷名称', align:'center'},
                    {field:'create_time', width:160, title:'提交时间', align:'center'},
                    {field: 'status', title: '状态', templet:'#row-status', align:'center'},
                    {field: 'order_status', title: '问卷标识', templet: '#order_status', align: 'center'},
                    {field: 'review_time', width:160, title:'答复时间', align:'center'},
                    {fixed: 'right', title:'操作', align:'center', toolbar: '#barDemo'}
                ]],
                id:'demo'
            });
        }
        jiazai();
        table.on('tool(test)', function(obj){
            switch(obj.event){
                case 'edit_id':
                    open_window(obj.data.id,"wdl_edit_log",'答疑','80%','75%');
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
                tech_name: $('#tech_name').val(),
                user_name: $('#user_name').val(),
            }
            ,page: {
                curr: 1
            }
        })

    }
</script>
</body>
</html>