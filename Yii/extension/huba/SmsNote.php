<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-27
 * Time: 下午3:59
 */

class SmsNote {
    const   NULL_PHONE = 0; //号码唯恐
    const   UN_ERROR = -1;  //未知错误
    //短信平台目标
    private static $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
    //用户账户
    private static $account = "cf_lqy050600";
    //密码
    private static $pwd = "123456";
    //解析 服务器返回的 xml格式字符串
    private static function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = self::xml_to_array($subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }
    /*
     * 发送 http请求
     */
    public static function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }
    //生成 验证码字符串
    public static function random($length = 6 , $numeric = 1) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }
    /*
     * 发送短信
     *
     */
    public static function send_sms($mobile,$mobile_code)
    {
        if(empty($mobile))
            return  self::NULL_PHONE;

        $post_data = "account=".self::$account."&password=".self::$pwd."&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");
        $post = self::Post($post_data,self::$target);
        $res = self::xml_to_array($post);
        if(!empty($res)){
            return $res['SubmitResult']['code'];
        }else{
            return self::UN_ERROR;
        }
    }
} 