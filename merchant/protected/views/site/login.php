<!DOCTYPE html>
<html>
    <head>
        <title><?php echo CHtml::encode($this->pageTitle);?></title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <script type="text/javascript" src="<?php echo M_SELF_JS_URL; ?>jquery.js"></script>
        <script type="text/javascript" src="<?php echo M_SELF_JS_URL; ?>jquery.yiiactiveform.js"></script>
        <link rel="stylesheet" href="<?php echo M_CSS_URL; ?>self.css" type="text/css">
        <style type="text/css">
            .info_list{
                width: 350px;
            }
        </style>
    </head>
    <body>

        <div class="container">
            <div class="LoginPanel">
                <div class="info_list_f" style="display:none;">
                    <i class="icon"></i>
                    <div class="error_trips"></div>
                </div>
                <!--<form class="registerform" action="">-->
                    <?php $form = $this->beginWidget('CActiveForm', 
                        array(
                            'id'=>'login_form',
                            'method'=>'post',
                            'enableClientValidation'=>true,
                            'clientOptions'=>array(
                                'validateOnSubmit'=>true,   //当 提交的时候进行验证
                            )  
                        ),
                        array('class'=>'registerform')
                    ); ?>                    
                    <div class="info_list">
                        <div class="tit ">
                            <span class="des_c">登录名：</span>
                        </div>

                        <div class="ipt fr">
                            <?php echo $form->textField($model,"name",array('class'=>'reg_ipt ml-80','maxlength'=>18,'placeholder'=>'请输入用户名'));?>
                            <b><?php echo $form->error($model,"name");?></b>
                        </div>
                    </div>

                    <div class="info_list">
                        <div class="tit fl">
                            <span class="des_c">密　码：</span>
                        </div>

                        <div class="ipt fr">
                            <span>
                                <?php echo $form->passwordField($model,"pwd",array('class'=>"reg_ipt ml-80",'maxlength'=>18,'placeholder'=>'请输入密码'));?>
                                <b><?php echo $form->error($model,'pwd');?></b>
                            </span>
                        </div>
                    </div>

                    <div class="info_list">
                        <div class="tit fl">
                            <span class="des_c">验 证 码：</span>
                        </div>

                        <div class="ipt fr">
                            <p>
                                <?php echo $form->textField($model,"codes",array('class'=>"reg_ipt ml-80",'maxlength'=>18,'id'=>'codes','placeholder'=>'请输入验证码'));?>
                                <b><?php echo $form->error($model,'codes');?></b>
                            </p>
                            <p style="display: inline-block;float: left;"><img src="<?php echo $this->createUrl('masync/getVcode',array('type'=>'admin'));?>" onclick="this.src=this.src+'&id='+Math.random(0,1)" style="cursor: pointer; "></p>
                        </div>    
                    </div>
                                          
                    <div class="info_list btnCol">
                        <input type="reset" value="" class="resetBtn">
                        <input type="submit" value="" class="loginBtn" name="submit">
                    </div>
                <!--</form>-->
                <?php $this->endWidget(); ?>
            </div>

            <script type="text/javascript">
                $(function(){
                    var flag = false;
                    $("#codes").blur(function(){
                        $.get(
                                "<?php echo $this->createUrl('masync/verifyVcode');?>",
                                {code:$("#codes").val(),ags:"merchant"},
                                function(data){
                                    flag = data.code==0 ? true : false;
                                }                                
                        );                        
                    });
                    
                    $(".loginBtn").click(function(){
                        if(!flag){
                            alert("验证码必须正确填写!");
                            return false;
                        }
                    });                    
                });
            </script>

        </div>

    </body>
</html>