<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"F:\Work space\jinrongzixun\public_html/../application/admin\view\index\index.html";i:1618990218;s:66:"F:\Work space\jinrongzixun\application\admin\view\common\head.html";i:1583287480;s:66:"F:\Work space\jinrongzixun\application\admin\view\common\menu.html";i:1571102864;s:66:"F:\Work space\jinrongzixun\application\admin\view\common\foot.html";i:1570519708;}*/ ?>
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
  <link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_2502109_ws6vr90kaj9.css" />
  <link rel="stylesheet" type="text/css" href="/static/manage/css/style.css" />
  <script type="text/javascript" src="/static/manage/js/public.js"></script>

</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
   <div class="layui-header">
    <div class="layui-logo" style="background: #393D49;border-bottom: 1px solid #393D49;"><?php echo $site['name']; ?></div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left daohang">
	  <li class="layui-nav-item"><a href="<?php echo url('index/main'); ?>"   target="main" class="layui-icon iconfont iconshouye"></a></li>
	  <li class="layui-nav-item"><a href="javascript:void(0);" onclick="open_menu();" class="layui-icon layui-icon-spread-left"></a></li>
      <li class="layui-nav-item"><a href="javascript:void(0);" onclick="clear_cache();" class="layui-icon layui-icon-fonts-clear"></a></li>
    </ul>
    <ul class="layui-nav layui-layout-right daohang">
      <li class="layui-nav-item">
        <a href="javascript:;">您好,<?php echo $admin['name']; ?></a>
      </li>
      <li class="layui-nav-item"><a href="<?php echo url("login/out"); ?>"  class="iconfont icontuichu" title="退出登录"></a></li>
    </ul>
  </div>
  <script>
    var is_open = 0 
    function open_menu(){
	if(is_open == 1){
	   $("#caidan .layui-nav-item").removeClass("layui-nav-itemed");
	   is_open = 0;
	}else{
	   $("#caidan .layui-nav-item").addClass("layui-nav-itemed");
	   is_open = 1;
	}
	
	}
  </script> 
   <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree"  lay-filter="test" id="caidan">
          <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<li class="layui-nav-item">
			  <a <?php if($vo['son']): ?>href="javascript:;"<?php else: ?> href="<?php echo url($vo['m'].'/'.$vo['a']); ?>" target="main"<?php endif; ?>><i class="iconfont <?php echo $vo['icon']; ?>"></i><?php echo $vo['name']; ?></a>
			  <?php if($vo['son']): ?>
                  <dl class="layui-nav-child">
					<?php if(is_array($vo['son']) || $vo['son'] instanceof \think\Collection || $vo['son'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
					<dd><a  href="<?php echo url($vo2['m'].'/'.$vo2['a']); ?>" target="main"><?php echo $vo2['name']; ?></a></dd>
					 <?php endforeach; endif; else: echo "" ;endif; ?>
				  </dl>
			  <?php endif; ?>
			</li>
          <?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </div>
  </div> 
 <div class="layui-body">
  <iframe src="<?php echo url('index/main'); ?>" name="main" width="100%" height="100%" style="border:none;"></iframe>
 </div>
 <div class="layui-footer">
<!-- 底部固定区域 -->
© <?php echo $site['banquan']; ?>  版权所有 
</div>
</div>
<script>
layui.use(['element','layer'], function(){
  var element = layui.element;
});

</script>
</body>
</html>