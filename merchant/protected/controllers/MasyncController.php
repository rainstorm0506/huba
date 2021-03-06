<?php
/**
 * 异步请求控制器 - 控制器
 * 
 * @author lqy
 */
class MasyncController extends MController
{
	private $vxcode = array('merchant');
	
	//图形验证码
	public function actionGetVcode()
	{
		$type = (string)$this->getQuery('type');
		$type = in_array($type , $this->vxcode) ? $type : 'merchant';
		
		$captcha = new MVerifyCodeAction($this , 'captcha');
		$captcha->backColor = 0xFFFFFF;
		$captcha->minLength = 6;
		$captcha->maxLength = 6;
		$captcha->height = 40;
		$captcha->width = 126;
		
		$code = $captcha->getCodes();
		
		$session = Yii::app()->session;
		$sx = array();
		if (isset($session['captcha']))
		{
			$sx = new ArrayObject($session['captcha']);
			$sx = $sx->getArrayCopy();
		}
		$sx[$type]['code'] = $code;
		Yii::app()->session['captcha'] = $sx;
		
		$captcha->rendCode($code);
	}
	
	/**
	 * 验证图形验证码
	 * 
	 * @param		GET			string		code		图形验证码
	 * @param		GET			string		ags			类型
	 */
	public function actionVerifyVcode()
	{
		if (!$code = (string)$this->getQuery('code'))
			$this->jsonOutput(1 , '请输入图形验证码!');
		
		if (!($type = (string)$this->getQuery('ags')) || !in_array($type , $this->vxcode))
			$this->jsonOutput(2 , '图形验证码类型错误!');
		
		$session = Yii::app()->session;
		if (!empty($session['captcha'][$type]['code']) && $code == $session['captcha'][$type]['code'])
		{
			$sx = new ArrayObject($session['captcha']);
			$sx = $sx->getArrayCopy();
			$sx[$type]['verdict'] = 1;
			Yii::app()->session['captcha'] = $sx;
			
			$this->jsonOutput(0);
		}else{
			$this->jsonOutput(3 , '请输入正确的图形验证码!');
		}
	}
	
	/**
	 * 输出二维码图片
	 * 
	 * @param		GET			string		text		内容
	 */
	public function actionQrcode()
	{
		if (!$text = (string)$this->getQuery('text'))
		{
			if ($ucode = (string)$this->getQuery('ucode'))
				$text = $this->createAbsoluteUrl('home/sign' , array('code' => $ucode));
		}
		
		if (!$text)
			Yii::app()->end();
		
		echo QRcode::png($text , false , QR_ECLEVEL_L , 3 , 2);
	}
	
	/**
	 * 验证手机号码是否被注册过
	 * 
	 * @param		GET			string		phone		手机号码
	 * @param		GET			string		type		member / enterprise / merchant
	 */
	public function actionVerifyPhone()
	{
		$type = (string)$this->getQuery('type');
		$phone = (string)$this->getQuery('phone');
		$type = $type === 'merchant';
		
		if (!Verify::isPhone($phone))
			$this->jsonOutput(1 , '手机号码错误');
		
		$model = ClassLoad::Only('Home');/* @var $model Home */
		if ($model->checkUserPhone($phone , $type))
			$this->jsonOutput(2 , '此号码已注册!');
		
		$this->jsonOutput(0);
	}
	
}