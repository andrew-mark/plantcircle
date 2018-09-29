<?php namespace ffinst;

use ffinst\tabs\FFISourcesTab;
use ffinst\tabs\FFIStreamsTab;
use ffinst\flow\LAAdminBase;
use ffinst\db\FFIDBMigrationManager;
use ffinst\flow\core\tabs\LAGeneralTab;
use ffinst\flow\core\tabs\LALicenseTab;
use ffinst\flow\core\tabs\LAAuthTab;
/**
 * Insta-Flow.
 *
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `FFI.php`
 *
 * @package   FFIAdmin
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      https://social-streams.com
 * @copyright 2014-2017 Looks Awesome
 */
class FFIAdmin extends LAAdminBase{
	/**
	 * Render the settings page for this plugin.
	 * @since 1.0.0
	 */
	public function display_plugin_admin_subpage() {
		$activated = $this->db->registrationCheck();
		
		$context = $this->context;
		$context['admin_page_title'] = esc_html( get_admin_page_title() );
		$context['options'] = FFI::get_instance($context)->get_options();
		$context['auth_options'] = FFI::get_instance($context)->get_auth_options();
		$this->db->dataInit();
		$context['streams'] = $this->db->streamsWithStatus();
		$context['sources'] = $this->db->sources();
		$context['activated'] = $activated;
		
		$tab_prefix = 'ffi';
		$context['form-action'] = '';
		$context['tabs'][] = new FFIStreamsTab();
		$context['tabs'][] = new FFISourcesTab();
		$context['tabs'][] = new LAGeneralTab($tab_prefix);
		$context['tabs'][] = new LAAuthTab($tab_prefix);
		$context['tabs'][] = new LALicenseTab($tab_prefix, $activated);
		
		$context['buttons-after-tabs'] = '<li id="request-tab"><span>Save changes</span> <i class="flaticon-paperplane"></i></li>';
		
		//TODO Add support ads addon
		//$context = apply_filters('ff_change_context', $context);
		
		include_once($context['root']  . 'views/admin.php');
	}
	
	protected function initPluginAdminPage(){
		$mm = new FFIDBMigrationManager($this->context);
		$mm->migrate();
		unset($mm);
	}
	
	protected function enqueueAdminStylesAlways($plugin_directory){
		wp_enqueue_style($this->getPluginSlug() .'-admin-icon-styles', $plugin_directory . 'css/admin-icon.css', array(), $this->context['version'] );
	}
	
	protected function enqueueAdminScriptsAlways($plugin_directory){		
	}
	
	protected function enqueueAdminStylesOnlyAtPluginPage($plugin_directory){
		wp_enqueue_style($this->getPluginSlug() .'-admin-styles', $plugin_directory . 'css/admin.css', array(), $this->context['version']);
		wp_enqueue_style($this->getPluginSlug() .'-colorpickersliders', $plugin_directory . 'css/jquery-colorpickersliders.css', array(), $this->context['version']);
		
		// Load web font
		wp_register_style('ff-fonts', '//fonts.googleapis.com/css?family=Montserrat:400,700|PT+Serif|Lato:300,400' );
		wp_enqueue_style( 'ff-fonts' );
	}
	
	protected function enqueueAdminScriptsOnlyAtPluginPage($plugin_directory){
		wp_enqueue_script( $this->getPluginSlug() . '-global-admin-script', $plugin_directory . 'js/global_admin.js', array( 'jquery' , 'backbone', 'underscore' ), $this->context['version']);
		
		// Enqueue scripts
		wp_enqueue_script( $this->getPluginSlug() . '-streams-script', $plugin_directory . 'js/streams.js', array( 'jquery' ), $this->context['version']);
		wp_enqueue_script( $this->getPluginSlug() . '-admin-script', $plugin_directory . 'js/admin.js', array( 'jquery', 'backbone', 'underscore' ), $this->context['version']);
		wp_localize_script($this->getPluginSlug() . '-admin-script', 'WP_FF_admin', array());
		wp_localize_script($this->getPluginSlug() . '-admin-script', 'isWordpress', (string)FF_USE_WP);
		wp_localize_script($this->getPluginSlug() . '-admin-script', '_ajaxurl', (string)$this->context['ajax_url']);
		wp_enqueue_script( $this->getPluginSlug() . '-zeroclipboard', $plugin_directory . 'js/zeroclipboard/ZeroClipboard.min.js', array( 'jquery' ), $this->context['version']);
		wp_enqueue_script( $this->getPluginSlug() . '-tinycolor', $plugin_directory . 'js/tinycolor.js', array( 'jquery' ), $this->context['version']);
		wp_enqueue_script( $this->getPluginSlug() . '-colorpickersliders', $plugin_directory . 'js/jquery.colorpickersliders.js', array( 'jquery' ), $this->context['version']);
	}
	
	protected function addPluginAdminSubMenu($displayAdminPageFunction){
		add_submenu_page(
			'flow-flow',
			'Grace Instagram',
			'Grace Instagram',
			'manage_options',
			$this->getPluginSlug() . '-admin',
			$displayAdminPageFunction
		);
	}
}