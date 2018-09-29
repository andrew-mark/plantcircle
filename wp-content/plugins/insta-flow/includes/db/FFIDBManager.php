<?php namespace ffinst\db;

if ( ! defined( 'WPINC' ) ) die;

use ffinst\flow\db\FFDB;
use ffinst\flow\db\LADBManager;
use ffinst\flow\social\FFFeedUtils;
use ffinst\flow\settings\FFSettingsUtils;

/**
 * Insta Flow.
 *
 * @package   Insta_Flow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright 2014-2017 Looks Awesome
 */
class FFIDBManager extends LADBManager{
	
	public function __construct($context) {
		parent::__construct($context);
	}
	
	//OAuth endpoint
	public final function social_auth(){
		if (isset($_REQUEST['type'])){
			$fieldName = $_REQUEST['type'];
			$options = $this->getOption('options', true);
			$options[$fieldName] = $_REQUEST[$fieldName];
			$this->setOption('options', $options, true);
			
			header('Location: ' . admin_url('admin.php?page=insta-flow-admin'), true, 301);
		}
		die();
	}
	
	protected function customizeResponce(&$responce){}
	
	protected function clean_cache($options){
		$force_load_cache = false;
		
		$general = $options['flow_flow_options'];
		$old = $this->getOption('options', true);
		
		if (sizeof($old) > 0){
			if ($general['instagram_access_token'] != $old['instagram_access_token']){
				$this->cleanByFeedType('instagram');
				$force_load_cache = true;
			}
		} else if (trim($general['instagram_access_token']) == ''){
			$this->cleanByFeedType('instagram');
			$force_load_cache = true;
		}

		return $force_load_cache;
	}

    protected function getFeedData($url, $timeout = 60, $header = false, $log = true){
        /** @var LADBManager $db */
        $use = true;
        $useIpv4 = true;
        return FFFeedUtils::getFeedData($url, $timeout, $header, $log, $use, $useIpv4);
    }

    private function getLocationMeta($locationId){
        $original = $this->getGeneralSettings()->original();
        $accessToken = $original['instagram_access_token'];

        if(!isset($accessToken)){
            return array();
        }

        $request = $this->getFeedData("https://api.instagram.com/v1/locations/{$locationId}/?access_token={$accessToken}");
        $json = json_decode($request['response']);
        if (!is_object($json) || (is_object($json) && sizeof($json->data) == 0)) {
            if (isset($request['errors']) && is_array($request['errors'])){
                foreach ( $request['errors'] as $error ) {
                    $error['type'] = 'instagram';
                    //TODO $this->filterErrorMessage
                    $this->errors[] = $error;
                    throw new \Exception();
                }
            }
            else {
                $this->errors[] = array('type'=>'instagram', 'message' => 'Bad request, access token issue', 'url' => "https://api.instagram.com/v1/locations/{$locationId}&access_token={$accessToken}");
                throw new \Exception();
            }
            return $locationId;
        }
        else {
            if($json->data){
                return $json->data;
            }else{
                $this->errors[] = array(
                    'type' => 'instagram',
                    'message' => 'Location not found',
                    'url' => "https://api.instagram.com/v1/locations/{$locationId}&access_token={$accessToken}"
                );
                throw new \Exception();
            }
        }
    }

    public function modifySource($source, $changed_content = true){
        $id = $source['id'];
        $enabled = $source['enabled'];
        $cache_lifetime = $source['cache_lifetime'];
        $status = isset($source['status']) ? intval($source['status']) : 0;

        unset($source['id']);
        unset($source['enabled']);
        unset($source['last_update']);
        unset($source['cache_lifetime']);
        if (isset($source['errors'])) unset($source['errors']);
        if (isset($source['status'])) unset($source['status']);
        if (isset($source['system_enabled'])) unset($source['system_enabled']);

        // get Location meta
        if($source['type'] == 'instagram' && $source['timeline-type'] == 'location'){
            $source['location-meta'] = $this->getLocationMeta($source['content']);
        }

        $in = array(
            'settings' => serialize((object)$source),
            'enabled' => (int)FFSettingsUtils::YepNope2ClassicStyle($enabled, true),
            'system_enabled' => (int)FFSettingsUtils::YepNope2ClassicStyle($enabled, true),
            'last_update' => 0,
            'changed_time' => time(),
            'cache_lifetime' => $cache_lifetime,
            'status' => $status
        );
        $up = array(
            'settings' => serialize((object)$source),
            'enabled' => (int)FFSettingsUtils::YepNope2ClassicStyle($enabled, true),
            'system_enabled' => (int)FFSettingsUtils::YepNope2ClassicStyle($enabled, true),
            'cache_lifetime' => $cache_lifetime
        );
        if ($changed_content) $up['last_update'] =  '0';
        try {
            if ( false === FFDB::conn()->query( 'INSERT INTO ?n SET `feed_id`=?s, ?u ON DUPLICATE KEY UPDATE ?u',
                    $this->cache_table_name, $id, $in, $up ) ) {
                throw new \Exception();
            }
            FFDB::commit();
        }
        catch (\Exception $e){
            FFDB::rollback();
        }
    }
}