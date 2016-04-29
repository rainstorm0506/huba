<?php
/**
 * 异步请求控制器 - 控制器
 * 
 * @author lqy
 */
class AsyncController extends WebController
{
	private $vxcode = array('vmember', 'venterprise', 'vmerchant', 'member' , 'merchant' , 'find');
        # 短信验证码过期时间 , 5分钟
        const SMSEXPIRE = 300;

        private $sessKey = array(
            1	=> 'sign',		#注册
            2	=> 'find',		#找回密码
            3	=> 'normal'	#正常情况发送短信
        );  
        //异步 发送短信
        public function actionSendSms()
        {
            $mobile = trim((string)$this->getPost("phone"));
            $type = trim((string)$this->getPost("type"));

            $sms_code = SmsNote::random();
            $sessKey = $this->sessKey[$type];
            $time    =  time();
            /*******    测试代码(不发送短信)    ********/
            if ((int)$this->getPost('test') === 1)
            {
                $session = Yii::app()->session;
                $sx = array();
                if (isset($session['smsCode']))
                {
                    $sx = new ArrayObject($session['smsCode']);
                    $sx = $sx->getArrayCopy();
                }

                $sx[$sessKey] = array('phone'=>$mobile , 'verCode'=>$sms_code , 'sendTime'=>$time , 'expire'=>$time+self::SMSEXPIRE , 'verifyTime'=>0);
                $session['smsCode'] = $sx;
                $this->jsonOutput(0 , array('vcode' => $sms_code));
                exit;
            }
            $res = SmsNote::send_sms($mobile,$sms_code);    //发送短信 返回的码
            if($res==2){ //短信平台 成功返回的状态值
                $session = Yii::app()->session;
                $sx = array();
                if (isset($session['smsCode']))
                {
                    $sx = new ArrayObject($session['smsCode']);
                    $sx = $sx->getArrayCopy();
                }

                $sx[$sessKey] = array('phone'=>$mobile , 'verCode'=>$sms_code , 'sendTime'=>$time , 'expire'=>$time+self::SMSEXPIRE , 'verifyTime'=>0);
                $session['smsCode'] = $sx;
                $this->jsonOutput(0 , array('vcode' => $sms_code));
                exit;                
            }else{
                $this->jsonOutput(3 , "短信平台繁忙,请稍候再发!");
                exit;                  
            }
        }
        //异步 验证短信验证码
        public function actionCheckCode()
        {
            $code = trim((string)$this->getPost('sms_code'));
            $type = trim((string)$this->getPost("type"));
            $sessKey = $this->sessKey[$type];   //验证码的 类型

            $session = Yii::app()->session;

            if(time() - $session['smsCode'][$sessKey]['sendTime']>self::SMSEXPIRE){
                $this->jsonOutput(2,"短信验证码已经过期");
            }else if($session['smsCode'][$sessKey]['verCode']!=$code){
                $this->jsonOutput(1,"短信验证码不正确");
            }else{
                $this->jsonOutput(0);
            }
            Yii::app()->end();
        }
	//图形验证码
	public function actionGetVcdoe()
	{
		$type = (string)$this->getQuery('type');
		$type = in_array($type , $this->vxcode) ? $type : 'member';
		
		$captcha = new VerifCodeIntAction($this , 'captcha');
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
	
	//设为默认地址
	public function actionUserSetDeftAddrs()
	{
		if (($id = (int)$this->getQuery('id')) < 1)
			$this->jsonOutput(1 , 'ID错误');
		
		if (!$uid = $this->getUid())
			$this->jsonOutput(2 , '请登录后操作!');
		
		$model = ClassLoad::Only('Cart');/* @var $model Cart */
		if ($model->userSetDeftAddrs($id , $uid))
			$this->jsonOutput(0);
		else
			$this->jsonOutput(3 , '操作失败!');
	}
	
	//添加&修改地址
	public function actionAddress()
	{
		
	}
}