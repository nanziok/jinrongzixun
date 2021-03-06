<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"F:\Work space\jinrongzixun\public_html/../application/admin\view\service\wdl_edit_kyc.html";i:1619404560;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_2502109_ws6vr90kaj9.css" />
<script type="text/javascript" src="/static/manage/js/Sortable.min.js"></script>
<style>
  .layui-form{margin: 0 auto;width: 100%;margin-top: 10px;}
  .layui-form-pane .layui-input-block{width:50%;}
  .test-data-title, .test-data-item{
    display: flex;
    margin: 5px 0;
  }
  .test-data-title dd {
    font-weight: bold;
    display: inline-block;
    text-align:center;
  }
  .test-data-title .xuhao, .test-data-item .xuhao{width: 50px;  }
  .test-data-item .xuhao{
    text-align: center;
    display: inline-flex;
    display: -webkit-flex;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .test-data-title .type, .test-data-item .type{width: 120px;  }
  .test-data-item .type{
    text-align: center;
    display: inline-flex;
    display: -webkit-flex;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .test-data-title .timu, .test-data-item .timu{width: 220px;  }
  .test-data-title .tinei, .test-data-item .tinei{width: 400px;  }
  .test-data-title .act, .test-data-item .act{
    width: 200px;
    text-align: center;
    display: inline-flex;
    display: -webkit-flex;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .test-data-item textarea{
    width:90%;
    height: 120px;
    display: block;
    margin: 0 auto;
  }
  .test-data-item button{
    height:38px;
  }
  #ac{display:none;}
  #ac img{height:100px;margin-left:20px;}
  .delete{background: #666;color: #fff;height: 30px;line-height: 30px;width: 100px;text-align: center;margin-top: 10px;cursor:pointer;}
  .layui-form-item{margin-left: 15px;}
</style>
<div class="layui-form layui-border-box">
  <div class="layui-table-tool">??????????????????</div>
</div>
<form class="layui-form layui-form-pane" method="post" id="form" >
  <div class="layui-form-item">
    <label class="layui-form-label">????????????</label>
    <div class="layui-input-block"><span class="require-field">*</span>
      <input type="text" name="name" placeholder="?????????????????????" class="layui-input must" value="<?php echo $row['name']; ?>">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">???????????????</label>
    <div class="layui-input-block">
      <select name="service_id" placeholder="??????????????????">
        <option value="0" <?php if($row['service_id'] == 0): ?>selected<?php endif; ?>>??????????????????</option>
        <?php if(is_array($service_list) || $service_list instanceof \think\Collection || $service_list instanceof \think\Paginator): $i = 0; $__LIST__ = $service_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <option value="<?php echo $vo['id']; ?>" <?php if($row['service_id'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
        <?php endforeach; endif; else: echo "" ;endif; ?>
      </select>
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">????????????</label>
    <div class="layui-input-block" style="min-width: 850px" id="test-setting-box">
      <textarea name="content" placeholder="?????????????????????" class="layui-textarea layui-hide must"><?php echo $row['content']; ?></textarea>
      <dl class="test-data-title">
        <dd class="xuhao">??????</dd><dd class="type">??????</dd><dd class="timu">??????</dd><dd class="tinei">??????</dd><dd class="act">??????</dd>
      </dl>
      <?php if(!(empty($row['content_array']) || (($row['content_array'] instanceof \think\Collection || $row['content_array'] instanceof \think\Paginator ) && $row['content_array']->isEmpty()))): if(is_array($row['content_array']) || $row['content_array'] instanceof \think\Collection || $row['content_array'] instanceof \think\Paginator): $i = 0; $__LIST__ = $row['content_array'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
      <dl class="test-data-item">
        <dd class="xuhao"><div><?php echo $vo['xuhao']; ?></div></dd>
        <dd class="type"><select lay-filter="test-item-type"><option value="radio" <?php if($vo['type'] == 'radio'): ?>selected<?php endif; ?>>??????</option><option value="checkbox" <?php if($vo['type'] == 'checkbox'): ?>selected<?php endif; ?>>??????</option> </select></dd>
        <dd class="timu"><textarea><?php echo $vo['timu']; ?></textarea></dd>
        <dd class="tinei"><textarea><?php echo $vo['tinei']; ?></textarea></dd>
        <dd class="act">
          <button type="button" class="layui-btn drag-test-item" style="display: inline-block"><i class="iconfont iconmove"></i></button>
          <button type="button" class="layui-btn remove-item">??????</button>
        </dd>
      </dl>
      <?php endforeach; endif; else: echo "" ;endif; endif; ?>
      <dl>
        <dd>
          <button type="button" class="layui-btn" id="add-question"><i class="layui-icon layui-icon-add-1"></i>????????????</button>
        </dd>
      </dl>
      <script type="text/html" id="test_data_itemTpl">
        <dl class="test-data-item">
          <dd class="xuhao"><div>{{d.xuhao}}</div></dd>
          <dd class="type"><select lay-filter="test-item-type"><option value="radio" {{#if(d.type=='radio'){}}selected{{#}}}>??????</option><option value="checkbox" {{#if(d.type=='checkbox'){}}selected{{#}}}>??????</option> </select></dd>
          <dd class="timu"><textarea>{{d.timu}}</textarea></dd>
          <dd class="tinei"><textarea>{{d.tinei}}</textarea></dd>
          <dd class="act">
            <button type="button" class="layui-btn drag-test-item" style="display: inline-block"><i class="iconfont iconmove"></i></button>
            <button type="button" class="layui-btn remove-item">??????</button>
          </dd>
        </dl>
      </script>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">??????</label>
    <div class="layui-input-block">
      <input type="number" name="weigh" value="<?php echo $row['weigh']; ?>" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">??????</label>
    <div class="layui-input-block">
      <input name="status" type="checkbox" class="layui-form-switch" lay-skin="switch" lay-text="??????|??????" value="1" <?php if($row['status'] == 1): ?>checked<?php endif; ?> />

    </div>

  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button type="button" class="layui-btn" id="form_btn">????????????</button>
      <button type="reset" class="layui-btn layui-btn-normal">??????</button>
    </div>
  </div>
</form>
<script>
  $(".delete").on('click',function(){
    $("#ac").hide();
    $(".img").attr("src","");
    var b=$("#img").val();
    $("#img").val('');
    del_img(b);
  })
  layui.use('form', function(){
    var form = layui.form;

    submit_form_back();

  })
  layui.use(['upload','laytpl','form'], function(){
    var upload = layui.upload
    laytpl = layui.laytpl,
            form = layui.form;

    //????????????
    var uploadInst = upload.render({
      elem: '#addimg' //????????????
      ,url: "<?php echo url('admin/upload/up_do'); ?>" //????????????
      ,done: function(ret){
        $("#ac").show();
        $(".img").attr('src',ret.pic);
        $("#img").val(ret.pic);
      }
      ,error: function(){
        layer.msg('????????????');
      }
    });

    //????????????,????????????
    $("#add-question").click(function () {
      var item_length = $(".test-data-item").length, data = {xuhao:1,type:'',timu:'',tinei:''},dom_;
      if(item_length>0){
        dom_ =  $(".test-data-item").last();
        data.xuhao = item_length + 1;
      }else{
        dom_ = $(".test-data-title");
      }
      let html_ = test_data_itemTpl.innerHTML;
      laytpl(html_).render(data, function(html){
        dom_.after(html);
      });
      form.render();
      $(".test-data-item").first().find("textarea").first().trigger("propertychange");
    })
    //
    $(document).on("click",".test-data-item .remove-item",function () {
      //??????dom???????????????textarea
      var data = $("textarea[name='content']").val()!='' ? JSON.parse($("textarea[name='content']").val()) : [],
              index_ = $(this).closest(".test-data-item").index(".test-data-item"),  dom_;
      $(".test-data-item").remove();
      data.splice(index_,1);

      var new_data = [];
      $.each(data,function (i,t) {
        new_data.push({
          xuhao: i+1,
          type:t.type,
          timu: t.timu,
          tinei: t.tinei
        })
      })
      html_ = test_data_itemTpl.innerHTML;
      layui.each(new_data,function (index,item) {
        dom_ = $(".test-data-item").length>0 ? $(".test-data-item").last() : $(".test-data-title");
        laytpl(html_).render(item, function(html){
          dom_.after(html);
        });
        dom_=null;
      })
      form.render();
      $("textarea[name='content']").val(JSON.stringify(new_data));
    })
    //????????????
    $(document).on("input inputchange propertychange", ".test-data-item textarea, .test-data-item select",function () {
      console.log("????????????");
      var data=[];
      $(".test-data-item").each(function (index,item){
        data.push({
          xuhao:  index+1,
          type: $(item).find(".type select").val(),
          timu: $(item).find(".timu textarea").val(),
          tinei: $(item).find(".tinei textarea").val(),
        })
      });
      $("textarea[name='content']").val(JSON.stringify(data));
    })
    //layui-select ????????????
    form.on('select(test-item-type)',function (data) {
      $(".test-data-item").first().find("textarea").first().trigger("propertychange");
    })
    //??????????????????????????????
    var list_test = document.getElementById("test-setting-box");
    var ops_test = {
      animation: 200,
      handle: ".drag-test-item",
      draggable: ".test-data-item",
      direction: 'vertical',
      forceFallback:true,
      group:'item-box-drag',
      //????????????
      onEnd: function (evt) {
        $(".test-data-item").first().find("textarea").first().trigger("propertychange");
      },
    };
    Sortable.create(list_test,ops_test);
  });
</script>
</body>
</html>