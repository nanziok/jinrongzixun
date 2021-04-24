// document.onkeydown = function(e){
//         var ev = document.all ? window.event : e;
//         if(ev.keyCode==13) {
//            return false;
//         }
//  }
//设置数据
function set_data(data){
      $.ajax({
            url: "/manage.php/xitong/wdl_setdata",
            data: data,
            success: function (txt) {
                 if(txt.code == 1){
					layer.msg(txt.msg);
				 }else{
					layer.alert(txt.msg,{icon: 2});
				 }
            }
        })
}
//打开新窗口
function open_window(id,url,title,height,width='600px'){
	layer.open({
	  type: 2,
	  title: title,
	  shadeClose: true,
	  shade: 0.6,
	  anim: 0,
	  scrollbar:false,
	  area: [width, height],
	  content: url+"?id="+id //iframe的url
	});
}
//提交页面
function submit_form(){
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
}
//提交页面
function submit_form_back(){
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
		$("#form").ajaxSubmit(function(txt){
			if(txt.code==1){
				layer.msg(txt.msg);
				setTimeout(function(){
				 	window.location.href = document.referrer; 
				},500)
			}else{
				layer.alert(txt.msg,{icon: 2,title:'提示'});
            	return false;
			}
		});
	})
}
//刷新当前页面
function editdo(){
    layer.closeAll();
	table.reload('demo', {});
}
//删除单个数据
function del(id,url){
	if(id == 0 || id == null){
		layer.alert("请选择需要操作的信息",{icon: 2});
		return false;
	}
	layer.confirm('确定要删除选中项吗?', {icon: 3, title:'提示'}, function(index){
		 if(index){
			$.ajax({
				url: url,
				data: "id=" + id,
				type: 'post',
				success: function (txt) {
					if(txt.code == 1){
						layer.msg(txt.msg);
						editdo();
					}else{
						layer.alert(txt.msg,{icon: 2});
						return false;
					}
				
				}
			})
		 }
	});
}
//删除多个数据数据进行重载
function del_data(data,url){
		var arr = new Array();
		if(data.length == 0){
	    	layer.alert("请选择需要操作的信息",{icon: 2});
		    return false;
	    }
		$.each(data,function(k,v){
		    arr.push(v.id);
		})
        layer.confirm('确定要删除选中项吗?', {icon: 3, title:'提示'}, function(index){
             if(index){
		        $.ajax({
		            url: url,
		            data: "id=" +arr ,
		            type: 'post',
			        success: function (txt) {
		            	if(txt.code == 1){
		            		layer.msg(txt.msg);
							editdo(); 
		            	}else{
		            		layer.alert(txt.msg,{icon: 2});
		            		return false;
		            	}
		            
			        }
		        })
             }
        });
}
function openurl(url){
	window.location.href=url;
}
//刷新当前页面
function reload(){
  window.location.reload();
}
function clear_cache(){
	  var tc = layer.load(0, {shade: [0.3,'#fff'],shadeClose : false});
	  $.ajax({
            url: '/manage.php/xitong/wdl_clear_cache',
            success: function (txt) {
            	layer.msg(txt.msg);
                layer.close(tc);
            }
        })
}

 function showImg(id,start,table,zid,field) {
        $.ajax({
            type:"post",
            url:'/admin/index/wdl_get_img',
            data:{
                id:id,
                start:start,
                table:table,
				zid:zid,
				field:field
            },
            async:true,
            success:function(ret){
                var json = JSON.parse(ret);
                layer.photos({
                    photos: json //格式见API文档手册页
                    ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机
                });
            }
        });

 }
