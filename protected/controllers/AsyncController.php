<?php
/**
 * 异步请求控制器 - 控制器
 * 
 * @author lqy
 */
class AsyncController extends WebController
{
	private $vxcode = array('vmember', 'venterprise', 'vmerchant', 'member' , 'merchant' , 'find');
	
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
	
	/**
	 * 获得地区数据
	 * 
	 * @param		GET		int		dictOneId			第一层ID
	 * @param		GET		int		dictTwoId			第二层ID
	 * @param		GET		int		dictThreeId			第三层ID
	 */
	public function actionDictChild()
	{
		$oneId = (int)$this->getQuery('dictOneId');
		$twoId = (int)$this->getQuery('dictTwoId');
		$threeId = (int)$this->getQuery('dictThreeId');
		
		$this->jsonOutput(0 , GlobalDict::getUnidList($oneId , $twoId , $threeId));
	}
	
	/**
	 * 收藏店铺
	 * 
	 * @param		GET		int		id		ID
	 * @param		GET		int		type	类别
	 */
	public function actionCollects()
	{
		$type = (int)$this->getQuery('type');
		if ($type < 1 || $type > 3)
			$this->jsonOutput(1 , '类别错误!');
		
		if (($id = (int)$this->getQuery('id')) <= 0)
			$this->jsonOutput(2 , 'ID错误');
		
		if (!$uid = $this->getUid())
			$this->jsonOutput(3 , '请登录后操作!');
		
		$model = ClassLoad::Only('Store');/* @var $model Store */
		if (($rt = $model->collects($type , $id , $uid)) === 1)
			$this->jsonOutput(0 , array('type'=>$type , 'join'=>true));
		elseif ($rt == -1)
			$this->jsonOutput(0 , array('type'=>$type , 'join'=>false));
		else
			$this->jsonOutput(3 , '操作失败!');
	}
	
	/**
	 * 商品点赞
	 * 
	 * @param		int		gid			商品ID
	 * @param		int		type		类别 , 1=商品 , 2=二手 , 3=积分 , 4=店铺
	 */
	public function actionUserPraise()
	{
		if (!$uid = $this->getUid())
			$this->jsonOutput(1 , '请登录后操作!');
		
		if (!$gid = (int)$this->getQuery('gid'))
			$this->jsonOutput(2 , '商品ID错误');
		
		if (!$type = (int)$this->getQuery('type'))
			$this->jsonOutput(3 , '类别错误');
		
		$models = ClassLoad::Only('Goods');/* @var $models Goods */
		if (($ret = $models->praise($gid , $type , $uid)) > 0)
		{
			GlobalGoods::getNewGoods(0 , 0 , true);
			$this->jsonOutput(0 , array('join'=>true));
		}elseif($ret == -1){
			$this->jsonOutput(0 , array('join'=>false));
		}
		$this->jsonOutput(4 , '程序异常!');
	}
	
	public function actionUserAddress()
	{
		$model = ClassLoad::Only('Cart');/* @var $model Cart */
		$this->renderPartial('userAddress' , array(
			'address' => $model->getUserAddressList($this->getUid()),
		));
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