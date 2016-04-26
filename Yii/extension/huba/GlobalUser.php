<?php
/**
 * Description of GlobalUser
 * 全局用户 相关方法
 * @author Administrator
 */
class GlobalUser {
	/**
	 * 检查给定的密码是否正确
	 * @param		string		$writePassword		密码(明文字符串)
	 * @param		string		$dbPassword			数据库中存储加密后的密码值
	 * @return		boolean
	 */
	public static function validatePassword($writePassword , $dbPassword)
	{
		return self::hashPassword($writePassword) === $dbPassword;
		#return password_verify($writePassword , $dbPassword);
	}
	
	/**
	 * 生成的密码散列
	 * @param		string		$password		密码(明文字符串)
	 * @return		string
	 */
	public static function hashPassword($password)
	{
		return sha1(hash('huba' , $password));
		#return password_hash($password , PASSWORD_DEFAULT);
	}    
}
