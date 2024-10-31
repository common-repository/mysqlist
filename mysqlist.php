<?php
/*
Plugin Name: MySQList
Description: Mit MySQList kannst du ganz einfach Listen erstellen.
Version:     1.2.2
Author:      Bennett Hollstein
Author URI:  http://bennetthollstein.de
*/
if ( !function_exists( 'add_action' ) ) {
	echo 'Hey :) Ich bin leider nur ein Wordpress Plugin und kann ohne Wordpress ziemlich wenig :/';
	exit;
}
if (!defined('MYSQLIST_VERSION_NUM'))
    define('MYSQLIST_VERSION_NUM', '1.2.1');

$mysqlist_path = plugin_dir_path( __FILE__ );
//PHP Dateien einfügen
require_once( $mysqlist_path . 'default.php' );
require_once( $mysqlist_path . 'sql.php' );
require_once( $mysqlist_path . 'convert.php' );

//(De)Aktivitäts Hook einstellen
register_deactivation_hook( __FILE__, 'mysqlist_deactivation' );
register_activation_hook( __FILE__, 'mysqlist_activation' );
//Actions hinzufügen
add_action( 'admin_menu', 'mysqlist_add_custom_menu' );
add_action( 'wp', 'mysqlist_checkdb' );
add_action( 'init', 'mysqlist_shortcodes' );
add_action( 'admin_init', 'mysqlist_settings' );
add_option(MYSQLIST_VERSION_NUM, MYSQLIST_VERSION_NUM);

//if ( is_admin() ) {
//     // We are in admin mode
//     require_once( dirname(__file__).'/admin/mysqlist_admin.php' );
//}
?>