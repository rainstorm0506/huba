<?php
/**
 * Description of HomeController
 * 商户端后台首页
 * @author Administrator
 */
class HomeController extends MController{
    //展示后台首页
    public function actionIndex()
    {
        $this->layout = false;
        $this->render("index");
    }
    //商户后台 头部
    public function actionTop()
    {
        $this->layout = false;
        $this->render("top");
    }
    //商户后台 左侧菜单栏
    public function actionLeft()
    {
        $this->layout = false;
        $this->render("left");
    }
    //商户后台 右侧内容页面
    public function actionMain()
    {
        $this->layout = false;
        $this->render("main");
    }
}
