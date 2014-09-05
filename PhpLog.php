<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$
require_once('PhpLog.Logger.php');
require_once('PhpLog.LogRouter.php');

defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
defined('YII_BEGIN_TIME') or define('YII_BEGIN_TIME',microtime(true));

define('PHPLOG_DIR', dirname(__FILE__));

class PhpLog {

	public static $_instance = null;

	public $_logger = null;

	public $_router = null;

	public function __construct() {
		$config = require(PHPLOG_DIR . DIRECTORY_SEPARATOR . 'config.php');
		$this->_router = new CLogRouter($this, $config['routes']);
		unset($config['routes']);
		$this->_logger = new CLogger($this, $config);
	}

    public static function log($msg, $level = CLogger::LEVEL_INFO, $category = 'application') {
    	if (self::$_instance === null)
    		self::$_instance = new PhpLog();
    	$instance = self::$_instance;

        if (YII_DEBUG && YII_TRACE_LEVEL > 0 && $level !== CLogger::LEVEL_PROFILE) {
            // YII_TRACE_LEVEL 设置backtrace 显示的内容条数，
            //这个常量会在debug_backtrace 函数返回信息中，获取指定条数，
            //如果为0(默认) 则为全部显示
            $traces = debug_backtrace();
            //debug_backtrace() 函数生成一个 backtrace,返回关联数组的数组，可以参考文档
            $count = 0;
            foreach ($traces as $trace) {
                if (isset($trace['file'], $trace['line'])/* && strpos($trace['file'], YII_PATH) !== 0*/) {
                    $msg.= "\nin " . $trace['file'] . ' (' . $trace['line'] . ')';
                    if (++$count >= YII_TRACE_LEVEL) break;
                }
            }
        }
        $instance->_logger->log($msg, $level, $category); //调用_logger的方法处理日志
    }
    public static function flush() {
    	if (!self::$_instance)
    		throw new Exception(PhpLog::t('yii', 'PhpLog can not run flush before you write logs.'));
    	else 
    		self::$_instance->_logger->flush(true);
    }

    public static function t($category, $msg, $params = array()) {
    	echo $msg;
    }
}

