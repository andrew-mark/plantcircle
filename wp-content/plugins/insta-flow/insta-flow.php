<?php if ( ! defined( 'WPINC' ) ) die;
/**
 * @package   Insta_Flow
 * @author    Looks Awesome <hello@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright 2014-2017 Looks Awesome
 *
 * @wordpress-plugin
 * Plugin Name:       Grace: Instagram Feed Gallery for WordPress
 * Plugin URI:        https://social-streams.com
 * Description:       Create graceful galleries of Instagram posts on your WordPress site.
 * Version:           1.1.5
 * Author:            Looks Awesome
 * Author URI:        looks-awesome.com
 * Text Domain:       insta-flow
 * Domain Path:       /languages
 */
if ( ! defined( 'FF_USE_WP' ) )  define( 'FF_USE_WP', true );
if ( ! defined( 'FF_USE_WPDB' ) )  define( 'FF_USE_WPDB', false );
if ( ! defined( 'FF_USE_WP_CRON' ) ) define('FF_USE_WP_CRON', true);
if ( ! defined( 'FF_USE_DIRECT_WP_CRON' ) ) define('FF_USE_DIRECT_WP_CRON', false);
if ( ! defined( 'FF_FORCE_FIT_MEDIA' ) ) define('FF_FORCE_FIT_MEDIA', false);
if ( ! defined( 'FF_FEED_POSTS_COUNT' ) ) define('FF_FEED_POSTS_COUNT', 100);
if ( ! defined( 'FF_LOCALE')) define('FF_LOCALE', get_locale());//TODO add a slash to the end
if ( ! defined( 'FF_DB_CHARSET')) define('FF_DB_CHARSET', defined( 'DB_CHARSET' ) ? DB_CHARSET : 'utf8');

require_once( plugin_dir_path( __FILE__ ) . 'INSTClassLoader.php' );
INSTClassLoader::get(plugin_dir_path( __FILE__ ), plugins_url() . '/' . 'insta-flow' . '/')->register();

$facade = \ffinst\flow\core\LAActivatorFacade::get();
$facade->registry_activator(new ffinst\FFIActivator(__FILE__));
