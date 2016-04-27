<?php
/**
 * Description of AdminIdentity
 *
 * @author Administrator
 */
class AdminIdentity extends CUserIdentity{
    private $_id;
    /**
     * 验证用户登录信息
     * @param	boolean		$verify		是否验证
     * @return	boolean
     */    
    public function authenticate($verify = true) {
        $admin_model = ClassLoad::Only("Admin");    /* @var $admin_model Admin */
        $user_info = $admin_model->getUserInfo($this->username);
        
        if(empty($user_info)){
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }else if($verify && !GlobalUser::validatePassword($this->password, $user_info['pwd'])){
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }else{
            $this->_id = $user_info['id'];
            $this->username = $user_info['account'];
            $this->errorCode = self::ERROR_NONE;            
        }
        return $this->errorCode;
    }
    //得到私有属性_id
    public function getId()
    {
            return $this->_id;
    }    
}
