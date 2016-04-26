<?php
/**
 * Description of WebForm
 * 表单模型(用于处理表单提交的数据信息)
 * @author  lqy
 */
class WebForm extends CFormModel{
	//获得登录用户ID , 这里是登录人ID , 所以 , 商家ID,子账号ID 都有可能
	public function getUid()
	{
		return (int)Yii::app()->getUser()->getId();
	}
	
	//获得登录人信息
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
}
