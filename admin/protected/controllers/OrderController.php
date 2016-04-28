<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-28
 * Time: 上午11:14
 */

class OrderController extends  SController{
        /*
         * 订单列表首页
         */
        public function actionIndex()
        {
            $this->layout = false;
            $this->render('index');
        }
} 