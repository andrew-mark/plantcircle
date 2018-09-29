<?php namespace ffinst\tabs;

if ( ! defined( 'WPINC' ) ) die;

use ffinst\flow\core\tabs\LATab;

/**
 * FlowFlow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
class FFISourcesTab implements LATab {
	public function __construct() {
	}

	public function id() {
		return 'sources-tab';
	}

	public function flaticon() {
		return 'flaticon-error';
	}

	public function title() {
		return 'Feeds';
	}

	public function includeOnce( $context ) {
		?>
		<script>
			var feeds = <?php echo json_encode($context['sources'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
			if (_.isArray(feeds)) feeds = {};
		</script>
		<div class="section-content" id="sources-cont" data-tab="sources-tab">
			<div class="section-sources" id="sources-list" data-view-mode="sources-list">
				<div class="section" id="feeds-list-section">
					<h1 class="desc-following"><span>List of feeds</span> <span class="admin-button green-button button-add">Create feed</span></h1>
					<p class="desc">Each feed can be connected to multiple streams. Cache for feed is being built immediately on creation. You can disable any feed and it will be disabled in all streams where it's connected. Feeds with errors are automatically disabled. <a class="ff-pseudo-link" href="#">Show only error feeds</a>.</p>
					<div id="feeds-view">
						<table class="feeds-list">
							<thead>
							<tr>
								<th></th>
								<th></th>
								<th>Feed</th>
								<th>Updated</th>
								<th>Live</th>
							</tr>
							</thead>
							<tbody id="feeds-list">
							<?php

							foreach ($context['sources'] as $feed) {
								$id = $feed['id'];
								$settings = '';

								$settingArr = array();
								if (isset($feed['content'])) {
									$settingArr['content'] = $feed['content'];
								} else {
									if (isset($feed['category-name']) && !empty($feed['category-name'])) {
										$settingArr['content'] = $feed['category-name'];
									} else {
										$settingArr['content'] = $feed['wordpress-type'];
									}
								}
								if (isset($feed['timeline-type'])) $settingArr['timeline-type'] = $feed['timeline-type'];
								if (isset($feed['mod']) && $feed['mod'] !== 'nope') $settingArr['mod'] = $feed['mod'];
								if (isset($feed['location-meta'])) $settingArr['location-meta'] = $feed['location-meta'];

								$content = $settingArr['content'];
								$type = $settingArr['timeline-type'];

								if(isset($feed['location-meta']) && isset($feed['location-meta']->name)){
                                    $content = $feed['location-meta']->name;
                                }

                                if (!empty($content)) {
                                    $v = str_replace('-', ' ', stripslashes($content));
                                    $v = str_replace('_timeline', '', $v);
                                    $v = str_replace('http://', '', $v);
                                    $v = str_replace('https://', '', $v);

                                    if ( strlen($v) > 20) {
                                        $v = substr( $v , 0, 20 ) . '...';
                                    }

                                    switch($type){
                                        case 'user_timeline':
                                            $icon = '<i class="flaticon-feed_type_user"></i>';
                                            break;
                                        case 'likes':
                                        case 'liked':
                                            $icon = '<i class="flaticon-feed_type_like"></i>';
                                            break;
                                        case 'tag':
                                            $icon = '<i class="flaticon-feed_type_hash"></i>';
                                            break;
                                        case 'location':
                                        case 'coordinates':
                                            $icon = '<i class="flaticon-feed_type_loc"></i>';
                                            break;
                                    }

                                    $settings = '<span><span class="highlight">' . $icon . $v . '</span></span>';
									if(isset($settingArr['mod'])){
										$settings .= '<span class="highlight grey">moderated</span>';
									}
                                }

								// it will be done via JS
								// $status = (isset($feed['status']) && $feed['status'] == 1) ? 'cache-status-ok' : 'cache-status-error';
								//$last_update = $feed['last_update'] == 0 ? '' : FFFeedUtils::classicStyleDate($feed['last_update']);
								$enabled = isset($feed['enabled']) ? ($feed['enabled'] === 'yep'? true : false) : true;

								$fc = $feed['cache_lifetime'];
								if ($fc == 5) {
									$int = 'Every 5 min';
								} else if ($fc == 30) {
									$int = 'Every 30 min';
								} else if ($fc == 60) {
									$int = 'Every hour';
								} else if ($fc == 360) {
									$int = 'Every 6 hours';
								} else if ($fc == 1440) {
									$int = 'Once a day';
								} else if ($fc == 10080) {
									$int = 'Once a week';
								}

								$feed['last_update'] = $feed['last_update'] === 'N/A' ? $feed['last_update'] : $feed['last_update'] . ' (' . $int . ')';

								echo
									'<tr data-uid="' . $id . '" data-network="'. $feed['type'] .'" class="' . ( $enabled ? 'feed-enabled' : '' ) . '">
										<td class="controls">
										    <i class="flaticon-tool_more" data-action="toggle-dropdown"></i>
										    <ul class="feed-dropdown-menu"><li data-action="filter">Filter feed</li><li data-action="cache">Rebuild cache</li></ul>
										    <i class="flaticon-tool_edit" data-action="edit"></i> 
										    <i class="flaticon-tool_delete" data-action="delete"></i>
                                        </td>
										<td class="td-status"><span class="cache-status-' . ($feed['status'] == 1 ? 'ok' : 'error') . '"></span></td>
										<td class="td-info">' . $settings . '</td>
										<td class="td-last-update">' . $feed['last_update'] . '</td>
										<td class="td-enabled"><label for="feed-enabled-' . $id .'"><input ' . ( $enabled ? 'checked' : '' ) . ' id="feed-enabled-' . $id .'" class="switcher" type="checkbox" name="feed-enabled-' . $id .'" value="yep"><div><div></div></div></label></td>
									</tr>';
							}

							if (empty($context['sources'])) {
								echo '<tr class="empty-row"><td class="empty-cell" colspan="5">Please add at least one feed</td></tr>';
							}

							?>
							</tbody>
						</table>
						<div class="holder"></div>
						<div class="popup">
							<div class="section">
								<i class="popupclose flaticon-close-4"></i>
								<div class="networks-choice add-feed-step">

								</div>
								<div class="networks-content  add-feed-step">
									<div id="feed-views"></div>
									<div id="filter-views"></div>
									<p class="feed-popup-controls add">
										<span id="feed-sbmt-1"
											  class="admin-button green-button submit-button">Add feed</span><span
											class="space"></span>
									</p>
									<p class="feed-popup-controls edit">
										<span id="feed-sbmt-2"
											  class="admin-button green-button submit-button">Save changes</span>
									</p>
									<p class="feed-popup-controls enable">
										<span id="feed-sbmt-3"
											  class="admin-button blue-button submit-button">Save & Enable</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php include($context['root']  . 'views/footer.php'); ?>
		</div>
	<?php
	}
}