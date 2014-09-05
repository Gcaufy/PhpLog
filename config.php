<?php 
return array(
	'autoDump' => false,
	'routes'=>array(
		array(
			'type'=>'file',
			'levels'=>'error, warning',
		),
		array(
			'type'=>'email',
			'levels'=>'error',
			'email' => array('gongweiyue@163.com'),
			'from' => 'test',
		),
		array(
			'type'=>'web',
			'levels'=>'error, warning',
			//'showInFireBug' => true,
		),
		array(
			'type'=>'profile',
			'levels'=>'error, warning, info',
			//'showInFireBug' => true,
		),
		array(
			'type'=>'db',
			'connection' => array(
				'host' => 'localhost',
				'user' => 'root',
				'password' => 'root',
				'db' => 'app_madcoder',
			),
			'logTableName'=>'sys_log',
			'levels'=>'error, warning, info',
			//'enabled' => false,
		),
		// // uncomment the following to show log messages on web pages
		// array(
		// 	'class'=>'CWebLogRoute',
		// ),
	),
);