<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"F:\Work space\jinrongzixun\public_html/../application/admin\view\service\wdl_edit_cat.html";i:1619157139;}*/ ?>
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
<script type="text/javascript" src="/static/manage/js/jquery.tagsinput.js"></script>
<link rel="stylesheet" type="text/css" href="/static/manage/css/jquery.tagsinput.css" />
<link rel="stylesheet" type="text/css" href="/static/manage/css/jquery-ui.css" />
<script type="text/javascript" src="/static/manage/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/manage/ueditor/ueditor.all.js"></script>


<style>
    .layui-form{margin: 0 auto;width: 90%;}
    .layui-form .layui-input-block{width:20%;}
    .layui-upload-img {
        width: 92px;
        height: 92px;
        /*margin: 10px;*/
        background-image: url("/static/manage/images/add_fpic.gif");
        background-size: cover;
        border: #eee 1px solid;
    }
</style>
<div class="layui-border-box">
    <div class="layui-table-tool">
        添加咨询师
        <button class="layui-btn layui-btn-sm layui-right" onclick="window.history.back(-1);">返回咨询师列表</button>
    </div>
</div>
<form class="layui-form layui-form-pane" method="post" id="form" style="margin-top: 10px;">
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="text" name="name" placeholder="请输入咨询师名称" class="layui-input must" value="<?php echo $row['name']; ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">头像</label>
        <div class="layui-input-block"><span class="require-field">*</span>
            <input type="text" name="image" placeholder="请上传头像" id="image_upload" class="layui-input must layui-hide" value="<?php echo $row['image']; ?>">
            <button type="button" class="layui-btn" id="uploadimage">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>
            <p style="margin-top: 10px"><img src="<?php echo $row['image']; ?>" class="layui-upload-img" id="image-preview" /></p>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标签</label>
        <div class="layui-input-block">
            <div class="tags" id="tags" style="width: 50%">
            <input type="text" name="tags" placeholder="回车生成标签" id="jquery-inputtags" class="" value="<?php echo $row['tags']; ?>" />
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容介绍</label>
        <div class="layui-input-block" style="width:60%;max-height:500px;z-index:0;"><span class="require-field">*</span>
            <textarea class="layui-textarea" id="container" name="content"><?php echo $row['content']; ?></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">价格</label>
        <div class="layui-input-block" style="width:60%;z-index:0;"><span class="require-field">*</span>
            <input class="layui-input must" placeholder="请输入价格" name="price" value="<?php echo $row['price']; ?>" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-block" style="width:60%;z-index:0;"><span class="require-field">*</span>
            <input class="layui-input must" placeholder="请输入排序数字" name="weigh" value="<?php echo $row['weigh']; ?>" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block" style="width:60%;z-index:0;"><span class="require-field">*</span>
            <input type="checkbox" class="layui-form-switch" lay-skin="switch" value="1" placeholder="是否显示" lay-text="显示|隐藏" name="status" value="10" <?php if($row['status'] == 1): ?>checked<?php endif; ?> />
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
    layui.use(['form','upload'], function(){
        var form = layui.form,upload=layui.upload;
        var ue = UE.getEditor('container', {
            autoHeightEnabled: true,
            autoFloatEnabled: true,
        });
        $imageUpload = upload.render({
            elem: '#uploadimage, #image-preview',
            url: "<?php echo url('admin/upload/up_do'); ?>",
            accept: 'images', //允许上传的文件类型
            acceptMime: 'image/jpg,image/jpeg,image/png',
            exts: 'jpg|png|gif|bmp|jpeg',
            auto: true, //选择后自动上传
            size: 500, //最大允许上传的文件大小，单位Kb
            multiple: false,
            done: function (res, index, upload) {
                console.log("上传结果",res,index,upload,this.item);
                $("#image_upload").val(res.pic);
                $('#image-preview').attr("src",res.pic);
            }
        });
        submit_form_back();
    })
        $('#jquery-inputtags').tagsInput({
            defaultText:'输入空格添加标签',
        });
</script>
</body>
</html>