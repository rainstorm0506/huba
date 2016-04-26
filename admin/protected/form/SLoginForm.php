<?php
/**
 * Description of SLoginForm
 *
 * @author Administrator
 */
class SLoginForm extends SForm{
    public $name,$pwd,$login_time,$codes;
    private $_identity = null;
    public function rules()
    {
        return array(
            array('name','required','message'=>'账户名必填'),
            array('pwd','required','message'=>' 密码不能为空'),
            array('pwd','checkPassword')
        );
    }
    //验证密码
    public function checkPassword()
    {
            $this->_identity = new AdminIdentity($this->name , $this->pwd);
            if($this->_identity->authenticate() == AdminIdentity::ERROR_PASSWORD_INVALID)
                    $this->addError('password' , '帐号或密码错误.');
    }    
}
