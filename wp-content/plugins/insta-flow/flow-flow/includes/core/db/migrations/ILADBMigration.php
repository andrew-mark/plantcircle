<?php namespace ffinst\flow\core\db\migrations;
use ffinst\flow\db\LADBManager;
use ffinst\flow\db\SafeMySQL;

if ( ! defined( 'WPINC' ) ) die;
/**
 * Flow-Flow
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
interface ILADBMigration {
	public function version();

	/**
	 * @param SafeMySQL $conn
	 * @param LADBManager $manager
	 */
	public function execute( $conn, $manager);
} 