<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"F:\Work space\test\public_html/../application/index\view\index\index.html";i:1564737184;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>锦宇app下载地址</title>
    <script src="/static/style/js/jquery-1.8.1.min.js"></script>
    <style>
    	html{
		   overflow-y: hidden;
		   opacity: 0;
		  }
    	body{
    		background: #fff;
    	}
    	* {
			margin: 0;
			padding: 0;
		}
		img{
			border: none;
		}
		.fl{
			float: left;
		}
		.fr{
			float: right;
		}
		/*清除浮动代码*/
		.clearfloat:after{
			display:block;
			clear:both;
			content:"";
			visibility:hidden;
			height:0;
		}
		.clearfloat{
			zoom:1;
		}
		.top{
			width: 3.75rem;
			height: 1.1rem;
			margin: 0 auto;
			display: none;
		}
		.top img{
			width: 3.75rem;
			height: 1.1rem;
		}
		.center{
			width: 3.75rem;
			background: #ffffff;
			margin: 0 auto;
		}
		.cenlogo{
			width: 3.07rem;
			height: 3.86rem;
			background: url('/static/style/images/cen_bg.png') no-repeat bottom center;
			background-size: 100% 100%;
			margin: 0 auto;
			margin-top: 0.3rem;
		}
		.c-lf{
			width: 0.9rem;
			height: 0.9rem;
			margin-left: 0.35rem;
		}
		.c-lf img{
			width: 0.9rem;
			height: 0.9rem;
			border-radius: 0.05rem;
		}
		.c-rg{
			width: 1.7rem;
		}
		.c-rg h4{
			font-size: 0.4rem;
			color: #333;
		}
		.c-rg p{
			font-size: 0.2rem;
			color: #666666;
			font-weight: bold;
			margin-top: 0.05rem;
		}
		.cenbtn{
			margin-top: 0.45rem;
			margin-bottom: 0.65rem;
		}
		.btn{
			width: 2.5rem;
			height: 0.4rem;
			line-height: 0.4rem;
			border-radius: 0.2rem;
			text-align: center;
			margin: 0 auto;
			margin-bottom: 0.2rem;
		}
		.btn img{
			width: 0.2rem;
			height: 0.23rem;
			position: relative;
			top: -0.02rem;
		}
		.btn span{
			font-size: 0.16rem;
			color: #FFFFFF;
			font-weight: bold;
		}
		.btn1{
			background: #78C258;
		}
		.btn2{
			background: #448EC9;
		}
		/*弹窗*/
		.bg_rgba {
			background: rgba(0, 0, 0, 0.5);
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			z-index: 5;
			display: block;
		}
		.tanchuang{
			width: 3.2rem;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
			z-index: 10;
			position: fixed;
			background: #ffffff;
			border-radius: 0.05rem;
			padding: 0.15rem 0 0.25rem 0;
		}
		.int{
			width: 2.5rem;
			height: 0.4rem;
			line-height: 0.4rem;
			background: #f5f5f5;
			margin: 0.15rem auto;
			border-radius: 0.25rem;
			padding-left: 0.15rem;
			padding-right: 0.15rem;
		}
		.int input{
			width: 1.6rem;
			border: none;
			background: none;
			outline: none;
			font-size: 0.14rem;
		}
		.huoqu{
			width: 1rem;
			display: inline-block;
			font-size: 0.14rem;
			color: #333333;
			text-align: right;
		}
		.fle {
			display: flex;
			display: -webkit-box;
			display: -moz-box;
			display: -ms-flexbox;
			display: -webkit-flex;
		}
		.fle_b {
			justify-content: space-between;
			-webkit-box-pack: justify;
			-webkit-justify-content: space-between;
			-moz-box-pack: justify;
			-ms-flex-pack: justify;
		}
		
		.tijiao{
			width: 2.8rem;
			height: 0.4rem;
			line-height: 0.4rem;
			text-align: center;
			background: #78C258;;
			border-radius: 0.25rem;
			margin: 0 auto; 
			font-size: 0.18rem;
			color: #ffffff;
			font-weight: bold;
			margin-top: 0.3rem;
		}
    </style>
   </head>
<body>
	<div class="top">
		<img src="/static/style/images/top.png" />
	</div>
	<div class="center">
		<div class="cenlogo clearfloat">
			 
		</div>
		<div class="cenbtn">
			<div class="btn btn1">
				<img src="/static/style/images/android.png" />
				<span>Android版下载</span>
			</div>
			<div class="btn btn2">
				<img src="/static/style/images/ios.png" />
				<span>IOS版下载</span>
			</div>
		</div>
	</div>
</body>
<script src="/static/style/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/static/manage/layui/layui.all.js"></script>
<link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" />
<script>
var is_send = 0;
setTimeout(function(){
 $('html').css('opacity','1');
 },200)
var isweixin = navigator.userAgent.toLowerCase().indexOf('micromessenger') !== -1; 
 if(isweixin){
   $(".top").show();
   $('html').css('overflow-y','scroll');
   $('.bg_rgba,.tanchuang').hide();
 }else{
	$('html').css('overflow-y','scroll');
	$('.bg_rgba,.tanchuang').hide();
 }
var u = navigator.userAgent;
var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

$(".btn1").on('click',function(){
	if(isAndroid){
		if(isweixin){
			alert("请在浏览器中打开");
		}else{
			window.location.href="<?php echo $android_url; ?>"
		}
	}else{
		alert("必须安卓设备安装哦");
	}
})
$(".btn2").on('click',function(){
	if(isiOS){
		if(isweixin){
			alert("请在浏览器中打开");
		}else{
			window.location.href="<?php echo $ios_url; ?>"
		}
	}else{
		alert("必须苹果设备安装哦");
	}
})
function rem(designWidth, maxWidth) {
	var doc = document,
	win = window,
	docEl = doc.documentElement,
	remStyle = document.createElement("style"),
	tid;

	function refreshRem() {
		var width = docEl.getBoundingClientRect().width;
		maxWidth = maxWidth || 540;
		width>maxWidth && (width=maxWidth);
		var rem = width * 100 / designWidth;
		remStyle.innerHTML = 'html{font-size:' + rem + 'px;}';
	}

	if (docEl.firstElementChild) {
		docEl.firstElementChild.appendChild(remStyle);
	} else {
		var wrap = doc.createElement("div");
		wrap.appendChild(remStyle);
		doc.write(wrap.innerHTML);
		wrap = null;
	}
	refreshRem();

	win.addEventListener("resize", function() {
		clearTimeout(tid);
		tid = setTimeout(refreshRem, 300);
	}, false);

	win.addEventListener("pageshow", function(e) {
		if (e.persisted) {
			clearTimeout(tid);
			tid = setTimeout(refreshRem, 300);
		}
	}, false);

	if (doc.readyState === "complete") {
		doc.body.style.fontSize = "16px";
	} else {
		doc.addEventListener("DOMContentLoaded", function(e) {
			doc.body.style.fontSize = "16px";
		}, false);
	}
}
rem(375, 1024);
</script>
</html>