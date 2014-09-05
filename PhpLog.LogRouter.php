
<?php
/**
 * CLogRouter class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CLogRouter manages log routes that record log messages in different media.
 *
 * For example, a file log route {@link CFileLogRoute} records log messages
 * in log files. An email log route {@link CEmailLogRoute} sends log messages
 * to specific email addresses. See {@link CLogRoute} for more details about
 * different log routes.
 *
 * Log routes may be configured in application configuration like following:
 * <pre>
 * array(
 *     'preload'=>array('log'), // preload log component when app starts
 *     'components'=>array(
 *         'log'=>array(
 *             'class'=>'CLogRouter',
 *             'routes'=>array(
 *                 array(
 *                     'class'=>'CFileLogRoute',
 *                     'levels'=>'trace, info',
 *                     'categories'=>'system.*',
 *                 ),
 *                 array(
 *                     'class'=>'CEmailLogRoute',
 *                     'levels'=>'error, warning',
 *                     'emails'=>array('admin@example.com'),
 *                 ),
 *             ),
 *         ),
 *     ),
 * )
 * </pre>
 *
 * You can specify multiple routes with different filtering conditions and different
 * targets, even if the routes are of the same type.
 *
 * @property array $routes The currently initialized routes.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.logging
 * @since 1.0
 */
//require_once('PhpLog.LogRoute.Db.php');
//require_once('PhpLog.LogRoute.Email.php');
//require_once('PhpLog.LogRoute.Web.php');
require_once('PhpLog.LogRoute.php');
require_once('PhpLog.LogRoute.File.php');


class CLogRouter
{
	private $_routes=array();

	private $_phplog = null;

	/**
	 * Initializes this application component.
	 * This method is required by the IApplicationComponent interface.
	 */
	public function __construct($instance, $routes) {
    	$this->_phplog = $instance;
    	$this->_routes = $routes;
    	$this->init();
	}

	public function init()
	{
		foreach($this->_routes as $name=>$route)
		{
			$type = $route['type'];
			if (isset($route['enabled']) && $route['enabled'] === false){
				unset($this->_routes[$name]);
				continue;
			}
			require_once('PhpLog.LogRoute.' . $type . '.php');
			$class = 'C' . ucfirst($type) . 'LogRoute';
			unset($route['type']);
			$router = new $class($this->_phplog, $route);
			$router->init();
			$this->_routes[$name]=$router;
			//var_dump($route);
			//$route=Yii::createComponent($route);
			//$route->init();
			//$this->_routes[$name]=$route;
		}
		//Yii::getLogger()->attachEventHandler('onFlush',array($this,'collectLogs'));
		//Yii::app()->attachEventHandler('onEndRequest',array($this,'processLogs'));
	}

	/**
	 * @return array the currently initialized routes
	 */
	public function getRoutes()
	{
		return new CMap($this->_routes);
	}

	/**
	 * @param array $config list of route configurations. Each array element represents
	 * the configuration for a single route and has the following array structure:
	 * <ul>
	 * <li>class: specifies the class name or alias for the route class.</li>
	 * <li>name-value pairs: configure the initial property values of the route.</li>
	 * </ul>
	 */
	public function setRoutes($config)
	{
		foreach($config as $name=>$route)
			$this->_routes[$name]=$route;
	}

	/**
	 * Collects log messages from a logger.
	 * This method is an event handler to the {@link CLogger::onFlush} event.
	 * @param CEvent $event event parameter
	 */
	public function collectLogs($dumpLogs)
	{
		$logger=$this->_phplog->_logger;
		//$dumpLogs=isset($event->params['dumpLogs']) && $event->params['dumpLogs'];
		foreach($this->_routes as $route)
		{
			if($route->enabled)
				$route->collectLogs($logger, $dumpLogs);
		}
	}

	/**
	 * Collects and processes log messages from a logger.
	 * This method is an event handler to the {@link CApplication::onEndRequest} event.
	 * @param CEvent $event event parameter
	 * @since 1.1.0
	 */
	public function processLogs($event)
	{
		$logger=Yii::getLogger();
		foreach($this->_routes as $route)
		{
			if($route->enabled)
				$route->collectLogs($logger,true);
		}
	}
}
