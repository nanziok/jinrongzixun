<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"F:\Work space\jinrongzixun\public_html/../application/admin\view\user\give_coupon.html";i:1619510401;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body>
<link rel="stylesheet" type="text/css" href="/static/manage/css/style.css" />
<script type="text/javascript" src="/static/manage/js/jquery-1.8.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" />
<script type="text/javascript" src="/static/manage/layui/layui.js"></script>
<script type="text/javascript" src="/static/manage/js/public.js"></script>
<script type="text/javascript" src="/static/manage/js/jquery.form.js"></script>
<style>
    .layui-form{margin: 0 auto;width: 90%;margin-top: 10px;}
    .layui-form-pane .layui-input-block{width:50%;}
</style>
<form class="layui-form layui-form-pane" method="post" id="form" >
    <div class="layui-form-item">
        <label class="layui-form-label">赠送人</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="hidden" name="user_id" value="<?php echo input('id'); ?>">
            <input type="text" name="user_text"  placeholder="请选择赠送人" class="layui-input must" value="<?php echo $user_text; ?>" disabled readonly>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">优惠券名称</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="text" name="name"  placeholder="请输入优惠券名称" class="layui-input must" value="系统赠送优惠券">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">优惠券类型</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <select class="layui-select" name="type" lay-filter="switch-type">
                <option value="0">请选择</option>
                <?php if(is_array($type_list) || $type_list instanceof \think\Collection || $type_list instanceof \think\Paginator): $key1 = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key1 % 2 );++$key1;?>
                <option value="<?php echo $key1; ?>"><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">有效截止日</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="text" name="end_time" id="date_timeout" placeholder="请选择有效截止日" class="layui-input must" value="" autocomplete="off">
        </div>
    </div>
    <div class="layui-form-item for-use_limit layui-hide">
        <label class="layui-form-label">满减条件</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="number" step="1.00" name="rule"  placeholder="请输入满减条件" class="layui-input" value="0.00">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">优惠券金额</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="number" step="1.00" name="fee"  placeholder="请输入优惠券金额" class="layui-input must" value="0.00">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="form_btn">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-normal">重置</button>
        </div>
    </div>
</form>
<script>
    layui.use(['laydate','form'], function(){
        var form = layui.form
        ,laydate = layui.laydate;
        submit_form();

        form.on("select(switch-type)",function (data) {
            if(data.value == 1){
                $(".for-use_limit").removeClass("layui-hide")
            }else{
                $(".for-use_limit").addClass("layui-hide")
            }
        })
        laydate.render({
            elem: '#date_timeout'
            ,btns: ['clear', 'now']
            ,trigger: 'click'
        })
    })
</script>
</body>
</html>