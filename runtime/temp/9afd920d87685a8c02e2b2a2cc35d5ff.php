<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"F:\Work space\test\public_html/../application/admin\view\site\main.html";i:1617766716;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title></title>
  <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_1426607_s0o29n5r67d.css" />
  <link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" />
  <script type="text/javascript" src="/static/manage/layui/layui.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/manage/css/style.css" />
  <script type="text/javascript" src="/static/manage/js/public.js"></script>
  <script type="text/javascript" src="/static/manage/js/jquery-1.8.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/manage/css/index.css" />
  <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
</head>
<style>
	#container1{width: 49%;float: left;margin-top: 2%;}
	#container2{width: 49%;float:right;border-left:1px dashed #E1E1E1;margin-top: 2%;}
	.box li{cursor: pointer;}
</style>
<body>
<div class="box">
		<div class="center1 clearfloat">
			<!--快捷方式-->
			<div class="left1 fl">
				<div class="title">
					<img src="/static/manage/images/index/list1.png">
					<span>快捷方式</span>
				</div>
				<ul class="ul_list clearfloat">
					<li onclick="openurl('<?php echo url('admin/service/index'); ?>');">
						<img src="/static/manage/images/index/fuwudian.png">
						<div>服务点管理</div></a>
					</li>
					<li onclick="openurl('<?php echo url('admin/user/fabu'); ?>');">
						<img src="/static/manage/images/index/fabu.png">
						<div>会员发布</div>
					</li>
					<li onclick="openurl('<?php echo url('admin/anli/index'); ?>');">
						<img src="/static/manage/images/index/anli.png">
						<div>案例管理</div>
					</li>
					<li onclick="openurl('<?php echo url('admin/user/index'); ?>');">
						<img src="/static/manage/images/index/icon4.png">
						<div>会员管理</div>
					</li>
					<li onclick="openurl('<?php echo url('admin/guanliyuan/index'); ?>');">
						<img src="/static/manage/images/index/guanliyuan.png">
						<div>管理员管理</div>
					</li>
				</ul>
			</div>
			<!--待办事项-->
			<div class="right1 fr">
				<div class="title">
					<img src="/static/manage/images/index/list2.png">
					<span>待办事项</span>
				</div>
				<ul class="ul_right clearfloat">
					<li onclick="openurl('<?php echo url('admin/user/fabu'); ?>?start_date=<?php echo $begintime; ?>&end_date=<?php echo $endtime; ?>');">
						<div class="div_1">今日发布</div>
						<div class="div_2"><?php echo $num1; ?></div>
					</li>
					<li onclick="openurl('<?php echo url('admin/user/index'); ?>?start_date=<?php echo $begintime; ?>&end_date=<?php echo $endtime; ?>');">
						<div class="div_1">今日注册</div>
						<div class="div_2"><?php echo $num2; ?></div>
					</li>
					<li onclick="openurl('<?php echo url('admin/tousu/index'); ?>?status=1');">
						<div class="div_1">投诉待处理</div>
						<div class="div_2"><?php echo $num3; ?></div>
					</li>
				</ul>
			</div>
		</div>
		<!--数据概览-->
		<div class="center2">
			<div class="title">
				<img src="/static/manage/images/index/list3.png">
				<span>数据概览</span>
			</div>
			<div class="data clearfloat">
				<div class="datacen fl" id="container1">
				</div>
				<div class="datacen fr" id="container2">
				</div>
			</div>
		</div>
		<!--系统信息-->
		<div class="center2">
			<div class="title">
				<img src="/static/manage/images/index/list4.png">
				<span>系统信息</span>
			</div>
			<div class="xitong clearfloat">
				<div class="xtcen fl">
					<div class="xtlist">
						<span>操作系统：</span>
						<span><?php echo $server['os']; ?></span>
                    </div>
                    <div class="xtlist">
						<span>系统版本：</span>
						<span><?php echo $server['os_version']; ?></span>
                    </div>
                    <div class="xtlist">
						<span>PHP版本：</span>
						<span><?php echo $server['php_version']; ?></span>
                    </div>                  
				</div>
				
				<div class="xtcen fl">
					<div class="xtlist">
						<span>网站域名：</span>
						<span><?php echo $server['host']; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" target="_blank"  class="layui-btn layui-btn-danger">app下载地址</a>
                    </div>
                    <div class="xtlist">
						<span>网站IP：：</span>
						<span><?php echo $server['ip']; ?></span>
                    </div>
                        <div class="xtlist">
						<span>服务器类型：</span>
						<span><?php echo $server['apache_version']; ?></span>
                    </div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	
    var data1 = <?php echo $data1; ?>;
	var data2 = <?php echo $data2; ?>;
    var t1 = '<?php echo $year; ?>年各月会员注册量';
    var t2 ='<?php echo $year; ?>年各月会员发布量';
	Highcharts.setOptions({ 
        colors: ['#058DC7', '#5cb85c'] 
	 }); 
     setdata('container1',t1,'数据来源：万动力','人',data1);
	 setdata('container2',t2,'数据来源：万动力','单',data2);

     function setdata(dv,title,laiyuan,danwei,data){
	 
	  Highcharts.chart(dv, {
				chart: {
					type: 'spline'
				},
				title: {
					text: title
				},
				subtitle: {
					text: laiyuan
				},
				xAxis: {
					categories: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
				},
				yAxis: {
					title: {
						text: danwei
					}
				},
				exporting:{
				   enabled:false
				},
				credits: {
					enabled: false
				},
				plotOptions: {
					line: {
						dataLabels: {
							// 开启数据标签
							enabled: true          
						},
						// 关闭鼠标跟踪，对应的提示框、点击事件会失效
						enableMouseTracking: false
					}
				},
				series:data
		 });
	 
	 }

	
</script>
</html>