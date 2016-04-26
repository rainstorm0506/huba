<?php
header("Content-Type:text/html;charset=utf-8");
// change the following paths if necessary
$root = dirname(__FILE__);
$yii=dirname(__FILE__).'/../Yii/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

require_once($root."/protected/config/constant.php");
require_once($root . '/../Yii/yii.php');
Yii::createWebApplication($config)->run();
