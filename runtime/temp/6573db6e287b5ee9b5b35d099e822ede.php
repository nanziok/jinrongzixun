<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"F:\Work space\test\public_html/../application/admin\view\index\main.html";i:1584062527;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title><?php echo $site['name']; ?></title>
  <script type="text/javascript" src="/static/manage/js/jquery-1.8.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" />
  <script type="text/javascript" src="/static/manage/layui/layui.js"></script>
  <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_1426607_14iihsd2hr8.css" />
  <link rel="stylesheet" type="text/css" href="/static/manage/css/style.css" />
  <script type="text/javascript" src="/static/manage/js/public.js"></script>
</head>
<style>
body {
  background-color: #fff;
  background-image:url(/static/manage/images/login/beijing.jpg);
  background-size: cover;
  background-repeat: no-repeat;
  background-attachment: fixed;
  color: #111;
  text-align: center;
  width: 100vw;
  font-weight: 700;
  overflow: hidden;
  font-family: 'Montserrat', sans-serif;
}

#fly-in {
  font-size: 2.6rem;
  margin: 40vh auto;
  height: 20vh; 
  text-transform: uppercase;
}

#fly-in span {
  display: block;
  font-size: .4em;
  opacity: .8;
}

#fly-in div {
  position: absolute;
  margin: 2vh 0;
  opacity: 1;
  left: 10vw;
  width: 80vw;
  animation: switch 16s linear infinite;
}

/* #fly-in div:nth-child(2) { animation-delay: 4s}
#fly-in div:nth-child(3) { animation-delay: 8s}
#fly-in div:nth-child(4) { animation-delay: 12s}



@keyframes switch {
    0% { opacity: 0;filter: blur(20px); transform:scale(12)}
    3% { opacity: 1;filter: blur(0); transform:scale(1)}
    10% { opacity: 1;filter: blur(0); transform:scale(.9)}
    30% { opacity: 0;filter: blur(10px); transform:scale(.1)}
    80% { opacity: 0}
    100% { opacity: 0}
} */
</style>
<body class="layui-layout-body">
<div id="fly-in">  
	<div><?php echo $site['name']; ?>后台系统欢迎您的使用</div>
	<!-- <div>欢迎您的使用<span>万动力</span></div>
	<div>万动力最新系统即将上线</div>
	<div>欢迎您的使用<span>万动力</span></div> -->
	

</div>
</body>
</html>