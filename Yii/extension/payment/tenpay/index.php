<?php 
require "./Tenpay.php";
$bank = 'alipay';
$subject = "测试支付宝";
echo Tenpay::init(1234546,10);