<?php
/**
 * Makes PhpLog.LogRoute.Db works on the sae platform.
 * @author Gcaufy <gongweiyue@163.com>
 * @package PhpLog.LogRoute
 * @link http://www.madcoder.cn
 */
class CSaeDbLogRoute extends CDbLogRoute
{


	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		$db = isset($this->connection['db']) ? $this->connection['db'] : SAE_MYSQL_DB;
		$this->connection = array(
			'user' => SAE_MYSQL_USER,
			'password' => SAE_MYSQL_PASS,
			'host' => SAE_MYSQL_HOST_M,
			'port' => SAE_MYSQL_PORT,
			'db' => $db,
		);
		parent::init();
	}

}
