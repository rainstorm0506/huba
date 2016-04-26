<?php
/**
 * Description of WebModel
 *
 * @author Administrator
 */
class WebModels extends ExtModels
{
	/**
	 * 将json格式的字符串解析
	 *
	 * @param		string		$json		json
	 */
	public function jsonDnCode($json)
	{
		return ($json && ($_temp = @json_decode($json,true)) && json_last_error()==JSON_ERROR_NONE) ? $_temp : array();
	}
	
	/**
	 * 获得基础的SQL
	 * @param		string		$key		表中字段名称
	 * @param		string		$val		字段的值
	 * @param		string		$type		查询类型	[LIKE , INTGROUP , ...]
	 * @param		string		$prefix		前缀
	 * @return		SQL
	 */
	public function getBaseSQL($key , $val , $type = 'EQUAL' , $prefix = '')
	{
		if (!$key || $val === null || (is_string($val) && trim($val)==='') || !$type)
			return '';
		
		$prefix = ($prefix && substr($prefix , -1) !== '.') ? ($prefix . '.') : $prefix;
		switch ($type)
		{
			case 'EQUAL'	: return $prefix . $key.'='.$this->quoteValue($val);
			case 'UNEQUAL'	: return $prefix . $key.'!='.$this->quoteValue($val);
			case 'LIKE'		: return "{$prefix}{$key} LIKE '%".substr($this->quoteValue($val) , 1 , -1)."%'";
			case 'INTGROUP'	:
				$val = is_array($val)?$val:array();
				$s = empty($val[0]) ? 0 : (int)$val[0];
				$e = empty($val[1]) ? 0 : (int)$val[1];
				if ($s && $e && $s > $e)
					list($s , $e) = array($e , $s);
	
				$SQL = array();
				if ($s) $SQL[] = "{$prefix}{$key}>={$s}";
				if ($e) $SQL[] = "{$prefix}{$key}<={$e}";
				return $SQL ? implode(' AND ', $SQL) : '';
		}
	}

	/**
	 * 删除缓存数据
	 * @param	string/array	$cacheName		缓存名称
	 * @param	string			$suffix			后缀
	 */
	public function clearCache($cacheName , $suffix = '')
	{
		if (is_array($cacheName))
		{
			foreach ($cacheName as $cache)
			{
				if (is_string($cache))
					CacheBase::delete($cache , $suffix);
				elseif (is_array($cache))
				CacheBase::delete(isset($cache[0])?$cache[0]:'' , isset($cache[1])?$cache[1]:'');
			}
		}else{
			CacheBase::delete($cacheName , $suffix);
		}
	}
	
	//获得登录用户ID , 这里是登录人ID , 所以 , 商家ID,子账号ID 都有可能
	public function getUid()
	{
		return (int)Yii::app()->getUser()->getId();
	}
	
	//获得登录人信息
	public function getUser()
	{
		return (($user = Yii::app()->getUser()->getName()) && is_array($user)) ? $user : array();
	}
	
}
