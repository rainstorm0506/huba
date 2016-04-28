<?php

class SiteController extends SController
{
    public $layout='//layouts/column1';

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
            //$this->layout = false;
            $form  = ClassLoad::Only("SLoginForm"); /* @var $form SLoginForm */
            if($this->isPost() && !empty($_POST['SLoginForm'])){
                $form->attributes = $this->getPost('SLoginForm');
                if($form->validate() && $form->login()){
                    $this->redirect($this->createUrl('home/index'));
                }
            }
            $this->renderPartial('login',array(
                'model'=>$form
            ));
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
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}