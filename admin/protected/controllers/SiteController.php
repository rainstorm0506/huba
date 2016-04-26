<?php

class SiteController extends SController
{
        /*
         * 平台默认登陆 页面
         */
	public function actionIndex()
	{
            echo 12131;
            die();
            $this->layout = false;
            //$form = ClassLoad::Only("SLoginForm");  /* @var $form SLoginForm */
            if($this->isPost()){
                
            }
            echo 1231;
            echo "<pre>";
            die(var_dump($form));
            $this->render('login',array(
                'form'=>$form
            ));
	}
        /*
         * 展示 平台后台页面
         */
        public function actionHome()
        {
            echo "home";
            die();
        }
        /**
	 * 错误处理页面
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}