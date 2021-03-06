<?php
/*
Plugin Name: Kaltst plugin
Description: A kaltst plugin to upload, manage, edit and delet video content from Kaltura CE 10
Author: Pavel Tashev
Version: 0.1
*/
require_once(plugin_dir_path( __FILE__ ).'lib/KalturaClient.php');
require_once(plugin_dir_path( __FILE__ ).'kaltst-uploader.php');
require_once(plugin_dir_path( __FILE__ ).'kaltst_meta_profile.php');
require_once(plugin_dir_path( __FILE__ ).'kaltst_table.php');
include('kaltst_list.php');
add_action('admin_menu', 'kaltst_plugin_setup_menu');
function kaltst_plugin_setup_menu(){
        add_menu_page( 'Kaltst Plugin Page', 'Kaltura', 'manage_options', 'kaltst', 'kaltst_lst' );
        add_submenu_page( 'kaltst', 'Kaltura Settings', 'Kaltura Settings', 'manage_options', 'kaltst_settings', 'kaltura_settings' );
        add_submenu_page( 'kaltst', 'Kaltst Plugin Page', 'Kaltura Uploader', 'manage_options', 'kaltst-uploader', 'kaltura_uploader' );
}
add_action('kalupload_hock', 'kaltura_uploader');
function kaltura_uploader() { do_action('kalturauploader_hoock'); }

add_action('admin_head','hook_javascript');
function hook_javascript(){
    global $url;
	//echo "<meta name='viewport' content='width=device-width, initial-scale=1'>"."\r\n";
    foreach ( glob( plugin_dir_path( __FILE__ ) . "lib/js/*.js" ) as $file ) {
        $url = plugins_url( wp_basename( $file ), "/kaltst/lib/js/*.js");
        echo "<script type='text/javascript' src='". $url . "'></script>"."\r\n";
    }
    foreach (glob( plugin_dir_path( __FILE__ ) . "lib/css/*.css" ) as $csss ) {
        $url = plugins_url( wp_basename( $csss ), "/kaltst/lib/css/*.css");
        echo "<link rel='stylesheet' type='text/css' href='".$url ."'>"."\r\n";
    }
}

add_action('admin_menu', 'register_my_custom_submenu_page');
function register_my_custom_submenu_page() {
	//add_submenu_page( 'kaltst', 'Kaltst Plugin List', 'Kaltura List', 'manage_options', 'kaltst-list', 'kaltst_lst');
  add_submenu_page( 'kaltst', 'Kaltst Meta Profile', 'Kaltura Meta Profile', 'manage_options', 'kaltura_meta_profile', 'kaltura_meta_profile' );
}
function tl_save_error() {
    update_option( 'plugin_error',  ob_get_contents() );
    file_put_contents( plugin_dir_path( __FILE__ ) .'/errors_log' , ob_get_contents() );
}
add_action( 'activated_plugin', 'tl_save_error' );










/*instalation proces*/





add_action( 'activated_plugin', 'kaltst_create_db' );
function kaltst_create_db() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'kaltura_config_settings';

	$sql = "CREATE TABLE $table_name (
    kaltura_settings_id int(11) AUTO_INCREMENT,
		kaltura_service_url varchar(510) NOT NULL,
		kaltura_partner_id varchar(510) NOT NULL,
    kaltura_partner_service_secret varchar(1020) NOT NULL,
    kaltura_admin_service_secret varchar(1020) NOT NULL,
    kaltura_wp_admin_wiz varchar(255) NOT NULL,
    kaltura_player_ui_config varchar(255) NOT NULL,
    kaltura_partner_user varchar(255) NOT NULL,
    kaltura_connection_type varchar(255) NOT NULL,
    PRIMARY KEY (kaltura_settings_id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}


/*instalation proces*/
