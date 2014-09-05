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
/**
 * CDbLogRoute class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
/**
 * CDbLogRoute stores log messages in a database table.
 *
 * To specify the database table for storing log messages, set {@link logTableName} as
 * the name of the table and specify {@link connectionID} to be the ID of a {@link CDbConnection}
 * application component. If they are not set, a SQLite3 database named 'log-YiiVersion.db' will be created
 * and used under the application runtime directory.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.logging
 * @since 1.0
 */
class CDbLogRoute extends CLogRoute {
    /**
     * @var string the ID of CDbConnection application component. If not set, a SQLite database
     * will be automatically created and used. The SQLite database file is
     * <code>protected/runtime/log-YiiVersion.db</code>.
     */
    public $connection;
    /**
     * @var string the name of the DB table that stores log content. Defaults to 'YiiLog'.
     * If {@link autoCreateLogTable} is false and you want to create the DB table manually by yourself,
     * you need to make sure the DB table is of the following structure:
     * <pre>
     *  (
     *		id       INTEGER NOT NULL PRIMARY KEY,
     *		level    VARCHAR(128),
     *		category VARCHAR(128),
     *		logtime  INTEGER,
     *		message  TEXT
     *   )
     * </pre>
     * Note, the 'id' column must be created as an auto-incremental column.
     * In MySQL, this means it should be <code>id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY</code>;
     * In PostgreSQL, it is <code>id SERIAL PRIMARY KEY</code>.
     * @see autoCreateLogTable
     */
    public $logTableName = 'YiiLog';
    /**
     * @var boolean whether the log DB table should be automatically created if not exists. Defaults to true.
     * @see logTableName
     */
    public $autoCreateLogTable = true;
    /**
     * @var CDbConnection the DB connection instance
     */
    private $_db;
    /**
     * Initializes the route.
     * This method is invoked after the route is created by the route manager.
     */
    public function init() {
        parent::init();$this->_db = null;
        if ($this->autoCreateLogTable) {
            $db = $this->getDbConnection();
            if(!$db->query("delete from {$this->logTableName} where 0 = 1") && $db->error) {
            	$this->createLogTable($db, $this->logTableName);
            }
        }
    }
    /**
     * Creates the DB table for storing log messages.
     * @param CDbConnection $db the database connection
     * @param string $tableName the name of the table to be created
     */
    protected function createLogTable($db, $tableName) {
    	$sql = "
    	CREATE TABLE `sys_log` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`level` VARCHAR(128) NULL DEFAULT NULL,
			`category` VARCHAR(128) NULL DEFAULT NULL,
			`logtime` INT(11) NULL DEFAULT NULL,
			`message` TEXT NULL,
			PRIMARY KEY (`id`)
		)
		COLLATE='utf8_general_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=1;
		";
		if(!$db->query($sql) && $db->error) {
			throw new Exception(PhpLog::t('yii', 'CDbLogRoute.connection get an MYSQL issue "{err}".', 
            		array('{err}' => $db->error)
        	));
        }
    }
    /**
     * @return CDbConnection the DB connection instance
     * @throws CException if {@link connectionID} does not point to a valid application component.
     */
    protected function getDbConnection() {
        if ($this->_db !== null) return $this->_db;
        elseif ($this->connection) {
            $dbconfig = $this->connection;
            $this->_db = @new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['db'], isset($dbconfig['port']) ? $dbconfig['port'] : 3306);
            if(mysqli_connect_errno()){
            	throw new Exception(PhpLog::t('yii', 'CDbLogRoute.connection get an MYSQL issue "{err}".', 
            		array('{err}' => mysqli_connect_error(),)
            	));
			}
            if ($this->_db) {
            	return $this->_db;
            }
        }
    }
    /**
     * Stores log messages into database.
     * @param array $logs list of log messages
     */
    protected function processLogs($logs) {
    	$sql = "insert into {$this->logTableName} (level, category, logtime, message) values(?,?,?,?)";
  		$stmt = $this->_db->prepare($sql);
  		$stmt->bind_param('ssis', $level, $category, $logtime, $message);
  		foreach ($logs as $i => $log) {
  			$level = $log[1];
  			$category = $log[2];
  			$logtime = (int)$log[3];
  			$message = $log[0];
			$stmt->execute();
  		}
		$stmt->close();
    }

    function __destruct() {
    	$this->_db->close();
    }
}

