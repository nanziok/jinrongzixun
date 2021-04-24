<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"F:\Work space\jinrongzixun\public_html/../application/admin\view\login\index.html";i:1590805977;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $site['name']; ?> - 登录</title>
<link rel="stylesheet" type="text/css" href="/static/manage/css/style.css" />
<link rel="stylesheet" type="text/css" href="/static/manage/css/login.css" />
<script type="text/javascript" src="/static/manage/js/jquery.js"></script>
<script type="text/javascript" src="/static/manage/js/jquery.form.js"></script>
<script type="text/javascript" src="/static/manage/js/common.js"></script>
<link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" />
<script type="text/javascript" src="/static/manage/layui/layui.js"></script>

<link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_885314_ariofai537a.css" />
</head>
<body>
<div class="subject">
		<div class="subject-middle">
			<div class="subject-middle-left">后台管理系统</div>
			<div class="subject-middle-right">
				<div class="title">账号登录</div>
				<form action="<?php echo url('login_do'); ?>" method="post" id="form">

				<div class="uname">
					<input type="text"  id="name" name="name" placeholder="请输入登录账号" <?php if($remember == 1): ?>value="<?php echo $name; ?>"<?php endif; ?>>
				</div>
				<div class="upwd">
					<input type="password" name="password" id="pwd" placeholder="请输入登录密码"><i class="iconfont icon-yanjing see" style="position: relative;left: -30px;cursor: pointer;top:4px;"></i>
				</div>
				<div class="login-immediately">
					<a href="javascript:;" id="form_btn">立即登录</a>
				</div>
				</form>
			</div>
		</div>
	</div>
<script>
	layui.use(['element','layer'], function(){
	  var element = layui.element;
	});
	$(".see").click(function(){
		if($(this).hasClass('icon-yanjing')){
			$(this).addClass('icon-ai-eye').removeClass('icon-yanjing');
			$("#pwd")[0].setAttribute('type','text');
		}else{
			$(this).addClass('icon-yanjing').removeClass('icon-ai-eye');
			$("#pwd")[0].setAttribute("type","password");
		}
	})
    function refreshVerify() {
        var ts = Date.parse(new Date()) / 1000;
        var img = document.getElementById('verify_img');
        img.src = "/captcha?id=" + ts;
    }


</script>

</body>
</html>
