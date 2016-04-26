<?php 
require "./Alipay.php";
$bank = 'alipay';
$subject = "测试支付宝";
echo Alipay::init(array(
	'out_trade_no'	=> 1234546,
	'total_fee'		=> 10,
	'bank'			=> $bank,
	'subject'		=> $subject,
));