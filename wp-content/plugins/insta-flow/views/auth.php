<?php if ( ! defined( 'WPINC' ) )  die;
/**
 * FlowFlow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
$options = $context['options'];
$auth = $context['auth_options'];
?>
<div class="section-content" data-tab="auth-tab">
	<div class="section" id="auth-settings">
		<h1 class="desc-following ">Instagram auth settings <span id="inst-auth" class='admin-button auth-button blue-button'>Authorize</span></h1>
		<p class="desc">You can use your own token or get token by authorizing our app. <a target="_blank" href="http://social-streams.com/doc/authenticate-with-instagram/">Follow manual setup guide</a></p>
        <dl class="section-settings">
			<dt class="vert-aligned">Access Token</dt>
			<dd>
				<input class="clearcache" type="text" id="instagram_access_token" name="flow_flow_options[instagram_access_token]" placeholder="Copy and paste from Instagram" value="<?php echo isset($options['instagram_access_token']) ? $options['instagram_access_token'] : ''?>"/>
			</dd>
		</dl>
		<p class="button-wrapper"><span id="inst-auth-settings-sbmt" class='admin-button green-button submit-button'>Save Changes</span></p>
	</div>
	<?php include($context['root']  . 'views/footer.php'); ?>
</div>
