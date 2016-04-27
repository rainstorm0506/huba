<?php
/**
 * Description of SLoginForm
 *
 * @author Administrator
 */
class MLoginForm extends MForm{
    public $name,$pwd,$codes;
    private $_identity = null;
    public function rules()
    {
        return array(
            array('name','required','message'=>'账户名必填'),
            array('pwd','required','message'=>' 密码不能为空'),
            array('codes','required','message'=>' 验证码不能为空'),
            array('pwd','checkPassword'),
        );
    }
    //验证密码
    public function checkPassword()
    {
            $this->_identity = new MAdminIdentity($this->name , $this->pwd);
            if($this->_identity->authenticate() == MAdminIdentity::ERROR_PASSWORD_INVALID){
                $this->addError('pwd' , '帐号和密码不相匹配');
            }else if($this->_identity->authenticate() == MAdminIdentity::ERROR_USERNAME_INVALID){
                $this->addError('name' , '账户不存在.');
            }
                    
    } 
    /*
     * 执行登录
     */
    public function login()
    {
            if($this->_identity===null)
            {
                    $this->_identity=new MAdminIdentity($this->name,$this->pwd);
                    $this->_identity->authenticate();
            }
            if($this->_identity->errorCode===  MAdminIdentity::ERROR_NONE)
            {
                    //$duration=$this->rememberMe ? 1*24*30 : 0; // 1 days
                    Yii::app()->user->login($this->_identity);
                    return true;
            }
            else
                    return false;
    }    
}