<?php namespace ffinst;

use ffinst\flow\LABase;
use ffinst\flow\settings\FFSettingsUtils;
use ffinst\flow\settings\FFStreamSettings;
use ffinst\flow\db\FFDB;

if ( ! defined( 'WPINC' ) ) die;

class FFI extends LABase{
	public function initWPWidget(){
		register_widget(new FFIWPWidget($this->context));
	}
	
	public function initVCIntegration(){
		$table = $this->context['table_name_prefix'] . 'streams';
		$streams = FFDB::streams($table);
		
		$streams_dropdown = array();
		foreach ($streams as $stream){
			$value = unserialize( $stream['value']);
			$id = $value->id;
			$name = $stream['name'] == '' ? "Stream $id" : $stream['name'];
			$streams_dropdown[$name] = $id;
		}
		
		vc_map(array(
			'name' => __('Grace', 'insta-flow'),
			'description' => __('Instagram Feed', 'insta-flow'),
			'base' => 'vc_insta_flow',
			'class' => '',
			'category' => __('Social', 'insta-flow'),
			'icon' => 'grace-icon',
			'params' => array(
				// Source
				array(
					'type' => 'dropdown',
					'heading' => __('Stream', 'insta-flow'),
					'admin_label' => true,
					'param_name' => 'stream',
					'value' => $streams_dropdown,
					'description' => __('Choose stream to display', 'insta-flow')
				)
			)
		));
	}
	
	protected function __construct($context, $slug, $slug_down) {
		parent::__construct($context, $slug, $slug_down);
	}
	
	protected function getShortcodePrefix(){
		return 'grace';
	}
	
	protected function getLoadCacheUrl($streamId = null, $force = false){
		$ajax_url = $this->context['ajax_url'];
		return $ajax_url. "?action=insta_flow_load_cache&feed_id={$streamId}&force={$force}";
	}
	
	protected function getPublicContext($stream, $context){
		$context['moderation'] = false;
		if (isset($stream->feeds) && !empty($stream->feeds)){
			foreach ( $stream->feeds as $source ) {
				$moderation = FFSettingsUtils::YepNope2ClassicStyleSafe($source, 'mod', false);
				if ($moderation){
					$context['moderation'] = $moderation;
					break;
				}
			}
		}
		
		$settings = new FFStreamSettings($stream);
		$this->cache->setStream($settings, $context['moderation']);
		$context['stream'] = $stream;
		$context['hashOfStream'] = $this->cache->transientHash($stream->id);
		$context['seo'] = false;////$this->generalSettings->isSEOMode();
		$context['can_moderate'] = FF_USE_WP ? $this->generalSettings->canModerate() : ff_user_can_moderate();
		return $context;
	}
	
	protected function enqueueStyles(){
	}
	
	protected function enqueueScripts(){
		/*$opts =  $this->get_options();
		$plugins_url = plugins_url();
		$js_opts = array(
				'streams' => new \stdClass(),
				'open_in_new' => $opts['general-settings-open-links-in-new-window'],
				'filter_all' => __('Show All', 'flow-flow'),
				'filter_search' => __('Search', 'flow-flow'),
				'expand_text' => __('Expand', 'flow-flow'),
				'collapse_text' => __('Collapse', 'flow-flow'),
				'posted_on' => __('Posted on', 'flow-flow'),
				'show_more' => __('Show more', 'flow-flow'),
				'followers' => __('Followers', 'flow-flow'),
				'following' => __('Following', 'flow-flow'),
				'posts' => __('Posts', 'flow-flow'),
				'date_style' => $opts['general-settings-date-format'],
				'dates' => array(
						'Yesterday' => __('Yesterday', 'flow-flow'),
						's' => __('s', 'flow-flow'),
						'm' => __('m', 'flow-flow'),
						'h' => __('h', 'flow-flow'),
						'ago' => __('ago', 'flow-flow'),
						'months' => array(
								__('Jan', 'flow-flow'), __('Feb', 'flow-flow'), __('March', 'flow-flow'),
								__('April', 'flow-flow'), __('May', 'flow-flow'), __('June', 'flow-flow'),
								__('July', 'flow-flow'), __('Aug', 'flow-flow'), __('Sept', 'flow-flow'),
								__('Oct', 'flow-flow'), __('Nov', 'flow-flow'), __('Dec', 'flow-flow')
						),
				),
				'lightbox_navigate' => __('Navigate with arrow keys', 'flow-flow'),
				'server_time' => time(),
				'forceHTTPS' => $opts['general-settings-https'],
				'isAdmin' => function_exists('current_user_can') && current_user_can( 'manage_options' ),
				'ajaxurl' => $this->context['ajax_url'],
				'isLog' => isset($_REQUEST['fflog']) && $_REQUEST['fflog'] == 1,
				'plugin_base' => $plugins_url . '/' . $this->slug ,
				'plugin_ver' => $this->context['version']
		);
		
		wp_enqueue_script($this->slug . '-plugin-script', $plugins_url . '/' . $this->slug . '/js/require-utils.js', array('jquery'), $this->context['version']);
		wp_localize_script($this->slug . '-plugin-script', $this->getNameJSOptions(), $js_opts);*/
	}
	
	protected function getNameJSOptions(){
		return 'InstaFlowOpts';
	}
}
