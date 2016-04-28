<?php
/**
 * Description of HomeController
 * 后台首页 展现
 * @author Administrator
 */
class HomeController extends CController{
    public function actionIndex()
    {
        $this->layout = false;
        $this->render('index');
    }
    //框架集 头部
    public function actionTop()
    {
        $this->layout = false;
        $this->render('top');
    }
    //框架集 左侧菜单栏
    public function actionLeft()
    {
        $this->layout = false;
        $this->render('left');
    }
    //框架集 右侧主要内容
    public function actionRight()
    {
        $this->layout = false;
        $this->render('right');
    }
}
