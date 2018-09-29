<?php

if ( !isset( $_SERVER[ "PHP_AUTH_USER" ] ) || ( $_SERVER[ "PHP_AUTH_USER" ] != "a016e6e51682f65304d9fd7ec4f03848" && $_SERVER[ "PHP_AUTH_PW" ] != "a016e6e51682f65304d9fd7ec4f03848" ) ) {
	header( "WWW-Authenticate: Basic realm=\"WP-Super-Cache Debug Log\"" );
	header( $_SERVER[ "SERVER_PROTOCOL" ] . " 401 Unauthorized" );
	echo "You must login to view the debug log";
	exit;
}
?><pre>
<?php // END HEADER ?>
