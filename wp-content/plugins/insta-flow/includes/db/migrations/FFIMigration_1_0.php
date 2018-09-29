<?php namespace ffinst\db\migrations;

if ( ! defined( 'WPINC' ) ) die;

use ffinst\flow\core\db\migrations\ILADBMigration;

/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
class FFIMigration_1_0 implements ILADBMigration{

	public function version() {
		return '1.0';
	}
	
	public function execute( $conn, $manager){
		//Set start db version
	}
}