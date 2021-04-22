<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"F:\Work space\test\public_html/../application/admin\view\goods\wdl_edit.html";i:1618994902;}*/ ?>
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
<script type="text/javascript" src="/static/manage/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/manage/ueditor/ueditor.all.js"></script>
<script type="text/javascript" src="/static/manage/js/Sortable.min.js"></script>
<script type="text/javascript" src="/static/manage/clickImg/jquery.drag.js"></script>
<link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_2502109_ws6vr90kaj9.css" />
<style>
.layui-form{margin: 0 auto;width: 100%;}
.layui-form .layui-input-block{width:20%;}
.layui-upload-img {
  width: 108px;
  height: 108px;
  background-image: url("/static/manage/images/add_fpic.gif");
  background-size: cover;
  border: #eee 1px solid;
}
.data-image-preview{
  width: 38px;
  height: 38px;
  background-color: #AAAAAA;
  background-size: cover;
  background-image: url("/static/manage/images/add_fpic.gif");
  border: #eee 1px solid ;
}
.layui-upload-list{
  margin-left: 110px;
  width: 80%;
}
.layui-upload-list .img-item{
  display: inline-block!important;
  position: relative;
  margin: 10px;
}
.layui-upload-list .img-item:hover{
  border: 1px solid;
}
.layui-upload-list .img-item span{
  position: absolute;
  top:0px;
  right: 0px;
  width: 100%;
}
.layui-upload-list .img-item:hover span{
  display: inline-block!important;
}
.layui-upload-list .img-item span .set-flag{
  margin: 6px;
  line-height: 24px;
  font-size: 24px; color: #007DDB;
  font-weight: 700;
  display: inline-block;
  float: right;
  cursor: pointer;
}
.layui-upload-list .img-item span .del-item {
  margin: 6px;
  line-height: 24px;
  font-size: 24px;
  color: darkred;
  font-weight: 700;
  display: inline-block;
  float: right;
  cursor: pointer;
}
.drag-sku-item{
  padding:2px 6px 2px 6px;
  background-color: #2c3e50;
}
.drag-sku-item i{
  font-size: 26px;
  color: #fff;
  line-height: 38px;
}
.sku-data-title, .sku-data-item, .sku-data-act{min-width: 920px;margin-top:10px }
.sku-data-title dd{font-weight: 700;text-align: center;display: inline-block;width: 120px;  }
.sku-data-item dd{font-weight: 700;text-align: center;display: inline-block;width: 120px}
</style>
<div class="layui-form layui-border-box">
	<div class="layui-table-tool">
		编辑商品
		<button class="layui-btn layui-btn-sm layui-right" onclick="window.history.back(-1);">返回商品列表</button> 
	</div>
</div>
<form class="layui-form" id="form" style="margin-top:10px;width:90%;" method="post">

  <div class="layui-form-item">
    <label class="layui-form-label">商品分类：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <select name="cate_id" class="must" msg="请选择商品分类" filter="false">
        <option value="0">请选择</option>
        <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): $i = 0; $__LIST__ = $cat_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	      <option value="<?php echo $vo['id']; ?>"  <?php if($row['cate_id'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
	    <?php endforeach; endif; else: echo "" ;endif; ?>
      </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">商品名：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="name"  placeholder="请输入商品标题" value="<?php echo $row['name']; ?>" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">宣传文案：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="lname"  placeholder="请输入商品文案" value="<?php echo $row['lname']; ?>" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">商品大图：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="image"  placeholder="请上传商品主图" value="<?php echo $row['image']; ?>" class="layui-input layui-hide must">
      <button type="button" class="layui-btn" id="uploadimage">
        <i class="layui-icon">&#xe67c;</i>上传图片
      </button>
    </div>
    <div class="layui-upload-list">
      <div class="img-item">
      <img class="layui-upload-img" id="uploadimage-box" src="<?php echo $row['image']; ?>">
      </div>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">轮播图：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="images"  placeholder="点击上传商品轮播图" value="<?php echo $row['images']; ?>" class="layui-input layui-hide must">
      <button type="button" class="layui-btn" id="uploadimages">
        <i class="layui-icon">&#xe67c;</i>批量上传
      </button>
    </div>
    <div class="layui-upload-list" id="uploadimages-box">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">商品价格：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="number" step="0.01" name="price"  placeholder="请输入商品价格" value="<?php echo $row['price']; ?>" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">市场价：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="kucun" step="0.01" name="mark_price"  placeholder="请输入商品市场价" value="<?php echo $row['mark_price']; ?>" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">品牌：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="brand"  placeholder="请输入商品品牌" value="<?php echo $row['brand']; ?>" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">库存：</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="number" name="kucun"  placeholder="请输入商品市场价" value="<?php echo $row['kucun']; ?>" class="layui-input must">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">是否多规格：</label>
    <div class="layui-input-block">
      <input type="checkbox" name="issku"  placeholder="是否多规格" lay-skin="switch" lay-filter="switchSku" value="1" lay-text="多规格|单规格" <?php if($row['issku'] == 1): ?>checked<?php endif; ?>>
    </div>
  </div>
  <div class="layui-form-item for-sku <?php if($row['issku'] != 1): ?>layui-hide<?php endif; ?>">
    <label class="layui-form-label">多规格配置：</label>
    <div class="layui-input-block" id="sku-setting-box">
      <dl class="sku-data-title">
        <dd>规格名</dd><dd>价格</dd><dd>市场价</dd><dd>库存</dd><dd>图片</dd><dd>状态</dd><dd></dd>
      </dl>
      <?php if(!(empty($row['sku-data']) || (($row['sku-data'] instanceof \think\Collection || $row['sku-data'] instanceof \think\Paginator ) && $row['sku-data']->isEmpty()))): if(is_array($row['sku-data']) || $row['sku-data'] instanceof \think\Collection || $row['sku-data'] instanceof \think\Paginator): $key = 0; $__LIST__ = $row['sku-data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?>
      <dl class="sku-data-item">
        <dd><input type="text" class="layui-input" data-name="name" value="<?php echo $vo['name']; ?>"></dd>
        <dd><input type="text" class="layui-input" data-name="price" value="<?php echo $vo['price']; ?>"></dd>
        <dd><input type="text" class="layui-input" data-name="mark_price" value="<?php echo $vo['mark_price']; ?>"></dd>
        <dd><input type="text" class="layui-input" data-name="kucun" value="<?php echo $vo['kucun']; ?>"></dd>
        <dd><input type="text" class="layui-input layui-hide" data-name="image" value="<?php echo $vo['image']; ?>"><img src="<?php echo $vo['image']; ?>" class="data-image-preview"/></dd>
        <dd><input type="checkbox" data-name="status" value="1" lay-filter="switchStatus" lay-skin="switch" lay-text="上架|下架" <?php if($vo['status'] == 1): ?>checked<?php endif; ?>></dd>
        <dd style="width: 150px;"><button type="button" class="layui-btn drag-sku-item" style="display: inline-block"><i class="iconfont iconmove"></i></button><button type="button" class="layui-btn layui-btn-danger del-sku-item" style="display: inline-block">移除</button> </dd>
      </dl>
      <?php endforeach; endif; else: echo "" ;endif; endif; ?>
      <dl class="sku-data-act">
        <dd>
          <button type="button" class="layui-btn" id="sku-data-addOne">添加一行</button>
          <button type="button" class="layui-btn" id="commone-uploader" style="display: none">上传图片</button>
          <textarea name="sku-data" style="display: none"><?php echo json_encode($row["sku-data"]); ?></textarea>
        </dd>
      </dl>
      <script type="text/html" id="sku_data_itemTpl">
        <dl class="sku-data-item">
          <dd><input type="text" class="layui-input" data-name="name" value=""></dd>
          <dd><input type="text" class="layui-input" data-name="price" value=""></dd>
          <dd><input type="text" class="layui-input" data-name="mark_price" value=""></dd>
          <dd><input type="text" class="layui-input" data-name="kucun" value=""></dd>
          <dd><input type="text" class="layui-input layui-hide" data-name="image" value=""><img class="data-image-preview"/></dd>
          <dd><input type="checkbox" data-name="status" value="1" lay-filter="switchStatus" lay-skin="switch" lay-text="上架|下架"></dd>
          <dd style="width: 150px;">
            <button type="button" class="layui-btn drag-sku-item" style="display: inline-block"><i class="iconfont iconmove"></i></button>
            <button type="button" class="layui-btn layui-btn-danger del-sku-item" style="display: inline-block">移除</button>
          </dd>
        </dl>
      </script>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">商品介绍：</label>
    <div class="layui-input-block" style="width:70%;z-index:0;">
      <textarea name="content" id="container" placeholder="请输入内容" style="min-height:300px;"><?php echo $row['content']; ?></textarea>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">排序：</label>
    <div class="layui-input-block">
      <input type="number" name="weigh" value="<?php echo $row['weigh']; ?>" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">状态：</label>
    <div class="layui-input-block">
      <input type="checkbox" <?php if($row['status'] == 1): ?>checked<?php endif; ?> name="status" lay-skin="switch" lay-filter="switchTest" lay-text="上架|下架" value="1" />
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button type="button" class="layui-btn" id="form_btn" lay-filter="form-submit">立即提交</button>
	  <button type="reset" class="layui-btn layui-btn-normal">重置</button>
    </div>
  </div>
</form>
<script>

layui.use(['form','upload','laytpl'], function(){
  var form = layui.form,
    upload=layui.upload,
    laytpl=layui.laytpl,
          uploadSkuImage;
    submit_form_back();
  var ue = UE.getEditor('container', {
    autoHeightEnabled: true,
    autoFloatEnabled: true,
  });
  
  //是否显示多规格配置
  form.on('switch(switchSku)', function(data){
    if(data.elem.checked){
      $(".for-sku").removeClass("layui-hide");
    }else{
      $(".for-sku").addClass("layui-hide");
    }
  })
  //多规格配置,添加一行
  $("#sku-data-addOne").click(function () {
    var dom_ = $(".sku-data-item").length>0 ? $(".sku-data-item").last() : $(".sku-data-title")
      html_ = sku_data_itemTpl.innerHTML;
    dom_.after(html_);
    form.render();
  })
  //多规格配置，监听改动，从dom获取数据写入textarea
  $(document).on("input inputchange propertychange","input[data-name='name'],input[data-name='price'],input[data-name='mark_price'],input[data-name='kucun'],input[data-name='image'],input[data-name='status']",function () {
    var temp = [], temp_item={}, temp_name;
    $(".sku-data-item").each(function () {
      $(this).find("input[data-name='name'],input[data-name='price'],input[data-name='mark_price'],input[data-name='kucun'],input[data-name='image']").each(function (index) {
        temp_name = $(this).data("name");
        temp_item[temp_name] = $(this).val();
      })
      temp_item["status"] = $(this).find("input[data-name='status']").is(':checked') ? $(this).find("input[data-name='status']").val() : 2;
      temp.push(temp_item);
      temp_item={};
    })
    $("textarea[name='sku-data']").text(JSON.stringify(temp));

  })
  //多规格配置删除一行
  $(document).on("click",".del-sku-item",function (){
    $(this).closest(".sku-data-item").remove();
    $("input[data-name='name']").eq(0).trigger("propertychange");
    // form.render();
  })
  //多规格配置拖曳修改顺序
  var list_sku = document.getElementById("sku-setting-box");
  var ops_sku = {
    animation: 200,
    handle: ".drag-sku-item",
    draggable: ".sku-data-item",
    direction: 'vertical',
    forceFallback:true,
    group:'item-box-drag',
    //拖动结束
    onEnd: function (evt) {
      $("input[data-name='name']").eq(0).trigger("propertychange");
    },
  };
  Sortable.create(list_sku,ops_sku);

  //多规格配置上传图片，多按钮公用
  $(document).on("click",".sku-data-item input[data-name='image'], .sku-data-item .data-image-preview", function () {
    var dom_where = $(this).closest(".sku-data-item");
    common_uploader.reload({
      done:function (res,index,upload){
        //把取得的结果写入适合的位置
        dom_where.find("input[data-name='image']").val(res.pic).trigger("propertychange");
        //生成缩略图，附加到尾部
        dom_where.find(".data-image-preview").remove();
        dom_where.find("input[data-name='image']").eq(0).after("<img src='"+res.pic+"' class='data-image-preview'/>")
      }
    })
    $('#commone-uploader').trigger("click");
  })
  //多规格单项开关
  form.on("switch(switchStatus)",function (data) {
    $("input[data-name='name']").eq(0).trigger("propertychange");
  })
  form.on("submit(form-submit)", function(data){
    layer.msg(JSON.stringify(data.field));
    console.log("提交之前", data);
    return false;
  });

  var common_uploader = upload.render({
    elem: '#commone-uploader',
    url: "<?php echo url('admin/upload/up_do'); ?>",
    accept: 'images', //允许上传的文件类型
    acceptMime: 'image/jpg,image/jpeg,image/png',
    exts: 'jpg|png|gif|bmp|jpeg',
    auto: true, //选择后自动上传
    size: 500, //最大允许上传的文件大小，单位Kb
    multiple: false,
    before: function () {
      console.log("上传之前",this);
    },
    done: function (res, index, upload) {
      console.log("上传结果",res);
      //this.item.closest(".sku-data-item").find("input[data-name='image']").val(res.pic).trigger("propertychange")
    }
  });
  //商品大图点击上传
  $("#uploadimage-box, input[name='image']").click(function () {
    $("#uploadimage").trigger("click");
  })
  //商品大图点击上传
  $("input[name='images']").click(function () {
    $("#uploadimages").trigger("click");
  })
  //js把多图写入dom
  var images = '<?php echo $row['images']; ?>';
  images = images ? images.split(",") : [];
  $.each(images,function (index,item){
    $("#uploadimages-box").append('<div class="img-item"><img src="' + item + '" class="layui-upload-img"><span style="display: none;background-color: #ddd;"><i class="layui-icon layui-icon-star-fill set-flag" title="设置主图"></i><i class="layui-icon layui-icon-delete del-item" title="删除"></i></span></div>');

  })
  //商品大图上传按钮
  var uploadInst = upload.render({
    elem: '#uploadimage',
    url: "<?php echo url('admin/upload/up_do'); ?>",
    done: function(res, index, upload){ //上传后的回调

    },
    accept: 'images', //允许上传的文件类型
    acceptMime: 'image/jpg,image/jpeg,image/png',
    exts: 'jpg|png|gif|bmp|jpeg',
    auto: true, //选择后自动上传
    size: 500, //最大允许上传的文件大小，单位Kb
    multiple: false,
    before: function(obj) {
      //预读本地文件示例，不支持ie8
      obj.preview(function (index, file, result) {
        $('#uploadimage-box').attr('src',result);
        // $('#uploadimages-box').append('<img src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
      });
    },
    progress: function(n, elem){
      var percent = n + '%' //获取进度百分比
      element.progress('demo', percent); //可配合 layui 进度条元素使用

      //以下系 layui 2.5.6 新增
      console.log(elem); //得到当前触发的元素 DOM 对象。可通过该元素定义的属性值匹配到对应的进度条。
    },
    done: function(res, index, upload){
      $("input[name='image']").val(res.pic);
    },
    error:function (index,upload){
      //uploadInst.upload();
      layer.msg('上传有误');
      //删除本地预览
      $("input[name='image']").val('');
    }
  })

  //商品轮播图上传按钮
  var uploadInst2 = upload.render({
    elem: '#uploadimages',
    url: "<?php echo url('admin/upload/up_do'); ?>",
    done: function(res, index, upload){ //上传后的回调

    },
    accept: 'images', //允许上传的文件类型
    acceptMime: 'image/jpg,image/jpeg,image/png',
    exts: 'jpg|png|gif|bmp|jpeg',
    auto: true, //选择后自动上传
    size: 500, //最大允许上传的文件大小，单位Kb
    multiple: true,
    before: function(obj) {
      //预读本地文件示例，不支持ie8
      // obj.preview(function (index, file, result) {
      //   $('#uploadimages-box').append('<img src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
      // });
    },
    done: function(res, index, upload){
      var res_input=$("input[name='images']").val() ? $("input[name='images']").val().split(",") : [];
      res_input.push(res.pic);
      $("input[name='images']").val(res_input.join(",")).trigger("change");
    },
    error:function (index,upload){
      //uploadInst2.upload();
      layer.msg('上传有误');
      //删除本地预览
      $("input[name='image']").val('');
    }
  })

})

$(function(){
  //委托监听轮播图input，还原到dom中去
  $(document).on("change","input[name='images']",function () {
    $('#uploadimages-box').html("");
    var data = $(this).val() ? $(this).val().split(",") : [];
    $.each(data, function (index,item) {
      $('#uploadimages-box').append('<div class="img-item"><img src="' + item + '" class="layui-upload-img"><span style="display: none;"><i class="layui-icon layui-icon-star-fill set-flag" title="设置主图"></i><i class="layui-icon layui-icon-delete del-item" title="删除"></i></span></div>');
    })

  })
  //多图绑定事件
  $("#uploadimages-box").on("click",".img-item span .set-flag",function () {
      //根据当前点击的位置，重写主图信息
    var img_src = $(this).closest(".img-item").find("img").attr("src");
    $("img#uploadimage-box").attr("src",img_src);
    $("input[name='image']").val(img_src);
  });
  //委托点击事件【删除】
  $("#uploadimages-box").on("click",".img-item span .del-item",function () {
    var item_index=$(this).closest(".img-item").index("#uploadimages-box .img-item");
    var images_value=$("input[name='images']").val();
    var temp = images_value.split(",");
    if (item_index > -1) {
      temp.splice(item_index, 1);
    }
    $(this).closest(".img-item").remove();
    $("input[name='images']").val(temp.join(","));
  });

  //监听排序，重新读取排序结果
  resizeImagesIndex = function (){
    var temp=[];
    $("#uploadimages-box .img-item").each(function (index) {
      temp.push($(this).find("img").attr("src"));
    })
    $("input[name='images']").val(temp.join(","));
  }
  //商品轮播图拖动排序
  var list = document.getElementById("uploadimages-box");
  var ops2 = {
    animation: 600,
    draggable: ".img-item",
    direction: 'horizontal',
    forceFallback:true,
    group:'images-box-drag',
    //拖动结束
    onEnd: function (evt) {
      resizeImagesIndex();
    },
  };
  Sortable.create(list,ops2);
  

})

</script>
</body>
</html>