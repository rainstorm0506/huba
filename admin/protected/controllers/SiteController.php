<?php

class SiteController extends SController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{       
            $this->layout = false;
            $form  = ClassLoad::Only("SLoginForm"); /* @var $form SLoginForm */
            if($this->isPost() && !empty($_POST['SLoginForm'])){
                $form->attributes = $this->getPost('SLoginForm');
                if($form->validate() && $form->login()){
                    $this->redirect($this->createUrl('site/home'));
                }
            }
            $this->renderPartial('login',array(
                'model'=>$form
            ));
	}
        public function actionHome()
        {
            echo $this->getUid();
            die();
        }
        /**
	 * This is the action to handle external exceptions.
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
	 * Displays the login page
	 */
	public function actionLogin()
	{
            $this->layout = false;
            // display the login form
            $this->render('login');
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