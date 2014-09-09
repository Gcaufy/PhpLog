<h3>Configuration:</h3>
<pre>
return array(
	//'enable' => true,		Default to true, set false disable the log function
	//'autoFlush' => 10000		Default to 10000, only works when autoDump set to false, when there are 10000 logs then flush them
	//'autoDump' => false,		Default to true, flush the log everytime, when set to false means flush the log only when you called PhpLog::flush()
	'routes'=>array(		// Define the routes you, there are 7 kinds of routes(db/email/file/profile/web/saeStorage/saeDb).
		array(
			'type'=>'file',		// File route, write log to a file
			'levels'=>'error, warning, trace, profile, info',		// Defaults to empty, meaning all levels, only log when the levels map these items.
			//'filter' => array(		// Preprocess the log messages
			//    'prefixSession' => false,	// Default to false, Whether to prefix each log message with the current user session ID.
			//	  'logVars' => array('_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER');	// List of the PHP predefined variables that should be logged.
			//),
			//'maxFileSize' => 1024,		// Default to 1024KB, create a new file when the log is bigger than 1024
			//'logPath' => '', 		// Default to current path(save path with the PhpLog)
			//'logFile' => 'application.log', 		// Default to 'application.log'. The log file name.
			//'enabled' => true,		// Default to true. Set false to disable the route.
		),
		array(
			'type'=>'saeStorage',		// SAE Storage route, write log as a file in storage, have the same attributes with file route
			'domain' => 'wordpress',		// Storage domain
			'logPath' => 'log',			
			'levels'=>'error',
		),
		array(
			'type'=>'email',		// Email route, send an email for the log, using php mail method
			'levels'=>'error',
			'email' => array(),		// Emails are sending to
			'from' => '',		// Email send from
			//'subject' => 'Application Log',		// Email subject, default to 'Apllication Log'
			//'header' => $headers,		// Array list of additional headers to use when sending an email.
		),
		array(
			'type'=>'web',		// Web route, show the logs on the end of the page or in firebug console.
			'levels'=>'error, warning',
			//'showInFireBug' => false,		// Default to false, Show log on the page, set to true to show logs in firebug console
			//'ignoreAjaxInFireBug' => true, 	// Default to true, ignore the ajax request. Only works when shows in firebug.
			//'ignoreFlashInFireBug' => true,		// Default to true, ignore the flash request. Only works when shows in firebug.
			//'collapsedInFireBug' => false, 		// Default to false, group the logs in firebug or not.
		),
		array(
			'type'=>'profile',		// Profile route, shows profile information on the web or firebug
			'levels'=>'error, warning, info',
			//'groupByToken' => true,		// Default to true. Whether to aggregate results according to profiling tokens.
		),
		array(
			'type'=>'db',		// Db route, write logs to the database, only support mysql.
			'connection' => array(		// The db connection. required always.
				'host' => 'localhost',
				'user' => 'root',
				'password' => 'root',
				'db' => 'app_madcoder',
			),
			'levels'=>'error, warning, info',
			//'logTableName'=>'phplog',		// Default to 'phplog'. The table to write log 
			//'autoCreateLogTable' => true,		// Default to true. Auto create the table, if the table doesn't exsit, set to false will not create the table.
			
		),

		array(
			'type'=>'saeDb',		// SAE Db Route. Write logs to the SAE database.
			'logTableName'=>'sys_log2',
			'levels'=>'error, warning, info',
		),
	),
);
</pre>


<h3>Example:</h3>
<pre>

PhpLog::log('This is a trace log', 'trace', 'test'); 
PhpLog::log('This is a warning log', 'warning', 'test'); 
PhpLog::log('This is a error log', 'error', 'test'); 
PhpLog::log('This is a info log', 'info', 'test'); 
PhpLog::log('This is a nothing log', 'nothing', 'test');


// Profile testing.
$arr = array();
$len = 500000;
for($i = 0; $i < $len; $i++){
	$arr[] = $i*rand(1000,9999);
}
PhpLog::log('begin:TEST-FOR', 'profile', 'test');
for($i = 0; $i < $len; $i++){
	$str .= $arr[$i];
}
PhpLog::log('end:TEST-FOR', 'profile', 'test'); 

PhpLog::log('begin:TEST-WHILE', 'profile', 'test');
$i = 0;
while($i < $len) {
	$str .= $arr[$i];
	$i++;
}
PhpLog::log('end:TEST-WHILE', 'profile', 'test'); 

PhpLog::log('begin:TEST-WHILELIST', 'profile', 'test');
while(list($key, $val) = each($arr)){
	$str .= $val;
}
PhpLog::log('end:TEST-WHILELIST', 'profile', 'test'); 

PhpLog::log('begin:TEST-FOREACH', 'profile', 'test');
foreach($arr as $key => $val){
	$str .= $val;
}
PhpLog::log('end:TEST-FOREACH', 'profile', 'test'); 


</pre>