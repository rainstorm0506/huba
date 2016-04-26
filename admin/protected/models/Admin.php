<?php
/**
 * Description of Admin
 * 平台管理员 数据模型层
 * @author Administrator
 */
class Admin extends SModels{
    /**
     * 得到管理员的信息
     * @param		string		$account		用户名称
     * @return		array
     */
    public function getUserInfo($account)
    {
        if (!$account) return array();
        
        $sql = "SELECT * FROM h_admin WHERE `name`={$this->quoteValue($account)}";
        return $this->queryRow($sql);
    }    
}
