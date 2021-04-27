<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"F:\Work space\jinrongzixun\public_html/../application/admin\view\xitong\index.html";i:1619418214;}*/ ?>
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
.layui-form{margin: 0 auto;width: 98%;margin-top: 10px;}
.layui-form-pane .layui-input-block{margin-left:160px;width:30%;}
.layui-form-pane .layui-form-label {width:160px;}
.require-field{margin-right:-260px;}
</style>
<form class="layui-form  layui-form-pane" action="<?php echo url("wdl_edit_do"); ?>" method="post" id="form">
	<input name="id" type="hidden" value="<?php echo $data['id']; ?>" />
    <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
	  <ul class="layui-tab-title">
		<li class="layui-this">网站设置</li>
		<li>短信设置</li>
		<li>微信设置</li>
		<li>分享设置</li>
		<li>基金咨询设置</li>
	  </ul>
	  <div class="layui-tab-content" style="min-height: 100px;">
		<div class="layui-tab-item layui-show">
		     <div class="layui-form-item">
			    <label class="layui-form-label">网站名称</label>
			    <div class="layui-input-block">
			      <input type="text" name="name" value="<?php echo $data['name']; ?>" placeholder="请输入网站名称" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">客服电话</label>
			    <div class="layui-input-block">
			      <input type="text" name="phone" value="<?php echo $data['phone']; ?>" placeholder="请输入网站名称" class="layui-input">
			    </div>
			 </div>
			  <div class="layui-form-item">
			    <label class="layui-form-label">系统版权</label>
			    <div class="layui-input-block">
			      <input type="text" name="banquan" value="<?php echo $data['banquan']; ?>" placeholder="请输入系统版权" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
				<label class="layui-form-label">app运行状态</label>
				<div class="layui-input-block">
				  <input type="checkbox" <?php if($data['app'] == 1): ?>checked=""<?php endif; ?> name="app" lay-skin="switch" lay-filter="switchTest" lay-text="运行|关闭">
				  <span class="require-field">此按钮关闭会禁用所有接口使用</span>
				</div>
			  </div>
			 
		      <div class="layui-form-item">
			    <label class="layui-form-label">app关闭原因</label>
			    <div class="layui-input-block">
			      <input type="text" name="app_desc" value="<?php echo $data['app_desc']; ?>" placeholder="请输入app关闭原因" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
				<label class="layui-form-label">注册状态</label>
				<div class="layui-input-block">
				  <input type="checkbox" <?php if($data['reg'] == 1): ?>checked=""<?php endif; ?> name="reg" lay-skin="switch" lay-filter="switchTest" lay-text="开启|关闭">
				  <span class="require-field">此按钮关闭会员注册渠道</span>
				</div>
			  </div>
		</div>


		<div class="layui-tab-item">
		     <div class="layui-form-item">
			    <label class="layui-form-label">互亿无线账号：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="msg_zhanghao" value="<?php echo $data['msg_zhanghao']; ?>" placeholder="请输入互亿无线账号" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">互亿无线秘钥：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="msg_secret" value="<?php echo $data['msg_secret']; ?>" placeholder="请输入互亿无线秘钥" class="layui-input">
			    </div>
			 </div>
			 <div class="layui-form-item">
			    <label class="layui-form-label">发送验证码模板：</label>
			    <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
			      <input type="text" name="msg_code" value="<?php echo $data['msg_code']; ?>" placeholder="请输入发送验证码模板" class="layui-input">
			    </div>
			 </div>
		</div>
		<div class="layui-tab-item">
		  <div class="layui-form-item">
			  <label class="layui-form-label">微信小程序APPID：</label>
			  <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
				  <input type="text" name="wechat_mp_appid" value="<?php echo $data['wechat_mp_appid']; ?>" placeholder="请输入微信小程序APPID" class="layui-input">
			  </div>
		  </div>
		  <div class="layui-form-item">
			  <label class="layui-form-label">微信小程序SECRET：</label>
			  <div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
				  <input type="text" name="wechat_mp_secret" value="<?php echo $data['wechat_mp_secret']; ?>" placeholder="请输入微信小程序APPSECRET" class="layui-input">
			  </div>
		  </div>
		  <div class="layui-form-item">
			<label class="layui-form-label">微信支付APPID：</label>
			<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
				<input type="text" name="wechat_pay_appid" value="<?php echo $data['wechat_pay_appid']; ?>" placeholder="请输入微信支付商户号" class="layui-input">
			</div>
		  </div>
		  <div class="layui-form-item">
			<label class="layui-form-label">微信支付商户号：</label>
			<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
				<input type="text" name="wechat_pay_merchant" value="<?php echo $data['wechat_pay_merchant']; ?>" placeholder="请输入微信支付商户号" class="layui-input">
			</div>
		  </div>
		  <div class="layui-form-item">
			<label class="layui-form-label">微信支密钥：</label>
			<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
				<input type="text" name="wechat_pay_key" value="<?php echo $data['wechat_pay_key']; ?>" placeholder="请输入微信支付密钥" class="layui-input">
			</div>
		 </div>
		</div>
		<div class="layui-tab-item" style="height: 380px;">
			<div class="layui-form-item">
				<label class="layui-form-label">分享规则简述：</label>
				<div class="layui-input-block" style="min-width: 500px;">
					<textarea style="display: none;" id="share_rule_des" name="share_rule_des"><?php echo $data['share_rule_des']; ?></textarea>
				</div>
			</div>
		</div>
		<div class="layui-tab-item">
			<div class="layui-form-item">
				<label class="layui-form-label">基金服务价格标准：</label>
				<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
					<select name="jijin_switch" lay-filter="jijin_switch">
						<option value="percent" <?php if($data['jijin_setting']['jijin_switch'] == 'perent'): ?>selected<?php endif; ?>>百分比</option>
						<option value="ladder" <?php if($data['jijin_setting']['jijin_switch'] == 'ladder'): ?>selected<?php endif; ?>>阶梯收费</option>
					</select>
				</div>
			</div>
			<div class="layui-form-item for-percent <?php if($data['jijin_setting']['jijin_switch'] == 'ladder'): ?>layui-hide<?php endif; ?>">
				<label class="layui-form-label">基金收费比例：</label>
				<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
					<input type="text" name="jijin_fee_percent" value="<?php echo $data['jijin_setting']['jijin_fee_percent']; ?>" placeholder="请输入仅仅收费比例" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item for-ladder <?php if($data['jijin_setting']['jijin_switch'] == 'percent'): ?>layui-hide<?php endif; ?>">
				<label class="layui-form-label">阶梯[0-1W]：</label>
				<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
					<input type="number" name="jijin_fee_ladder1" value="<?php echo $data['jijin_setting']['jijin_fee_ladder1']; ?>" placeholder="请输入阶梯收费标准" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item for-ladder <?php if($data['jijin_setting']['jijin_switch'] == 'percent'): ?>layui-hide<?php endif; ?>">
				<label class="layui-form-label">阶梯[1W-3W]：</label>
				<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
					<input type="number" name="jijin_fee_ladder2" value="<?php echo $data['jijin_setting']['jijin_fee_ladder2']; ?>" placeholder="请输入阶梯收费标准" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item for-ladder <?php if($data['jijin_setting']['jijin_switch'] == 'percent'): ?>layui-hide<?php endif; ?>">
				<label class="layui-form-label">阶梯[3W-5W]：</label>
				<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
					<input type="number" name="jijin_fee_ladder3" value="<?php echo $data['jijin_setting']['jijin_fee_ladder3']; ?>" placeholder="请输入阶梯收费标准" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item for-ladder <?php if($data['jijin_setting']['jijin_switch'] == 'percent'): ?>layui-hide<?php endif; ?>">
				<label class="layui-form-label">阶梯[5W-10W]：</label>
				<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
					<input type="number" name="jijin_fee_ladder4" value="<?php echo $data['jijin_setting']['jijin_fee_ladder4']; ?>" placeholder="请输入阶梯收费标准" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item for-ladder <?php if($data['jijin_setting']['jijin_switch'] == 'percent'): ?>layui-hide<?php endif; ?>">
				<label class="layui-form-label">阶梯[10W-20W]：</label>
				<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
					<input type="number" name="jijin_fee_ladder5" value="<?php echo $data['jijin_setting']['jijin_fee_ladder5']; ?>" placeholder="请输入阶梯收费标准" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item for-ladder <?php if($data['jijin_setting']['jijin_switch'] == 'percent'): ?>layui-hide<?php endif; ?>">
				<label class="layui-form-label">阶梯[20W-50W]：</label>
				<div class="layui-input-block"><span class="require-field">请勿擅自更改</span>
					<input type="number" name="jijin_fee_ladder6" value="<?php echo $data['jijin_setting']['jijin_fee_ladder6']; ?>" placeholder="请输入阶梯收费标准" class="layui-input">
				</div>
			</div>
		</div>
	  </div>
	</div> 
    <div class="layui-form-item" style="padding-top:30px;">
		<div class="layui-input-block">
		  <button type="button" class="layui-btn"  lay-filter="formDemo" id="form_btn">立即提交</button>
		  <button type="reset" class="layui-btn layui-btn-primary">重置</button>
		</div>
  </div>
</form>
<script>
layui.use(['element','form','layedit'], function(){
  var $ = layui.jquery,element = layui.element;
  var form = layui.form ,layedit=layui.layedit;

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
		layedit.sync(layeditor);
		$("#form").ajaxSubmit(function(txt){
			if(txt.code==1){
				layer.msg(txt.msg);
				reload();
			}else{
				layer.alert(txt.msg,{icon: 2,title:'提示'});
            	return false;
			}
		});
	})
	form.on('select(jijin_switch)',function (data){
		console.log("出发更改");
		if(data.value == 'percent'){
			$('.for-percent').removeClass("layui-hide");
			$('.for-ladder').addClass("layui-hide");
		}else if(data.value == 'ladder'){
			$('.for-percent').addClass("layui-hide");
			$('.for-ladder').removeClass("layui-hide");
		}
	});
	var layeditor = layedit.build('share_rule_des', {
		height: 300,
		uploadImage: {
			url: "<?php echo url('admin/upload/up_for_edit'); ?>",
			type: 'post'
		}
	});


});

</script>
</body>
</html>
