<?php
/**
 * Description of Admin
 * 后台 管理员操作模型
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
		
                $sql = "SELECT * FROM admin WHERE account='{$account}'";
		return $this->queryRow($sql);
	}    
}
