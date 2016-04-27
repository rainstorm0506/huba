<?php
header("Content-Type:text/html;charset=utf-8");
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);       
// change the following paths if necessary
$root = dirname(__FILE__);
$yii=dirname(__FILE__).'/../Yii/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

require_once($root."/protected/config/constant.php");
require_once($yii);
Yii::createWebApplication($config)->run();