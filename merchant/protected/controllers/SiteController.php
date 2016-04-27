<?php

class SiteController extends MController
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
            $this->layout = false;
            $form  = ClassLoad::Only("MLoginForm"); /* @var $form MLoginForm */
            if($this->isPost() && !empty($_POST['MLoginForm'])){
                $form->attributes = $this->getPost('MLoginForm');
                if($form->validate() && $form->login()){
                    echo "成功!!";
                    die();
                    //$this->redirect($this->createUrl('site/home'));
                }
            }
            $this->renderPartial('login',array(
                'model'=>$form
            ));
	}
        public function actionHome()
        {
            $this->render('home');
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