<?php
/**
 * It's the yii log module, removed the dependence from yii framework, you can use it in any of your project.
 * @author Gcaufy <gongweiyue@163.com>
 * @link http://www.madcoder.cn/
 * @copyright Copyright &copy; 2014
 * @license http://www.madcoder.cn/license/
 */

require_once('PhpLog.Logger.php');
require_once('PhpLog.LogRouter.php');

defined('PHPLOG_BEGIN_TIME') or define('PHPLOG_BEGIN_TIME',microtime(true));
defined('PHPLOG_DIR') or define('PHPLOG_DIR', dirname(__FILE__));

class PhpLog {

	public static $_instance = null;

	private $_logger = null;
	private $_router = null;
	private $_traceLevel = 3;
	private $_debug = true;
	private $_enable = true;

	public function __construct() {
		$config = require(PHPLOG_DIR . DIRECTORY_SEPARATOR . 'config.php');

		if (isset($config['enable']))
			$this->_enable = $config['enable'];

		if ($this->_enable) {
			if (isset($config['traceLevel']))
				$this->_traceLevel = $config['traceLevel'];
			if (isset($config['debug']))
				$this->_debug = $config['debug'];

			$this->_router = new CLogRouter($this, $config['routes']);
			unset($config['routes']);
			$this->_logger = new CLogger($this, $config);
		}

	}

    public static function log($msg, $level = CLogger::LEVEL_INFO, $category = 'application') {
    	if (self::$_instance === null)
    		self::$_instance = new PhpLog();
    	$instance = self::$_instance;
    	
    	if ($instance->_enable === false) {
    		return false;
    	}

        if ($instance->_debug && $instance->_traceLevel > 0 && $level !== CLogger::LEVEL_PROFILE) {
            // $this->_traceLevel 设置backtrace 显示的内容条数，
            //这个常量会在debug_backtrace 函数返回信息中，获取指定条数，
            //如果为0(默认) 则为全部显示
            $traces = debug_backtrace();
            //debug_backtrace() 函数生成一个 backtrace,返回关联数组的数组，可以参考文档
            $count = 0;
            foreach ($traces as $trace) {
                if (isset($trace['file'], $trace['line'])/* && strpos($trace['file'], YII_PATH) !== 0*/) {
                    $msg.= "\nin " . $trace['file'] . ' (' . $trace['line'] . ')';
                    if (++$count >= $instance->_traceLevel) break;
                }
            }
        }
        $instance->_logger->log($msg, $level, $category); //调用_logger的方法处理日志
    }
    public static function flush() {
    	$instance = self::$_instance;
    	if ($instance->_enable === false) {
    		return false;
    	}
    	if (!$instance)
    		throw new Exception(PhpPhpLog::t('PhpLog can not run flush before you write logs.'));
    	else 
    		$instance->_logger->flush(true);
    }

    public static function t($msg, $params = array()) {
    	return strtr($msg, $params);
    }

    public function getRouter() {
    	return $this->_router;
    }

    public function getLogger() {
    	return $this->_logger;
    }
}

