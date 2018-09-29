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

class FFIStreamsTab implements LATab{
	public function __construct() {
	}

	public function id() {
		return 'streams-tab';
	}

	public function flaticon() {
		return 'flaticon-ctrl-left';
	}

	public function title() {
		return 'Streams';
	}

	public function includeOnce( $context ) {
		$arr = $context['streams'];

		$export = array();
		foreach ($arr as $stream) {

			$item = array();

			foreach ($stream as $key => $value) {
                if ($key !== 'value') {
					if ($key === 'error') {
						$item['error'] = true;
					} else {
						if ($key === 'css') {
							$value = str_replace('"', "'", $value);
						}
						$item[$key] = $value;
					}
				}
			}

			$export[] = $item;
		}
//		debug
//		$export[0]['css'] = '';
//		$export[0]['heading'] = '';
		?>
		<script>
			var streams = <?php echo json_encode($export, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
		</script>
		<div class="section-content" id="streams-cont" data-tab="streams-tab">
			<div class="section-stream" id="streams-list" data-view-mode="streams-list">

				<div class="section" id="streams-list-section">
					<h1 class="desc-following"><span>List of your streams</span> <span class="admin-button green-button button-add">create stream</span></h1>
					<p class="desc">Here is a list of your streams. Edit them to change styling or to add/remove social feeds. Status means all connected feeds are loaded or not.</p>
					<table>
						<thead>
						<tr>
							<th></th>
							<th></th>
							<th>Stream</th>
							<th>Attached Feeds</th>
							<?php
							if (FF_USE_WP) echo '<th>Shortcode</th>';
							else echo '<th>ID</th><th>Status</th>';
							?>
						</tr>
						</thead>
						<tbody>
						<?php

						foreach ($arr as $stream) {
							if (!isset($stream['id'])) continue;
							$id = $stream['id'];

							$status = $stream['status'] == 1 ? 'ok' : 'error';
							$additionalInfo = FF_USE_WP ?
								'<td><span class="shortcode">[grace id="' . $id . '"]</span></td>' :
								'<td>' . $id . '</td>';

							if (isset($_REQUEST['debug']) && isset($stream['error'])) {
								$additionalInfo .= $stream['error'];
							}
							$info = '';
							if (isset($stream['feeds']) && !empty($stream['feeds'])) {
								$feeds = $stream['feeds'];

								if (is_array($feeds) || is_object($feeds)){
									foreach ( $feeds as $feed ) {
									    $type = $feed['timeline-type'];
									    $content = $feed['content'];

                                        if(isset($feed['location-meta']) && isset($feed['location-meta']->name)){
                                            $content = $feed['location-meta']->name;
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

										$info .= '<span class="highlight">' . $icon . $content . '</span>';
									}
								}
							}


							echo
								'<tr data-stream-id="' . $id . '">
							      <td class="controls">
							        <div class="loader-wrapper">
							            <div class="throbber-loader"></div>
							        </div>
							        <i class="flaticon-tool_edit" data-action="edit"></i> 
							        <i class="flaticon-tool_clone" data-action="clone"></i> 
                                    <i class="flaticon-tool_delete" data-action="delete"></i>
                                  </td>
                                  <td><span class="cache-status-'. $status .'"></span></td>
							      <td class="td-name">' . (!empty($stream['name']) ? stripslashes($stream['name']) : 'Unnamed') . '</td>
							      <td class="td-feed">' . (empty($info) ? '<span class="highlight grey">NO FEEDS</span>' : $info) . '</td>'
								. $additionalInfo .
								'</tr>';
						}

						if (empty($arr)) {
							echo '<tr class="empty-row"><td class="empty-cell" colspan="6">Please add at least one stream</td></tr>';
						}

						?>
						</tbody>
					</table>
				</div>
                <div class="section rating-promo">
                    <div class="fb-wrapper"><div class="fb-page" data-href="https://www.facebook.com/looksawesooome/" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/looksawesooome/"><a href="https://www.facebook.com/looksawesooome/">Looks Awesome</a></blockquote></div></div></div>
                    <h1 class="desc-following"><span>Help plugin to grow</span></h1>
                    <p class="">A lot of users only think to review Grace Instagram Feed Gallery when something goes wrong while many more people use it satisfactory. Don't let this go unnoticed. If you find Grace useful please leave your honest rating and review on plugins <a href="http://codecanyon.net/downloads" target="_blank">Downloads page</a> to help Flow-Flow grow and endorse its further development!</p>
                </div>
			</div>
		</div>
		<?php
	}
} 