<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"F:\Work space\jinrongzixun\public_html/../application/admin\view\order\wdl_edit_log.html";i:1619510805;}*/ ?>
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
    .test-item{
        font-weight: 700;
        line-height: 38px;
    }
</style>
<form class="layui-form" action="<?php echo url('wdl_cat_add_do'); ?>" id="form" >
    <input name="id" type="hidden" value="<?php echo $row['id']; ?>" />
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md6">
            <label class="layui-form-label">答卷人</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" value="<?php echo $row['user_text']; ?>">
            </div>
        </div>
        <div class="layui-col-md6">
            <label class="layui-form-label">咨询师</label>
            <div class="layui-input-block">
                <input type="text" placeholder="咨询师" class="layui-input" value="<?php echo $row['service_text']; ?>">
            </div>
        </div>
    </div>
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md6">
            <div id="log-content-box" style="min-width: 200px;height: 400px;overflow-y:auto;overflow-x: hidden">
                <div class="layui-form-item">
                    <label class="layui-form-label">问卷信息：</label>
                    <div class="layui-input-block">

                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md6">
            <textarea class="layui-textarea" name="notes" id="log_notes"></textarea>
        </div>
        <script type="text/html" id="test_log_content_tpl">
            {{#
            var isChecked=function(item,list){
                return list.includes(String(item)) ? "checked" : "";
            }
            }}
            <div class="layui-form-item">
                <div class="layui-input-block">
            {{#  var temp_data=[], temp_key;  }}
            {{#  temp_data=d.tinei;  }}
            {{#  if(d.type == 'radio'){  }}
            <p class="test-item">{{d.timu}}【单选题】</p>
            {{#  $.each(temp_data, function(index,item){  }}
            <input class="layui-input" type="radio" value="{{index}}" title="{{item}}" {{isChecked(index,d.answer)}} />
            {{#  });  }}
            {{#  }else if(d.type == 'checkbox'){  }}
            <p class="test-item">{{d.timu}}【多选题】</p>
            {{#  $.each(temp_data, function(index,item){  }}
            <input class="layui-input" type="checkbox" value="{{index}}" title="{{item}}" lay-skin="primary" {{isChecked(index,d.answer)}} />
            {{#  });  }}
            {{#  }  }}
                </div>
            </div>
        </script>
    </div>

    <div class="layui-form-item" style="margin-top: 20px;margin-left: 50%;">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="form_btn">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-normal">重置</button>
        </div>
    </div>

</form>
<script>
    layui.use(['form','layedit','laytpl'], function(){
        var form = layui.form
        ,layedit=layui.layedit
        ,laytpl=layui.laytpl;
        $("#form_btn").click(function(){
            var leg = $(".must").length;
            for(var a=0;a<leg;a++){
                var val=$(".must").eq(a).val();
                if(val == "" || val == 0){
                    var msg = $(".must").eq(a).attr('msg');
                    if(!msg){
                        msg = $(".must").eq(a).attr('placeholder');
                    }
                    layer.alert(msg,{icon: 2,title:'提示'});
                    return false;
                }
            }
            layedit.sync(editIndex);
            $("#form").ajaxSubmit(function(txt){
                if(txt.code==1){
                    layer.msg(txt.msg);
                    setTimeout(function(){
                        window.parent.editdo();
                    },500)
                }else{
                    layer.alert(txt.msg,{icon: 2,title:'提示'});
                    return false;
                }
            });
        })

        //创建一个编辑器
        var editIndex = layedit.build('log_notes',{
            height: 400,
            uploadImage: {
                url: "<?php echo url('admin/upload/up_for_edit'); ?>",
                type: 'post'
            }
        });
        var test_log_content = <?php echo $row['content']; ?>, html_=test_log_content_tpl.innerHTML, dom_=$("#log-content-box");
        test_log_content = test_log_content ?? [];
        console.log("数据是", test_log_content);
        layui.each(test_log_content,function (index, item){
            item.tinei = item.tinei!='' ? item.tinei.split(/[\n|\r\n]/) : [];
            item.answer = item.answer ? item.answer.split(",") : []
            console.log(11111,item);
            laytpl(html_).render(item,function (html) {

                dom_.append(html);
            })
        });
        form.render();

    })
</script>
</body>
</html>