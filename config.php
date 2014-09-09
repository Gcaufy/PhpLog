<?php 
return array(
	'enable' => true,
	'autoDump' => false,
	'routes'=>array(
		array(
			'type'=>'file',
			'levels'=>'error, warning',
		),
		array(
			'type'=>'saeStorage',
			'domain' => 'wordpress',
			'logPath' => 'log',
			'levels'=>'error',
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
			'filter' => array(
				'prefixSession' => true,
			),
			//'enabled' => false,
		),

		array(
			'type'=>'saeDb',
			'logTableName'=>'sys_log2',
			'levels'=>'error, warning, info',
			'filter' => array(
				'prefixSession' => true,
			),
			//'enabled' => false,
		),
	),
);