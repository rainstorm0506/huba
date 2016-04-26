<?php
class SController extends CController
{
	//过滤器
	public function filters()
	{
		return array('checkLogin');
	}
	
	/**
	 * 检查是否登录
	 * @param	CFilterChain	$filterChain
	 */
	public function filterCheckLogin(CFilterChain $filterChain)
	{
            return true;
//		$route = $this->getRoute();
//		if ($route != 'site/index' && $route != 'site/captcha' && !$this->getUid())
//			$this->redirect(array('site/index'));
//		$filterChain->run();
	}
	
	/**
	 * 将json格式的字符串解析
	 *
	 * @param		string		$json		json
	 */
	public function jsonDnCode($json)
	{
		return ($json && ($_temp = @json_decode($json,true)) && json_last_error()==JSON_ERROR_NONE) ? $_temp : array();
	}
	
	//获得登录用户ID
	public function getUid()
	{
		return (int)Yii::app()->getUser()->getId();
	}
	
	//获得登录用户信息
	public function getUser()
	{
		return (($user = Yii::app()->getUser()->getName()) && is_array($user)) ? $user : array();
	}
	
	/**
	 * 获取 get或者post . 优先get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getParam($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getParam($name , $defaultValue);
	}
	
	/**
	 * 获取 post
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getPost($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getPost($name , $defaultValue);
	}
	
	/**
	 * 获取 get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getQuery($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getQuery($name , $defaultValue);
	}
	
	/**
	 * 请求是否是post
	 */
	public function isPost()
	{
		return Yii::app()->getRequest()->isPostRequest;
	}
	
	/**
	 * 自定义 显示错误
	 * @param	array	$error		错误数据
	 * @param	string	$page		页面
	 */
	public function error($error , $page = 'error')
	{
		$this->_viewsEnd($error, 'application.views.layouts.'.$page);
	}
	
	/**
	 * 自定义 显示提示
	 * @param	array	$message	提示数据
	 * @param	string	$page		页面
	 */
	public function message($message , $page = 'message')
	{
		$this->_viewsEnd($message, 'application.views.layouts.'.$page);
	}
	
	/**
	 * 显示 信息 , 并结束
	 * @param array/string		$data	数据
	 * @param string			$page	页面
	 * 
	 * $data = array('code'=>'' , 'info' => '' , 'title' => '');
	 */
	private function _viewsEnd($data , $page)
	{
		$ary = array();
		if (is_array($data))
		{
			$ary = $data;
			$ary['info'] = isset($ary['info']) ? $ary['info'] : '';
		}else{
			$ary['info'] = $data;
		}
		
		if(Yii::app()->request->isAjaxRequest)
			echo $ary['info'];
		else
			$this->renderPartial($page , array('des'=>$ary));
		Yii::app()->end();
	}
	
	/**
	 * post 提交的数据,结束
	 * @param	object	$model
	 * @param	string	$formId
	 */
	public function exitAjaxPost($model , $formId = 'append-form')
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === $formId)
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * 创建到前台的URL
	 * @param		string		$route			控制器/方法
	 * @param		array		$params			参数
	 * @param		string		$ampersand		参数链接符
	 * @return		string
	 */
	public function createFrontUrl($route , array $params = array() , $ampersand = '&')
	{
		return str_replace('/supervise/' , '/' , Yii::app()->createUrl($route , $params , $ampersand));
	}
	
	/**
	 * 检查登录用户的权限
	 * @param	string		$execKey		操作的key
	 * @param	boolean		$show			显示错误页面
	 */
	public function checkUserPurview($execKey , $show = true , $message = '你没有操作的权限!' , $page = 'noPurview')
	{
		static $session = null;
		if ($session === null)
		{
			$session = Yii::app()->session;
		}
		$purviews = isset($session[self::PURV]['purviews']) ? $session[self::PURV]['purviews'] : array();
		$purviews = empty($purviews[$execKey]);
		if ($show && $purviews)
			$this->_viewsEnd($message , 'application.views.layouts.'.$page);
		return !$purviews;
	}
	
	/**
	 * 获得栏位是否有显示的权限
	 * @param	int		$id		栏位ID
	 */
	public function getMenuShow($id)
	{
		static $session = null;
		if ($session === null)
		{
			$session = Yii::app()->session;
		}
		$fields = isset($session[self::PURV]['fields']) ? $session[self::PURV]['fields'] : array();
		return !empty($fields[$id]);
	}
	
	/**
	 * 删除缓存数据
	 * @param	string/array	$cacheName		缓存名称
	 */
	public function clearCache($cacheName)
	{
		if (is_array($cacheName))
		{
			foreach ($cacheName as $cache)
			{
				if (is_string($cache))
					CacheBase::clear($cache);
				elseif (is_array($cache))
					CacheBase::clear(isset($cache[0])?$cache[0]:'');
			}
		}else{
			CacheBase::clear($cacheName);
		}
	}

	/**
	 * 设置API返回值 直接输出
	 * @param	int				$code		错误码
	 * @param	string/array	$mixed		错误信息 / 正常情况下输出的数据
	 */
	public function jsonOutput($code , $mixed = array())
	{
		$return = array('code'=>$code , 'message'=>'' , 'data'=>array());
		if ($code === 0)
		{
			$return['data'] = is_array($mixed) ? $mixed : array();
		}else{
			$return['message'] = is_string($mixed) ? $mixed : '未知错误';
		}
		Yii::app()->end(json_encode($return));
	}
}