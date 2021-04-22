    $(function () {
        $("#form_btn").click(function () {
			if($("#name").val()==""){
			     layer.msg('请输入登录账号');
				 return false;
			}
			if($("#pwd").val()==""){
			     layer.msg('请输入登录账号');
				 return false;
			}
			if($("#captcha").val()==""){
			     layer.msg('请输入验证码');
				 return false;
			}
            $("#form").ajaxSubmit(function (txt) {
                if (txt.code == 1) {
                    window.location.href = txt.url;
                } else {
                    layer.alert(txt.msg,{icon:2,title:'提示'});
					return false;
                }
            });
        })
    })
