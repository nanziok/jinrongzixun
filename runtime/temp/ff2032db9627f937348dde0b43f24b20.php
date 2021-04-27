<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"F:\Work space\jinrongzixun\public_html/../application/admin\view\order\wdl_edit.html";i:1619511092;}*/ ?>
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
    .layui-input-block{width:50%;}
    .layui-form-label {
        white-space: nowrap;
        word-break: keep-all;
    }
</style>
<form class="layui-form" action="<?php echo url('wdl_cat_add_do'); ?>" id="form" >
    <input name="id" type="hidden" value="<?php echo $row['id']; ?>" />
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md6">
            <label class="layui-form-label">订单编号</label>
            <div class="layui-input-block"><span class="require-field">*</span>
                <input type="text" name="order_no" placeholder="订单编号" class="layui-input must" value="<?php echo $row['order_no']; ?>">
            </div>
        </div>
        <div class="layui-col-md6">
            <label class="layui-form-label">下单人</label>
            <div class="layui-input-block"><span class="require-field">*</span>
                <input type="text" name="user_text" placeholder="下单人" class="layui-input must" value="<?php echo $row['user_text']; ?>">
            </div>
        </div>
    </div>
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md6">
            <label class="layui-form-label">咨询师</label>
            <div class="layui-input-block"><span class="require-field">*</span>
                <input type="text" name="service_text" placeholder="咨询师" class="layui-input must" value="<?php echo $row['service_text']; ?>">
            </div>
        </div>
        <div class="layui-col-md6">
            <label class="layui-form-label">订单总价</label>
            <div class="layui-input-block"><span class="require-field">*</span>
                <input type="price" name="service_text" placeholder="咨询师" class="layui-input must" value="<?php echo $row['price']; ?>">
            </div>

        </div>
    </div>
    <div class="layui-row layui-col-space10">
    <div class="layui-col-md6">
        <label class="layui-form-label">应付款</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="text" name="c_amount" placeholder="应付款" class="layui-input must" value="<?php echo $row['c_amount']; ?>">
        </div>
    </div>
    <div class="layui-col-md6">
        <label class="layui-form-label">实付款</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="text" name="t_amount" placeholder="实付款" class="layui-input must" value="<?php echo $row['t_amount']; ?>">
        </div>
    </div>
    </div>
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md6">
            <label class="layui-form-label">支付平台单号</label>
            <div class="layui-input-block"><span class="require-field">*</span>
                <input type="text" name="payment_code" placeholder="支付平台单号" class="layui-input must" value="<?php echo $row['payment_code']; ?>">
            </div>
        </div>
        <div class="layui-col-md6">
            <label class="layui-form-label">支付方式</label>
            <div class="layui-input-block"><span class="require-field">*</span>
                <input type="t_amount" name="pay_type_text" placeholder="实付款" class="layui-input must" value="<?php echo $row['pay_type_text']; ?>">
            </div>
        </div>

    </div>
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md6">
            <label class="layui-form-label">支付时间</label>
            <div class="layui-input-block">
                <input name="pay_time" type="text" class="layui-input" value="<?php echo $row['pay_time']; ?>">
            </div>
        </div>
        <div class="layui-col-md6">
            <label class="layui-form-label">订单状态</label>
            <div class="layui-input-block">
                <select name="status">
                    <?php if(is_array($status_list) || $status_list instanceof \think\Collection || $status_list instanceof \think\Paginator): $key = 0; $__LIST__ = $status_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?>
                    <option value="<?php echo $key; ?>"><?php echo $vo; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md6">
            <label class="layui-form-label">下单时间</label>
            <div class="layui-input-block">
                <input type="text" name="create_time" placeholder="下单时间" class="layui-input" value="<?php echo $row['create_time']; ?>"/>
            </div>
        </div>
        <div class="layui-col-md6">
            <label class="layui-form-label">更新时间</label>
            <div class="layui-input-block">
                <input type="text" name="update_time" placeholder="更新时间" class="layui-input" value="<?php echo $row['update_time']; ?>"/>
            </div>
        </div>
    </div>
    <div class="layui-form-item" style="margin-top: 20px;margin-left: 50%;">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="form_btn">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-normal">重置</button>
        </div>
    </div>

</form>
<script>
    layui.use('form', function(){
        var form = layui.form;
        submit_form();
    })
</script>
</body>
</html>