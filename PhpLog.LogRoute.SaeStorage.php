<?php
/**
 * Makes PhpLog.LogRoute.File works on the sae platform.
 * @author Gcaufy <gongweiyue@163.com>
 * @package PhpLog.LogRoute
 * @link http://www.madcoder.cn
 */
class CSaeStorageLogRoute extends CFileLogRoute
{

	public $domain = '';

	private $_saeStorage = null;


	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		parent::init();
		if (!$this->domain)
			throw new Exception(PhpLog::t('PhpLog.LogRoute.SaeStorage.logPath can not be empty.'));
		$this->_saeStorage = new SaeStorage();
	}

	/**
	 * Saves log messages in files.
	 * @param array $logs list of log messages
	 */
	public function processLogs($logs)
	{
		$saeStorage = $this->_saeStorage;
		$logFile=$this->getLogPath().DIRECTORY_SEPARATOR.$this->getLogFile();

		$storage = new SaeStorage();
		if(!$saeStorage->fileExists($this->domain, $logFile));
			$saeStorage->upload($this->domain, $logFile, '');
		$content = $saeStorage->read($this->domain, $logFile);
		foreach($logs as $log)
			$saeStorage->write($this->domain, $logFile, ($content ? $content : '' ) . $this->formatLogMessage($log[0],$log[1],$log[2],$log[3]));
	}
}
