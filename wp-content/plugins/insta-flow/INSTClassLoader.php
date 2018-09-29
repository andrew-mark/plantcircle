<?php if ( ! defined( 'WPINC' ) ) die;
/**
 * FlowFlow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
class INSTClassLoader {
	private static $instance = null;
	public static function get($root = null, $url = null) {
		if(self::$instance == null) {
			self::$instance = new INSTClassLoader($root, $url);
		}
		return self::$instance;
	}
	
	private $url;
	private $root;
	
	private function __construct($root, $url) {
		$this->url  = $url;
		$this->root = $root;
	}
	
	public function loadClass($className) {
		if (0 === strpos($className, 'ffinst')) {
			$path = plugin_dir_path( __FILE__ );
			if (strpos($className, 'flow') > 0){
				$cls = str_replace('ffinst\flow', $path . 'flow-flow/includes', $className);
			}
			else {
				$cls = str_replace('ffinst', $path . 'includes', $className);
			}
			$path = str_replace('\\', DIRECTORY_SEPARATOR, $cls) . '.php';
			/** @noinspection PhpIncludeInspection */
			require_once($path);
		}
//		else if (0 === strpos($className, 'flow\\')){
//			$path = str_replace('insta-flow', 'flow-flow', $this->root) . 'includes';
//			$cls = str_replace('flow', $path, $className);
//			$path = str_replace('\\', DIRECTORY_SEPARATOR, $cls) . '.php';
//			/** @noinspection PhpIncludeInspection */
//			require_once($path);
//		}
	}
	
	public function register($with_config = false) {
		spl_autoload_register(array(self::get(), 'loadClass'));
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getRoot() {
		return $this->root;
	}
}