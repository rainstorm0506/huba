<!DOCTYPE html>
<html>
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <title><?php echo CHtml::encode($this->pageTitle);?></title>
    <style type="text/css">
    </style>
</head>
<frameset rows="150,*" cols="*" frameborder="no" border="0" framespacing="0">
    <frame src="<?php echo $this->createUrl('home/top');?>" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="topFrame" />
    <frameset cols="187,*" frameborder="no" border="0" framespacing="0">
        <frame src="<?php echo $this->createUrl('home/left');?>" name="left" scrolling="No" noresize="noresize" id="leftFrame" title="leftFrame" />
        <frame src="<?php echo $this->createUrl('home/main');?>" name="mainFrame" id="mainFrame" title="mainFrame" />
    </frameset>
</frameset>
<noframes><body>

    </body>
</noframes>
</html>