<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<script type="text/javascript" src="<?php echo A_SELF_JS_URL; ?>jquery.js"></script>
<script type="text/javascript" src="<?php echo A_SELF_JS_URL; ?>Validform_v5.3.2_min.js"></script>
<link rel="stylesheet" href="<?php echo A_CSS_URL; ?>self.css" type="text/css">
</head>
<body>

<div class="container">
	<div class="LoginPanel">
	    <div class="info_list_f" style="display:none;">
		   <i class="icon"></i>
		   <div class="error_trips"></div>
	    </div>
		 <!--<form class="registerform" action="">-->
                <?php $active = $this->beginWidget('CActiveForm', array('id'=>'login-form' , 'htmlOptions'=>array('class'=>'registerform'))); ?>
		<div class="info_list">
			<div class="tit ">
				<span class="des_c">登录名：</span>
			</div>
			
			<div class="ipt fr">
                            <?php echo $active->textField($form,"name",array('class'=>"reg_ipt ml-80","maxlength"=>18));?>
				  <!--<input type="text"   placeholder="请输入用户名" value="" maxlength="18" name="loginname" class="reg_ipt ml-80"   datatype="s6-18" nullmsg="用户名不能为空" errormsg="用户名至少为6位" >-->
			</div>
		</div>
		
		<div class="info_list">
			<div class="tit fl">
				<span class="des_c">密　码：</span>
			</div>
			
			<div class="ipt fr">
				  <input type="password" placeholder="请输入密码" value="" maxlength="18" name="loginname" class="reg_ipt ml-80" datatype="*6-16" nullmsg="密码不能为空！" errormsg="用户名至少为6位！">
			</div>
		</div>
		
		<div class="info_list btnCol">
			<input type="reset" value="" class="resetBtn">
			<input type="submit" value="" class="loginBtn"></a>
		</div>
		</form>
                <?php $this->endWidget();?>
	</div>

	<script type="text/javascript">
    $(function(){	
	
	$(".registerform").Validform({
		tiptype:function(msg,o,cssctl){
			var objtip=$(".error_trips");
			
			objtip.text(msg);
			$('.info_list_f').show();
		}
	  });
    })
   </script>
	
</div>

</body>
</html>