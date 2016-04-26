<?php
class Tenpay
{
	private static $config = array();
	
	public static function getConfig()
	{
		self::$config = empty(self::$config) ? require(dirname(__FILE__).'/config.php') : self::$config;
		return self::$config;
	}
	
	public static function init($orderSN , $orderMoney , $goodsName = '')
	{
		$config = self::getConfig();
		
		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($config['key']);
		$reqHandler->setGateUrl('https://gw.tenpay.com/gateway/pay.htm');
		$reqHandler->setParameter('partner'				, $config['partner']);
		$reqHandler->setParameter('out_trade_no'		, $orderSN);
		$reqHandler->setParameter('total_fee'			, intval($orderMoney*100));  //总金额
		$reqHandler->setParameter('return_url'			, 'http:://www.ebangon.com/pay/tenpayReturn');	//同步回调地址
		$reqHandler->setParameter('notify_url'			,  'http:://www.ebangon.com/pay/tenpayNotify');		//异步回调地址
		$reqHandler->setParameter('body'				, "订单号：{$orderSN}");
		$reqHandler->setParameter('bank_type'			, 'DEFAULT');//银行类型，默认为财付通
		$reqHandler->setParameter('spbill_create_ip'	, empty($_SERVER['REMOTE_ADDR'])?'':$_SERVER['REMOTE_ADDR']);//客户端IP
		$reqHandler->setParameter('fee_type'			, '1');//币种
		$reqHandler->setParameter('subject'				, $goodsName?$goodsName:$orderSN);//商品名称，（中介交易时必填）
		$reqHandler->setParameter('sign_type'			, 'MD5');//签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter('service_version'		, '1.0');//接口版本号
		$reqHandler->setParameter('input_charset'		, 'utf-8');//字符集
		$reqHandler->setParameter('sign_key_index'		, '1');//密钥序号
		$reqHandler->setParameter('time_start'			, date('YmdHis'));//订单生成时间
		$reqHandler->setParameter('trade_mode'			, '1');//交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		$reqHandler->setParameter('trans_type'			, '1');//交易类型
		$reqHandler->getRequestURL();

		return self::buildRequestForm($reqHandler->getGateUrl() , $reqHandler->getAllParameters());
	}

	private static function buildRequestForm($url , array $params)
	{
		$sHtml = "<form id='paySubmit' name='paySubmit' action='{$url}' method='post'>";
		foreach($params as $k => $v)
			$sHtml .= "<input type='hidden' name='{$k}' value='{$v}' />";
		$sHtml .= "</form>";
		return $sHtml. "<script>document.forms['paySubmit'].submit();</script>";
	}
}

class RequestHandler
{

	var $gateUrl;
	var $key;
	var $parameters;
	var $debugInfo;

	function __construct()
	{
		$this->RequestHandler();
	}

	function RequestHandler()
	{
		$this->gateUrl = "https://www.tenpay.com/cgi-bin/v1.0/service_gate.cgi";
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
	}

	function init()
	{
		
	}
	
	function getGateURL()
	{
		return $this->gateUrl;
	}

	function setGateURL($gateUrl)
	{
		$this->gateUrl = $gateUrl;
	}

	function getKey()
	{
		return $this->key;
	}

	function setKey($key)
	{
		$this->key = $key;
	}

	function getParameter($parameter)
	{
		return $this->parameters[$parameter];
	}

	function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$parameter] = $parameterValue;
	}

	function getAllParameters()
	{
		return $this->parameters;
	}

	function getRequestURL()
	{
		$this->createSign();
		$reqPar = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v)
			$reqPar .= $k . "=" . urlencode($v) . "&";

		$reqPar = substr($reqPar, 0, strlen($reqPar)-1);
		$requestURL = $this->getGateURL() . "?" . $reqPar;
		return $requestURL;
	}
	
	function getDebugInfo()
	{
		return $this->debugInfo;
	}

	function doSend()
	{
		header("Location:" . $this->getRequestURL());
		exit;
	}

	function createSign()
	{
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v)
		{
			if("" != $v && "sign" != $k)
				$signPars .= $k . "=" . $v . "&";
		}
		
		$signPars .= "key=" . $this->getKey();
		$sign = strtolower(md5($signPars));
		$this->setParameter("sign", $sign);
		$this->_setDebugInfo($signPars . " => sign:" . $sign);
	}

	function _setDebugInfo($debugInfo)
	{
		$this->debugInfo = $debugInfo;
	}
}

class ResponseHandler
{
	var $key;
	var $parameters;
	var $debugInfo;
	
	function __construct()
	{
		$this->ResponseHandler();
	}

	function ResponseHandler()
	{
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
		foreach($_GET as $k => $v)
			$this->setParameter($k, $v);
		
		foreach($_POST as $k => $v)
			$this->setParameter($k, $v);
	}
	
	function getKey()
	{
		return $this->key;
	}
	
	function setKey($key)
	{
		$this->key = $key;
	}
	
	function getParameter($parameter)
	{
		return $this->parameters[$parameter];
	}
	
	function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$parameter] = $parameterValue;
	}

	function getAllParameters()
	{
		return $this->parameters;
	}

	function isTenpaySign()
	{
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v)
		{
			if("sign" != $k && "" != $v)
				$signPars .= $k . "=" . $v . "&";
		}
		
		$signPars .= "key=" . $this->getKey();
		$sign = strtolower(md5($signPars));
		$tenpaySign = strtolower($this->getParameter("sign"));
		$this->_setDebugInfo($signPars . " => sign:" . $sign . " tenpaySign:" . $this->getParameter("sign"));
		return $sign == $tenpaySign;
	}

	function getDebugInfo()
	{
		return $this->debugInfo;
	}

	function doShow($show_url)
	{
		$strHtml = "<html><head>\r\n" .
				"<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">" .
				"<script language=\"javascript\">\r\n" .
				"window.location.href='" . $show_url . "';\r\n" .
				"</script>\r\n" .
				"</head><body></body></html>";
		echo $strHtml;
		exit;
	}

	function _isTenpaySign($signParameterArray)
	{
		$signPars = "";
		foreach($signParameterArray as $k)
		{
			$v = $this->getParameter($k);
			if("sign" != $k && "" != $v)
				$signPars .= $k . "=" . $v . "&";
		}
		$signPars .= "key=" . $this->getKey();
		$sign = strtolower(md5($signPars));
		$tenpaySign = strtolower($this->getParameter("sign"));
		$this->_setDebugInfo($signPars . " => sign:" . $sign . " tenpaySign:" . $this->getParameter("sign"));
		return $sign == $tenpaySign;
	}

	function _setDebugInfo($debugInfo)
	{
		$this->debugInfo = $debugInfo;
	}
}

class ClientResponseHandler
{
	var $key;
	var $parameters;
	var $debugInfo;
	var $content;

	function __construct()
	{
		$this->ClientResponseHandler();
	}

	function ClientResponseHandler()
	{
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
		$this->content = "";
	}

	function getKey()
	{
		return $this->key;
	}

	function setKey($key)
	{
		$this->key = $key;
	}

	function setContent($content)
	{
		$this->content = $content;

		$xml = simplexml_load_string($this->content);
		$encode = $this->getXmlEncode($this->content);

		if($xml && $xml->children())
		{
			foreach ($xml->children() as $node)
			{
				if($node->children())
				{
					$k = $node->getName();
					$nodeXml = $node->asXML();
					$v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);
				}else{
					$k = $node->getName();
					$v = (string)$node;
				}
				if($encode!="" && $encode != "UTF-8")
				{
					$k = iconv("UTF-8", $encode, $k);
					$v = iconv("UTF-8", $encode, $v);
				}
				$this->setParameter($k, $v);
			}
		}
	}

	function setContent_backup($content)
	{
		$this->content = $content;
		$encode = $this->getXmlEncode($this->content);
		$xml = new SofeeXmlParser();
		$xml->parseFile($this->content);
		$tree = $xml->getTree();
		unset($xml);
		foreach ($tree['root'] as $key => $value)
		{
			if($encode!="" && $encode != "UTF-8")
			{
				$k = mb_convert_encoding($key, $encode, "UTF-8");
				$v = mb_convert_encoding($value[value], $encode, "UTF-8");
			}else{
				$k = $key;
				$v = $value[value];
			}
			$this->setParameter($k, $v);
		}
	}
	
	function getContent()
	{
		return $this->content;
	}

	function getParameter($parameter)
	{
		return $this->parameters[$parameter];
	}

	function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$parameter] = $parameterValue;
	}

	function getAllParameters()
	{
		return $this->parameters;
	}

	function isTenpaySign()
	{
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v)
		{
			if("sign" != $k && "" != $v)
				$signPars .= $k . "=" . $v . "&";
		}
		$signPars .= "key=" . $this->getKey();
		$sign = strtolower(md5($signPars));
		$tenpaySign = strtolower($this->getParameter("sign"));
		$this->_setDebugInfo($signPars . " => sign:" . $sign . " tenpaySign:" . $this->getParameter("sign"));
		return $sign == $tenpaySign;
	}

	function getDebugInfo()
	{
		return $this->debugInfo;
	}

	function getXmlEncode($xml)
	{
		$ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if($ret)
			return strtoupper ( $arr[1] );
		
		return '';
	}

	function _setDebugInfo($debugInfo)
	{
		$this->debugInfo = $debugInfo;
	}

	function _isTenpaySign($signParameterArray)
	{
		$signPars = "";
		foreach($signParameterArray as $k)
		{
			$v = $this->getParameter($k);
			if("sign" != $k && "" != $v)
				$signPars .= $k . "=" . $v . "&";
		}
		$signPars .= "key=" . $this->getKey();
		$sign = strtolower(md5($signPars));
		$tenpaySign = strtolower($this->getParameter("sign"));
		$this->_setDebugInfo($signPars . " => sign:" . $sign . " tenpaySign:" . $this->getParameter("sign"));
		return $sign == $tenpaySign;
	}
}

class TenpayHttpClient
{
	var $reqContent;
	var $resContent;
	var $method;
	var $certFile;
	var $certPasswd;
	var	$certType;
	var $caFile;
	var $errInfo;
	var $timeOut;
	var $responseCode;

	function __construct()
	{
		$this->TenpayHttpClient();
	}
	
	function TenpayHttpClient()
	{
		$this->reqContent = "";
		$this->resContent = "";
		$this->method = "post";
		$this->certFile = "";
		$this->certPasswd = "";
		$this->certType = "PEM";
		$this->caFile = "";
		$this->errInfo = "";
		$this->timeOut = 120;
		$this->responseCode = 0;
	}
	
	function setReqContent($reqContent)
	{
		$this->reqContent = $reqContent;
	}
	
	function getResContent()
	{
		return $this->resContent;
	}
	
	function setMethod($method)
	{
		$this->method = $method;
	}
	
	function getErrInfo()
	{
		return $this->errInfo;
	}
	
	function setCertInfo($certFile, $certPasswd, $certType="PEM")
	{
		$this->certFile = $certFile;
		$this->certPasswd = $certPasswd;
		$this->certType = $certType;
	}
	
	function setCaInfo($caFile)
	{
		$this->caFile = $caFile;
	}
	
	function setTimeOut($timeOut)
	{
		$this->timeOut = $timeOut;
	}
	
	function call()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeOut);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		$arr = explode("?", $this->reqContent);
		if(count($arr) >= 2 && $this->method == "post")
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_URL, $arr[0]);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arr[1]);
		}else{
			curl_setopt($ch, CURLOPT_URL, $this->reqContent);
		}
		if($this->certFile != "")
		{
			curl_setopt($ch, CURLOPT_SSLCERT, $this->certFile);
			curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->certPasswd);
			curl_setopt($ch, CURLOPT_SSLCERTTYPE, $this->certType);
		}
		
		if($this->caFile != "")
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_CAINFO, $this->caFile);
		} else {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}

		$res = curl_exec($ch);
		$this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($res == NULL)
		{
			$this->errInfo = "call http err :" . curl_errno($ch) . " - " . curl_error($ch) ;
			curl_close($ch);
			return false;
		} else if($this->responseCode  != "200") {
			$this->errInfo = "call http err httpcode=" . $this->responseCode  ;
			curl_close($ch);
			return false;
		}
		
		curl_close($ch);
		$this->resContent = $res;
		return true;
	}

	function getResponseCode()
	{
		return $this->responseCode;
	}
}