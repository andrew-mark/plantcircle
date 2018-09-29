<?php if ( ! defined( 'WPINC' ) ) die;
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
$moderation = $context['moderation'] && $context['can_moderate'];
$stream = $context['stream'];
if (FF_USE_WP)
	$admin = $moderation ? $moderation : function_exists('current_user_can') && current_user_can('manage_options');
else
	$admin = ff_user_can_moderate();
$id = $stream->id;
$hash = $context['hashOfStream'];
$seo = $context['seo'];
$disableCache = isset($_REQUEST['disable-cache']);
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '0';
$opts =  $this->get_options();
$plugins_url = plugins_url();
$js_opts = array(
	'streams' => new \stdClass(),
	'open_in_new' => $opts['general-settings-open-links-in-new-window'],
	'filter_all' => __('Show All', 'insta-flow'),
	'filter_search' => __('Search', 'insta-flow'),
	'expand_text' => __('Expand', 'insta-flow'),
	'collapse_text' => __('Collapse', 'insta-flow'),
	'posted_on' => __('Posted on', 'insta-flow'),
	'show_more' => __('Show more', 'insta-flow'),
	'followers' => __('Followers', 'insta-flow'),
	'following' => __('Following', 'insta-flow'),
	'posts' => __('Posts', 'insta-flow'),
	'date_style' => $opts['general-settings-date-format'],
	'dates' => array(
		'Yesterday' => __('Yesterday', 'insta-flow'),
		's' => __('s', 'insta-flow'),
		'm' => __('m', 'insta-flow'),
		'h' => __('h', 'insta-flow'),
		'ago' => __('ago', 'insta-flow'),
		'months' => array(
			__('Jan', 'insta-flow'), __('Feb', 'insta-flow'), __('March', 'insta-flow'),
			__('April', 'insta-flow'), __('May', 'insta-flow'), __('June', 'insta-flow'),
			__('July', 'insta-flow'), __('Aug', 'insta-flow'), __('Sept', 'insta-flow'),
			__('Oct', 'insta-flow'), __('Nov', 'insta-flow'), __('Dec', 'insta-flow')
		),
	),
	'lightbox_navigate' => __('Navigate with arrow keys', 'insta-flow'),
	'server_time' => time(),
	'forceHTTPS' => $opts['general-settings-https'],
	'isAdmin' => function_exists('current_user_can') && current_user_can( 'manage_options' ),
	'ajaxurl' => $this->context['ajax_url'],
	'isLog' => isset($_REQUEST['fflog']) && $_REQUEST['fflog'] == 1,
	'plugin_base' => $plugins_url . '/' . $this->slug ,
	'plugin_ver' => $this->context['version']
);
?>
	<!-- Flow-Flow — Social stream plugin for WordPress -->
	<div class="ff-stream" id="ff-stream-<?php echo $id;?>"><span class="ff-loader"><span class="ff-square" ></span><span class="ff-square"></span><span class="ff-square ff-last"></span><span class="ff-square ff-clear"></span><span class="ff-square"></span><span class="ff-square ff-last"></span><span class="ff-square ff-clear"></span><span class="ff-square"></span><span class="ff-square ff-last"></span></span></div>

	<script type="text/javascript">
		var FFI_resource = window.FFI_resource || {
			scriptDeferred: jQuery.Deferred(),
			styleDeferred: jQuery.Deferred(),
			scriptLoading: false,
			styleLoading: false
		}
		var InstaFlowOpts = window.InstaFlowOpts || <?php echo json_encode($js_opts); ?>;
		(function ( $ ) {
			"use strict";
			var hash = '<?php echo $hash; ?>';
			if (/MSIE 8/.test(navigator.userAgent)) return;
			var opts = window.InstaFlowOpts;
            var isLS = isLocalStorageNameSupported();
			if (!opts) {
				window.console && window.console.log('Flow-Flow Social Stream: no options available on moment of script execution');
				return;
			}
			if (!window.FFI_resource) {
				window.console && window.console.log('Flow-Flow Social Stream: required script has not been loaded. Please check if all resources in place or loaded in correct order.');
				return
			}
			var data = {
				'action': '<?php echo $context['slug_down'] . '_fetch_posts';?>',
				'stream-id': '<?php echo $id;?>',
				'disable-cache': '<?php echo $disableCache;?>',
				'hash': hash,
				'page': '<?php echo $page;?>',
				'preview': '<?php echo $stream->preview ? 1 : 0;?>'
			};
			var isMobile = /android|blackBerry|iphone|ipad|ipod|opera mini|iemobile/i.test(navigator.userAgent);
			var streamOpts = <?php echo json_encode($stream); ?>;

            streamOpts.trueLayout = streamOpts.layout

            if(streamOpts.layout == 'carousel'){
                streamOpts['layout'] = 'grid'
                streamOpts['g-ratio-h'] = "1"
                streamOpts['g-ratio-img'] = "1/2"
                streamOpts['g-ratio-w'] = "1"
                streamOpts['g-overlay'] = "yep"
                streamOpts['c-overlay'] = "yep"
                streamOpts['s-desktop'] = "0"
                streamOpts['s-laptop'] = "0"
                streamOpts['s-smart-l'] = "0"
                streamOpts['s-smart-p'] = "0"
                streamOpts['s-tablet-l'] = "0"
                streamOpts['s-tablet-p'] = "0"
            }

            opts.streams['stream' + streamOpts.id] = streamOpts;
			var $cont = $("#ff-stream-"+data['stream-id']);
			var ajaxDeferred;
			var script, style;
			var layout_pre = streamOpts.layout.charAt(0);
			var isOverlay = layout_pre === 'j' || streamOpts[layout_pre + '-overlay'] === 'yep';
			var imgIndex;
			if (isOverlay) {
				if (streamOpts.template[0] !== 'image') {
					for (var i = 0, len = streamOpts.template.length; i < len; i++) {
						if (streamOpts.template[i] === 'image') imgIndex = i;
					}
					streamOpts.template.splice(0, 0, streamOpts.template.splice(imgIndex, 1)[0]);
				}
				streamOpts.isOverlay = true;
			}
			if (FFI_resource.scriptDeferred.state() === 'pending' && !FFI_resource.scriptLoading) {
				script = document.createElement('script');
				script.src = "<?php echo $context['plugin_url'] . $context['slug'];?>/js/public.js";
				script.onload = function( script, textStatus ) {
					FFI_resource.scriptDeferred.resolve();
				};
				document.body.appendChild(script);
				FFI_resource.scriptLoading = true;
			}
			if (FFI_resource.styleDeferred.state() === 'pending' && !FFI_resource.styleLoading) {
				style = document.createElement('link');
				style.type = "text/css";
                style.id = "ff_style";
                style.rel = "stylesheet";
				style.href = "<?php echo $context['plugin_url'] . $context['slug'];?>/css/public.css";
				style.media = "screen";
				style.onload = function( script, textStatus ) {
					FFI_resource.styleDeferred.resolve();
				};
				document.getElementsByTagName("head")[0].appendChild(style);
				FFI_resource.styleLoading = true;
			}
			$cont.addClass('ff-layout-' + streamOpts.trueLayout);
			if (!isMobile) $cont.css('minHeight', '500px');
			ajaxDeferred = <?php if ($admin) {echo '$.get(opts.ajaxurl, data)';} else {echo 'isLS && sessionStorage.getItem(hash) ? {} : $.get(opts.ajaxurl, data)';} echo PHP_EOL; ?>;
			$.when( ajaxDeferred, FFI_resource.scriptDeferred, FFI_resource.styleDeferred ).done(function ( data ) {
				var response, $errCont, err;
                var moderation = <?php echo $moderation ? 1 : 0 ?>;
                var original = <?php if ($admin) {echo 'data[0]';} else {echo '(isLS && sessionStorage.getItem(hash)) ? sessionStorage.getItem(hash) : data[0]';}?>;
				try {
					response = JSON.parse(original);
				} catch (e) {
					window.console && window.console.log('Flow-Flow gets invalid data from server');
					if (opts.isAdmin || opts.isLog) {
						$errCont = $('<div class="ff-errors" id="ff-errors-invalid-response"><div class="ff-disclaim">If you see this message then you have administrator permissions and Flow-Flow got invalid data from server. Please provide error message below if you are doing support request.<\/div><div class="ff-err-info"><\/div><\/div>');
						$cont.before($errCont);
						$errCont.find('.ff-err-info').html(original == '' ? 'Empty response from server' : original)
					}
					return;
				}
                opts.streams['stream' + streamOpts.id]['items'] = response;
                if (!InstaFlowOpts.dependencies) InstaFlowOpts.dependencies = {};
				<?php
				//TODO Add support ads addon
				$dependencies = array();//apply_filters('ff_plugin_dependencies', array());
				foreach ($dependencies as $name) {
				    echo "if (!InstaFlowOpts.dependencies['{$name}']) InstaFlowOpts.dependencies['{$name}'] = true;";
				}
				?>
                var requests = [];
                var request, extension, style;

                for ( extension in InstaFlowOpts.dependencies ) {
                    if ( InstaFlowOpts.dependencies[extension] && InstaFlowOpts.dependencies[extension] !== 'loaded') {
                        request = $.getScript( opts.plugin_base + '-' + extension + '/js/ff_' + extension + '_public.js');
                        requests.push(request);

                        style = document.createElement('link');
                        style.type = "text/css";
                        style.rel = "stylesheet";
                        style.id = "ff_ad_style";
                        style.href = opts.plugin_base + '-' + extension + '/css/ff_' + extension + '_public.css';
                        style.media = "screen";
                        document.getElementsByTagName("head")[0].appendChild(style);

                        InstaFlowOpts.dependencies[extension] = 'loaded';
                    }
                }

                var resourcesLoaded = $.when.apply($, requests);

                resourcesLoaded.done(function(){
                    var $stream, width;
                    $stream = FlowFlow.buildStreamWith(response, streamOpts, moderation, InstaFlowOpts.dependencies);
                    <?php if (!$admin) {echo 'if (isLS && response.items.length > 0 && response.hash.length > 0) sessionStorage.setItem(response.hash, original);' . PHP_EOL;}?>
                    var num = streamOpts.layout === 'compact' || (streamOpts.mobileslider === 'yep' && isMobile && streamOpts.trueLayout !== 'carousel')? (streamOpts.mobileslider === 'yep' ? 3 : streamOpts['cards-num']) : false;
					$cont.append($stream);
                    if (typeof $stream !== 'string') {
                        FlowFlow.setupGrid($cont.find('.ff-stream-wrapper'), num, streamOpts.scrolltop === 'yep', streamOpts.gallery === 'yep', streamOpts, $cont);
                    }
                    setTimeout(function(){
                        $cont.find('.ff-header').removeClass('ff-loading').end()
                        $cont.find('.ff-loader').addClass('ff-squeezed').delay(300).hide();
                    }, 0);

	                <?php
	                //TODO Add support ads addon
	                //do_action('ff_add_view_action', $stream);
	                ?>

                }).fail(function(){
                    console.log('Flow-Flow: resource loading failed')
                });

                var isErr = response.status === "errors";
                if ((opts.isAdmin || opts.isLog) && isErr) {
					$errCont = $('<div class="ff-errors" id="ff-errors-'+response.id+'"><div class="ff-err-info">If you see this then you are administrator and Flow-Flow got errors from APIs while requesting data. Please go to plugin admin and after refreshing page check for error(s) on stream settings page. Please provide error message info if you are doing support request.<\/div><\/div>');
					$cont.before($errCont);
				}

                if (opts.isAdmin && response.status === 'building') {
                    window.console && window.console.log(response);
                    $cont.prepend($('<div id="ff-admin-info">ADMIN INFO: Feeds cache is being built in background. Please wait for changes to apply. Page reload is required.<\/div>'));
                }
			});

            function isLocalStorageNameSupported() {
                var testKey = 'test', storage = window.sessionStorage;
                try {
                    storage.setItem(testKey, '1');
                    storage.removeItem(testKey);
                    return true;
                } catch (error) {
                    return false;
                }
            };

			return false;
		}(jQuery));
	</script>
	<!-- Flow-Flow — Social streams plugin for Wordpress -->