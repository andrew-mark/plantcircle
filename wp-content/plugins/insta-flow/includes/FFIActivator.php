<?php namespace ffinst;

use ffinst\flow\core\LAActivatorBase;
use ffinst\db\FFIDBMigrationManager;
use ffinst\flow\settings\FFSnapshotManager;
use ffinst\db\FFIDBManager;

class FFIActivator extends LAActivatorBase{

	protected function checkPlugin(){
		$mm = new FFIDBMigrationManager($this->context);
		$mm->migrate();
		unset($mm);
	}

	protected function initContext($file){
		/** @var wpdb $wpdb */
		$wpdb = $GLOBALS['wpdb'];

		$context = array(
				'root'              => plugin_dir_path($file),
				'slug'              => 'insta-flow',
				'slug_down'         => 'insta_flow',
				'plugin_url'        => plugin_dir_url(dirname($file).'/'),
				'admin_url'         => admin_url('admin-ajax.php'),
				'table_name_prefix' => $wpdb->base_prefix . 'ffi_',
				'version'			=> '1.1.5',
				'faq_url' 			=> 'http://docs.social-streams.com/',
				'json_url' 			=> 'http://flow.looks-awesome.com/service/news/news.json',
				'json_file' 		=> 'news.json',
				'count_posts_4init'	=> 100
		);
		$context['db_manager'] = new FFIDBManager($context);
		return $context;
	}

	protected function checkEnvironment(){
		if(version_compare(PHP_VERSION, '5.3.0') == -1){
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( '<b>Grace</b> plugin requires PHP version 5.3.0 or higher. Pls update your PHP version or ask hosting support to do this for you, you are using old and unsecure one' );
		}

		if(!function_exists('curl_version')){
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( '<b>Grace</b> plugin requires curl extension for php. Please install/enable this extension or ask your hosting to help you with this.' );
		}

		if(!function_exists('mysqli_connect')){
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( '<b>Grace</b> plugin requires mysqli extension for MySQL. Please install/enable this extension on your server or ask your hosting to help you with this. <a href="http://php.net/manual/en/mysqli.installation.php">Installation guide</a>' );
		}
	}

	protected function singleSiteActivate(){
		$this->checkPlugin();
	}

	protected function singleSiteDeactivate(){
		wp_clear_scheduled_hook( 'ffi_load_cache' );
		wp_clear_scheduled_hook( 'ffi_load_cache_4disabled' );
	}

	protected function beforePluginLoad(){
		$this->setContextValue('ajax_url', admin_url('admin-ajax.php'));

		new FFIUpdater($this->context);

		//parent::beforePluginLoad();
	}

	protected function registrationCronActions(){
		$this->addCronInterval('minute', array('interval' => MINUTE_IN_SECONDS, 'display' => 'Once Minute'));
		$this->addCronInterval('sex_hours', array('interval' => MINUTE_IN_SECONDS * 60 * 6, 'display' => 'Sex hours'));
		add_filter('cron_schedules', array($this, 'getCronIntervals'));

		$time = time();
		$ffi = FFI::get_instance($this->context);

		add_action('ffi_load_cache', array($ffi, 'refreshCache'));
		if(false == wp_next_scheduled( 'ffi_load_cache' )){
			wp_schedule_event( $time, 'minute', 'ffi_load_cache' );
		}

		add_action('ffi_load_cache_4disabled', array($ffi, 'refreshCache4Disabled'));
		if(false == wp_next_scheduled( 'ffi_load_cache_4disabled' )){
			wp_schedule_event( $time, 'sex_hours', 'ffi_load_cache_4disabled' );
		}
	}

	protected function registrationAjaxActions(){
		$ffi = FFI::get_instance($this->context);
		$ffadmin = new FFIAdmin($this->context);
		$dbm = $this->context['db_manager'];
		$slug_down= $this->context['slug_down'];

		add_action('wp_ajax_' . $slug_down . '_fetch_posts', array( $ffi, 'processAjaxRequest'));
		add_action('wp_ajax_nopriv_' . $slug_down . '_fetch_posts', array( $ffi, 'processAjaxRequest'));
		add_action('wp_ajax_' . $slug_down . '_moderation_apply_action', array( $ffi, 'moderation_apply'));
		add_action('wp_ajax_' . $slug_down . '_load_cache', array( $ffi, 'processAjaxRequestBackground'));
		add_action('wp_ajax_nopriv_' . $slug_down . '_load_cache', array( $ffi, 'processAjaxRequestBackground'));
		add_action('wp_ajax_' . $slug_down . '_load_comments', array( $ffi, 'processAjaxRequestBackground'));
		add_action('wp_ajax_nopriv_' . $slug_down . '_load_comments', array( $ffi, 'processAjaxRequestBackground'));
		add_action('wp_ajax_' . $slug_down . '_load_carousel', array( $ffi, 'loadCarousel'));
		add_action('wp_ajax_nopriv_' . $slug_down . '_load_carousel', array( $ffi, 'loadCarousel'));

		add_action('wp_ajax_' . $slug_down . '_social_auth',			array( $dbm, 'social_auth' ));
		add_action('wp_ajax_' . $slug_down . '_save_sources_settings',	array( $dbm, 'save_sources_settings' ));
		add_action('wp_ajax_' . $slug_down . '_get_stream_settings',	array( $dbm, 'get_stream_settings' ));
		add_action('wp_ajax_' . $slug_down . '_ff_save_settings',		array( $dbm, 'ff_save_settings_fn' ));
		add_action('wp_ajax_' . $slug_down . '_save_stream_settings',	array( $dbm, 'save_stream_settings' ));
		add_action('wp_ajax_' . $slug_down . '_create_stream',			array( $dbm, 'create_stream' ));
		add_action('wp_ajax_' . $slug_down . '_clone_stream',			array( $dbm, 'clone_stream' ));
		add_action('wp_ajax_' . $slug_down . '_delete_stream', 			array( $dbm, 'delete_stream' ));

		add_action('wp_ajax_' . $slug_down . '_get_admin_page', array( $ffadmin, 'get_plugin_admin_page' ));

		$manager = new FFSnapshotManager($this->context);
		add_action('wp_ajax_create_backup',  array( $manager, 'processAjaxRequest'));
		add_action('wp_ajax_restore_backup', array( $manager, 'processAjaxRequest'));
		add_action('wp_ajax_delete_backup',  array( $manager, 'processAjaxRequest'));

		if (!FF_USE_WP_CRON){
			add_action('wp_ajax_' . $slug_down . '_refresh_cache', array($ffi, 'refreshCache'));
			add_action('wp_ajax_nopriv_' . $slug_down . '_refresh_cache', array($ffi, 'refreshCache'));
		}
	}

	protected function renderAdminSide(){
		new FFIAdmin($this->context);
	}

	protected function renderPublicSide(){
		$ffi = FFI::get_instance($this->context);
		add_action( 'init', array($ffi,		'register_shortcodes'));
		add_action( 'init', array($ffi,		'load_plugin_textdomain'));
		add_action( 'wp_enqueue_scripts',	array( $ffi, 'enqueue_scripts' ));
		add_action( 'wpmu_new_blog',		array( $ffi, 'activate_new_site' ));
	}

	protected function afterPluginLoad(){
		$ffi = FFI::get_instance($this->context);
		add_action( 'widgets_init', array($ffi, 'initWPWidget'));
		add_action( 'vc_before_init', array($ffi, 'initVCIntegration'));
	}
}
