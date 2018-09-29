<?php namespace ffinst;
if ( ! defined( 'WPINC' ) ) die;

use ffinst\flow\core\LARemoteUpdater;

/**
 * Grace
 *
 * @package   Grace
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright 2017 Looks Awesome
 */
class FFIUpdater extends LARemoteUpdater{
	
	protected function getUrlToPluginMetafileJson(){
		return 'http://flow.looks-awesome.com/service/update/insta-flow.json';
	}
	
	protected function getPlugin($info){
		$plugin = array(
				'name'              => $info->plugin["name"],
				'slug'              => $info->basename,
				'plugin'            => $info->basename . '/' . $info->basename . '.php',
				'version'           => $info->plugin['version'],
				'author'            => $info->author["name"],
				'author_profile'    => $info->author["url"],
				'last_updated'      => $info->plugin['published_at'],
				'homepage'          => $info->plugin["url"],
				'short_description' => $info->plugin["description"],
				'sections'          => array(
						'description'   => $info->plugin["description"],
						'changelog'       => $info->plugin["changelog"],
				)
		);
		if (isset($info->plugin['download_url'])) $plugin['download_link'] = $info->plugin['download_url'];
		return (object) $plugin;
	}
	
	protected function getPluginWithNewVersion($info){
		$plugin = array();
		$plugin['url'] = $info->plugin["url"];
		$plugin['slug'] = 'insta-flow';
		$plugin['new_version'] = $info->plugin['version'];
		$plugin['plugin'] = 'insta-flow/insta-flow.php';
		if (isset($info->plugin['download_url'])) $plugin['package'] = $info->plugin['download_url'];
		return (object) $plugin;
	}
}