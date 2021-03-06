<?php

mbs_import('common', 'CMultiRowControl');

class CUserDepMemberControl extends CMultiRowControl {
	private static $instance = null;
	
	protected function __construct($db, $cache, $primarykey = null, $secondKey = null){
		parent::__construct($db, $cache, $primarykey, $secondKey);
	}
	
	/**
	 *
	 * @param CAppEnvironment $mbs_appenv
	 * @param CDbPool $dbpool
	 * @param CMemcachePool $mempool
	 * @param string $primarykey
	 */
	static function getInstance($mbs_appenv, $dbpool, $mempool, $primarykey = null){
		if(empty(self::$instance)){
			try {
				$memconn = $mempool->getConnection();
				self::$instance = new CUserDepMemberControl(
						new CMultiRowOfTable($dbpool->getDefaultConnection(),
								mbs_tbname('user_department_member'), 'dep_id', $primarykey, 'user_id'),
						$memconn ? new CMultiRowOfCache($memconn, $primarykey, 'CUserDepMemberControl') : null
				);
			} catch (Exception $e) {
				throw $e;
			}
		}
		self::$instance->setPrimaryKey($primarykey);
		
		return self::$instance;
	}
} 

?>