<?php
/**
 * Description of ClassLoad
 *创建单例模式
 * @author lqy
 */
class ClassLoad {
	/**
	 * 返回一个 单例 class
	 * @param string $cn	类名称
	 */
	public static function Only($cn)
	{
		static $c = array();
		
		$class = null;
		if (isset($c[$cn]))
		{
			$class = $c[$cn];
		}else{
			$class = new $cn();
			$c[$cn] = $class;
		}
		return $class;
	}    
}
