<?php
$path = dirname(__FILE__).DIRECTORY_SEPARATOR."..";
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'虎吧平台管理',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
                'system.extension.huba.*',
                'system.extension.PHPExcel.*',
		'application.models.*',
		'application.form.*',
		'application.components.*',
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		#memCache缓存
		'memCache' => require($path . '/../../config/memCache.php'),
		#文本缓存
		'fileCache' => require($path . '/../../config/fileCache.php'),	           
		// uncomment the following to enable URLs in path-format
                'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),		
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=huba',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		// 日志
		'log' => array (
			'class' => 'CLogRouter',
			'routes' => array (
				array (
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning'
				),
				array ('class' => 'CWebLogRoute')
			)
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);