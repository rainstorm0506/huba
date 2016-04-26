<?php
/**
 * Created by PhpStorm.
 * User: lqy
 * Date: 16-4-22
 * Time: 下午5:20
 */

class GlobalController extends WebController{
    /*
     * 处理 多图上传(新插件)
     */
    public function actionMove()
    {
        $file_path = "./uploads/";
        if (isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
            $upload_file = $_FILES['Filedata'];
            $file_info = pathinfo($upload_file['name']);
            $file_type = $file_info['extension'];
            $save = $file_path . md5(uniqid($_FILES["Filedata"]['name'])) . '.' . $file_info['extension'];
            $name = $_FILES['Filedata']['tmp_name'];
            if (!move_uploaded_file($name, $save)) {
                exit;
            }else{
                echo $save;
            }
        }
    }
    /*
     * 异步 删除图片
     */
    public function actionDelImg()
    {
        $src = $_GET['src'];
        if (file_exists($src)) {
            unlink($src);
            echo 0;
        }else{
            echo 1;
        }
    }
} 