<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
Yii::app()->clientScript->registerCssFile(CSS_URL."default.css");
Yii::app()->clientScript->registerScriptFile(SELF_JS_URL."handlers.js");
Yii::app()->clientScript->registerScriptFile(SELF_JS_URL."swfupload.js");
Yii::app()->clientScript->registerScriptFile(SELF_JS_URL."swfupload.swf");
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>
<form method="post" action="<?php echo $this->createUrl('site/index');?>">
    <input type="text" name="keyword">
    <input type="submit" value="搜索">
</form>
<?php echo "<hr/>";?>
<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>
<p><input type="button" id="tset"></p>
<p><img src="<?php echo $this->createUrl('async/getVcdoe',array('type'=>'member'));?>" onclick="this.src=this.src+'&id='+Math.random(0,1)">1231</p>

<p>
    <button id="test_btn">测试短信</button>
<form action="<?php echo $this->createUrl('async/checkCode');?>">
    验证码:<input type="text" name="codes" id="sms_code">
    <input type="submit" value="确认验证码" id="subBtn">
</form>
</p>
<?php echo "<hr/>";?>
<form method="post" action="<?php echo $this->createUrl('site/index');?>">
    验证码:<input type="text" name="code">
    <input type="submit" id="sub" value="点击提交">
</form>
<br/>
<span id="spanButtonPlaceholder"></span>
<div id="divFileProgressContainer"></div>
<div id="thumbnails">
    <ul id="pic_list" style="margin: 5px;"></ul>
    <div style="clear: both;"></div>
</div>
<!--多图上传--带删除 插件-->
<script type="text/javascript">
    var swfu;
    var file_queue_limit = 100;//队列1，每次只能上传1个,若是1个以上，上传后的样式是叠加图片
    window.onload = function() {
        swfu = new SWFUpload({
            upload_url: "<?php echo $this->createUrl('global/move');?>",
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: "2 MB", //最大2M
            file_types: "*.jpg;*.png;*.gif;*.bmp", //设置选择文件的类型
            file_types_description: "JPG Images", //描述文件类型
            file_upload_limit: "0", //0代表不受上传个数的限制
            file_queue_limit:file_queue_limit,
            file_queue_error_handler: fileQueueError,
            file_dialog_complete_handler: fileDialogComplete, //当关闭选择框后,做触发的事件
            upload_progress_handler: uploadProgress, //处理上传进度
            upload_error_handler: uploadError, //错误处理事件
            upload_success_handler: uploadSuccess, //上传成功够,所处理的时间
            upload_complete_handler: uploadComplete, //上传结束后,处理的事件
            button_image_url: "<?php echo IMG_URL;?>/uplodify/upload.png",
            button_placeholder_id: "spanButtonPlaceholder",
            button_width: 113,
            button_height: 33,
            button_text: '',
            button_text_style: '.spanButtonPlaceholder { font-family: Helvetica, Arial, sans-serif; font-size: 14pt;} ',
            button_text_top_padding: 0,
            button_text_left_padding: 0,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,
            flash_url: "<?php echo SELF_JS_URL;?>/swfupload.swf",
            custom_settings: {
                upload_target: "divFileProgressContainer"
            },
            debug: false //是否开启日志
        });
    };
</script>
<script type="text/javascript">
    $("#sub").click(function(){
        flag = false;
        $.get(
            "<?php echo $this->createUrl('async/verifyVcode');?>",
            {code:$("input[name=code]").val(),ags:"member"},
            function(data){
                flag = data.code==0 ? true : false;
                if(!flag){
                    alert(data.message);
                }
            }
        );
        //console.log(flag);
        return false;
        return flag==true ? true : false;
    });
</script>

<script type="text/javascript">
    $("#test_btn").click(function(){
        $.post(
            "<?php echo $this->createUrl('async/sendSms');?>",
            //{phone:"18650215426",type:3,test:1},
            {phone:"18650215426",type:3},
            function(data){
                console.log(typeof(data));
            }
        );
    });
    //验证短信 验证码
    $("#subBtn").click(function(){
        var code = $("#sms_code").val();
        $.post(
            "<?php echo $this->createUrl('async/checkCode');?>",
            {sms_code:code,type:3},
            function(data){
                if(data.code!=0){
                    alert(data.message);
                }else{
                    alert("正确");
                    console.log(data);
                }
            }
        );
        return false;
    });
</script>
