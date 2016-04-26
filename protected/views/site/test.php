<div id="test">1231</div>
<script type="text/javascript">
    $("#test").click(function(){
	$.post(
	    "<?php echo $this->createUrl('site/index');?>",
	    function(data){
		console.log(data);
	    }
	);
    });
</script>