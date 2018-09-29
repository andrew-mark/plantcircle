<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define('WP_CACHE', true);
define( 'WPCACHEHOME', dirname(__FILE__) . '/wp-content/plugins/wp-super-cache/' );
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'plantcircle');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '} &shZuCqwMHJ[Bm7>cMRKpl_O/u_ T<49<?YR:DUoV`SPNg_[U-Q^~x5fF|?R]T');
define('SECURE_AUTH_KEY',  '`N`>9awBV5B2!Q; ?j2MC{S<o/q+^?D.]#~g3G[kJ^PJiG*]N9cEeT{DB`b|vgD ');
define('LOGGED_IN_KEY',    'C,K~%)Hc~I9!VUo%,Y_MGh#,2a[Qf7=v85(VLN`rxe4s{?+^5nlMsi:8B0 5EJxD');
define('NONCE_KEY',        'Cza4T*So[tkA.1OIb_>nG/`bLa2M8ELJ+G:=SN7B2IJ~k$djU#o30fvco0dQFDN/');
define('AUTH_SALT',        't1HT=iHmxY(f]MF=|TQ}e]HR-ZDCnT_jomY9<YbQ]*0$4uxk,K9A}3K+tyCa+q#1');
define('SECURE_AUTH_SALT', 'j#UC56v}j[q}7*)2C+zivV;KVmN%F8_AC7r&e=!{-?C@?/|P%X4!1){<P@|HAFJ`');
define('LOGGED_IN_SALT',   'SAzl|fuC`a3QL?)3?e.@nL$;uQ=5XBbNPnRdg}E1/PJt6[I7bOJw`*H+_^y&utud');
define('NONCE_SALT',       'B3I0w=_m!6<JfAJk{XKE*B|d<(p;gvIng`EyIG.U5x,1:)bu+m2 S-5h:+l3o&)R');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'pc_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

define('WP_HOME', 'http://localhost:8888/plantcircle'); // blog url
define('WP_SITEURL', 'http://localhost:8888/plantcircle'); // site url

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

// define('DISALLOW_FILE_EDIT', true);

/* That's all, stop editing! Happy blogging. */
