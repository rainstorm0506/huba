<?php
/**
 * Description of HomeController
 * 后台首页 展现
 * @author Administrator
 */
class HomeController extends SController{
    public function actionIndex()
    {
        echo $this->getUid();
        die();
    }
}
