<?php namespace ffinst\db;

if ( ! defined( 'WPINC' ) ) die;

use ffinst\flow\core\db\LADBMigrationManager;

/**
 * Insta Flow.
 *
 * @package   Insta_Flow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright 2014-2017 Looks Awesome
 */
class FFIDBMigrationManager extends LADBMigrationManager{
	
	protected function migrations(){
		$result = array();
		foreach ( glob($this->context['root'] . 'includes/db/migrations/FFIMigration_*.php') as $filename ) {
			$result[] = 'ffinst\\db\\migrations\\' . basename($filename, ".php");
		}
		return $result;
	}
}