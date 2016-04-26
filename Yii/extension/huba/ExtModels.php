<?php
/**
 * Description of ExtModels
 * 虎吧项目model层的基类
 * @author lqy
 */
class ExtModels
{
	/**
	 * 执行无查询SQL
	 * @param	string		$SQL		SQL
	 * @param	array		$params		为SQL执行的输入参数 (name=>value)
	 * @return	integer					返回此操作影响的行数
	 */
	public function execute($SQL , array $params = array())
	{
		return Yii::app()->getDb()->createCommand($SQL)->execute($params);
	}

	/**
	 * 执行一个SQL 查询
	 * @param	string		$SQL		SQL
	 * @param	array		$params		为SQL执行的输入参数 (name=>value)
	 * @return	CDbDataReader[对象]		获取查询结果的读取器对象
	 */
	public function query($SQL , array $params = array())
	{
		return Yii::app()->getDb()->createCommand($SQL)->query($params);
	}

	/**
	 * 查询并返回结果中的所有行
	 * @param	string		$SQL				SQL
	 * @param	array		$fetchAssociative	是否每一行应该被作为一个关联数组返回， 关联数组的列名为键或列索引作为键（从0开始）。
	 * @param	array		$params				为SQL执行的输入参数 (name=>value)
	 * @return	array							返回查询结果中的所有行。每个数组元素是一个数组，表示一行。 如果查询结果为空，返回空数组。
	 */
	public function queryAll($SQL , $fetchAssociative = true , array $params = array())
	{
		return Yii::app()->getDb()->createCommand($SQL)->queryAll($fetchAssociative , $params);
	}

	/**
	 * 查询并返回结果中的第一行
	 * @param	string		$SQL				SQL
	 * @param	array		$fetchAssociative	是否每一行应该被作为一个关联数组返回， 关联数组的列名为键或列索引作为键（从0开始）。
	 * @param	array		$params				为SQL执行的输入参数 (name=>value)
	 * @return	array							返回查询结果的第一行。 如果没有结果为空数组。
	 */
	public function queryRow($SQL , $fetchAssociative = true , array $params = array())
	{
		$row = Yii::app()->getDb()->createCommand($SQL)->queryRow($fetchAssociative , $params);
		return empty($row) ? array() : $row;
	}

	/**
	 * 查询并返回结果中的第一列
	 * @param	string		$SQL				SQL
	 * @param	array		$params				为SQL执行的输入参数 (name=>value)
	 * @return	array							返回查询结果的第一列。 如果没有结果为空数组。
	 */
	public function queryColumn($SQL , array $params = array())
	{
		return Yii::app()->getDb()->createCommand($SQL)->queryColumn($params);
	}

	/**
	 * 查询并返回结果中第一行的第一个字段
	 * @param	string		$SQL			SQL
	 * @param	array		$params			为SQL执行的输入参数 (name=>value)
	 * @return	mixed						返回查询结果的第一行数据的第一列的值。如果没有值返回False。
	 */
	public function queryScalar($SQL , array $params = array())
	{
		return Yii::app()->getDb()->createCommand($SQL)->queryScalar($params);
	}
	
	/**
	 * 插入
	 * @param	string		$table		将被插入的表
	 * @param	array		$columns	要插入表的列数据(name=>value)
	 * @return	integer					返回此操作影响的行数
	 */
	public function insert($table , array $columns)
	{
		return Yii::app()->getDb()->createCommand()->insert($table , $columns);
	}
	
	/**
	 * 获得最后插入的ID
	 */
	public function getInsertId()
	{
		return Yii::app()->getDb()->getLastInsertID();
	}
	
	/**
	 * 更新
	 * @param	string		$tabName		将被插入的表
	 * @param	array		$columns		要更新的列数据(name=>value)
	 * @param	mixed		$conditions		放入 WHERE 部分的条件
	 * @param	array		$params			要绑定到此查询的参数
	 * @return	integer						返回此操作影响的行数
	 */
	public function update($table , array $columns , $conditions = '', array $params = array())
	{
		return Yii::app()->getDb()->createCommand()->update($table , $columns , $conditions , $params);
	}
	
	/**
	 * 删除
	 * @param	string		$tabName		将被插入的表
	 * @param	mixed		$conditions		放入 WHERE 部分的条件
	 * @param	array		$params			要绑定到此查询的参数
	 * @return	integer						返回此操作影响的行数
	 */
	public function delete($table , $conditions = '', array $params = array())
	{
		return Yii::app()->getDb()->createCommand()->delete($table , $conditions , $params);
	}
	
	public function quoteValue($params)
	{
		return Yii::app()->getDb()->quoteValue(trim($params));
	}
	
	/**
	 * like 查询
	 * @param	string		$params		查询条件
	 * @param	int			$pattern	查询模式 0=全查询 , 1=前查询 , 2=后查询
	 */
	public function quoteLikeValue($params , $pattern = 0)
	{
		$params = substr(Yii::app()->getDb()->quoteValue(trim($params)), 1 , -1);
		switch ($pattern)
		{
			case 1	: return "'%{$params}'";
			case 2	: return "'{$params}%'";
			default	: return "'%{$params}%'";
		}
	}
}
