<?php
/**
 * CWebLogRoute shows the log content in Web page.
 *
 * The log content can appear either at the end of the current Web page
 * or in FireBug console window (if {@link showInFireBug} is set true).
 *
 * @author Qiang Xue <qiang.xue@gmail.com> / Gcaufy <gongweiyue@163.com>
 * @package PhpLog.LogRoute
 * @link http://www.madcoder.cn
 */
class CWebLogRoute extends CLogRoute
{
	/**
	 * @var boolean whether the log should be displayed in FireBug instead of browser window. Defaults to false.
	 */
	public $showInFireBug=false;
	/**
	 * @var boolean whether the log should be ignored in FireBug for ajax calls. Defaults to true.
	 * This option should be used carefully, because an ajax call returns all output as a result data.
	 * For example if the ajax call expects a json type result any output from the logger will cause ajax call to fail.
	 */
	public $ignoreAjaxInFireBug=true;
	/**
	 * @var boolean whether the log should be ignored in FireBug for Flash/Flex calls. Defaults to true.
	 * This option should be used carefully, because an Flash/Flex call returns all output as a result data.
	 * For example if the Flash/Flex call expects an XML type result any output from the logger will cause Flash/Flex call to fail.
	 * @since 1.1.11
	 */
	public $ignoreFlashInFireBug=true;
	/**
	 * @var boolean whether the log should be collapsed by default in Firebug. Defaults to false.
	 * @since 1.1.13.
	 */
	public $collapsedInFireBug=false;

	/**
	 * Displays the log messages.
	 * @param array $logs list of log messages
	 */
	public function processLogs($logs)
	{
		$this->render('log',$logs);
	}

	/**
	 * Renders the view.
	 * @param string $view the view name (file name without extension). The file is assumed to be located under framework/data/views.
	 * @param array $data data to be passed to the view
	 */
	protected function render($view,$data)
	{
		$isAjax=$this->isAjax();
		$isFlash=$this->isFlash();

		if($this->showInFireBug)
		{
			// do not output anything for ajax and/or flash requests if needed
			if($isAjax && $this->ignoreAjaxInFireBug || $isFlash && $this->ignoreFlashInFireBug)
				return;
			$view.='-firebug';
		}
		$viewFile=dirname(__FILE__).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$view.'.php';
		include($viewFile);
	}

	protected function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
	}

	protected function isFlash() {
		isset($_SERVER['HTTP_USER_AGENT']) && (stripos($_SERVER['HTTP_USER_AGENT'],'Shockwave')!==false || stripos($_SERVER['HTTP_USER_AGENT'],'Flash')!==false);
	}

}