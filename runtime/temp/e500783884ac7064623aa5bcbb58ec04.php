<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"F:\Work space\jinrongzixun\public_html/../application/admin\view\user\user_coupon.html";i:1619519510;}*/ ?>
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
    .layui-table-cell{height:50px!important;line-height:50px!important;}
</style>
<div class="layui-form layui-border-box">
    <div class="layui-table-tool">
        <div class="layui-table-tool-temp">
            <div class="demoTable">
                搜索用户： <div class="layui-inline"> <input class="layui-input" id="name" placeholder="请输入昵称|手机号|用户名"/></div>
                &nbsp;&nbsp;类型：<div class="layui-inline">
                <select id="type">
                    <option value="0">请选择</option>
                    <?php if(is_array($type_list) || $type_list instanceof \think\Collection || $type_list instanceof \think\Paginator): $key1 = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key1 % 2 );++$key1;?>
                    <option value="<?php echo $key1; ?>"><?php echo $vo; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
                &nbsp;&nbsp;状态：<div class="layui-inline">
                <select id="status">
                    <option value="0">请选择</option>
                    <?php if(is_array($status_list) || $status_list instanceof \think\Collection || $status_list instanceof \think\Paginator): $key2 = 0; $__LIST__ = $status_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key2 % 2 );++$key2;?>
                    <option value="<?php echo $key; ?>"><?php echo $vo; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
                <button class="layui-btn layui-btn-sm" onclick="chongzai();">搜索</button>
            </div>
        </div>
    </div>
</div>
<table id="demo" lay-filter="test" class="layui-hide"></table>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="rule">
{{#  if(d.type==1){  }}
满 {{d.rule}} 减 {{d.fee}}
{{#  }else{  }}
{{d.fee}}
{{#  }  }}
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
                url: "<?php echo url('couponList'); ?>", //数据接口
                method: 'post',
                cols: [[ //表头
                    {field: 'id', title: '优惠券ID',width:100,align:'center'},
                    {field: 'user_text', title:'会员手机号', width:200, templet: '#img', unresize: true,align:'center'},
                    {field: 'name', title: '优惠券名',width:220,align:'center'},
                    {field: 'type_text', title: '类型', align:'center'},
                    {field: 'fee', title: '券面值', templet:'#rule', align: 'center'},
                    {field: 'create_time', title: '添加时间',width:220,align:'center'},
                    {field: 'end_time', title: '过期时间',width:220,align:'center'},
                    {field: 'status_text', title:'状态', align:'center'},
                    {fixed: 'right', title:'操作', align:'center', toolbar: '#barDemo'}
                ]],
                id:'demo'
            });
        }
        jiazai();
        table.on('tool(test)', function(obj){
            switch(obj.event){
                case 'del':
                    del(obj.data.id,"wdl_del_coupon");
                    break;
            };
        });


    });
    function add(){
        open_window(0,"wdl_add",'添加会员','50%');
    }
    function chongzai(){
        table.reload('demo', {
            where: {
                name:$("#name").val(),
                status:$("#status").val(),
                type:$("#type").val()
            }
            ,page: {
                curr: 1
            }
        })

    }

</script>
</body>
</html>