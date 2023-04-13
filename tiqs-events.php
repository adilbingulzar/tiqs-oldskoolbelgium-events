<?php 
/* 

Plugin name: Tiqs Oldskoolbelgium Events 
Description: Display Tiqs Events 
Author: Tiqs 
Version: 1.1.3
*/  

function ss_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "tiqs_events";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
		`id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`event_id` int(11) NULL,
		`vendorId` int(11) NOT NULL,
		`eventname` varchar(255) CHARACTER SET utf8 NULL,
		`eventdescript` longtext CHARACTER SET utf8 NULL,
		`eventCategory` varchar(255) CHARACTER SET utf8 NULL,
		`eventVenue` varchar(255) CHARACTER SET utf8 NULL,
		`eventAddress` varchar(255) CHARACTER SET utf8 NULL,
		`eventCity` varchar(255) CHARACTER SET utf8 NULL,
		`eventZipcode` varchar(255) CHARACTER SET utf8 NULL,
		`eventCountry` varchar(255) CHARACTER SET utf8 NULL,
		`StartDate` date NULL,
		`EndDate` date NULL,
		`StartTime` time NULL,
		`EndTime` time NULL,
		`eventImage` varchar(255) CHARACTER SET utf8 NULL,
		`tag` varchar(255) CHARACTER SET utf8 NULL,
		`url` varchar(255) CHARACTER SET utf8 NULL,
		`facebookUrl` varchar(255) CHARACTER SET utf8 NULL,
		`type` enum('api','manual') CHARACTER SET utf8 NULL DEFAULT 'manual',
		`is_blocked` enum('0','1') CHARACTER SET utf8 NULL DEFAULT '0',
		`createdAt` timestamp NULL,
		`updatedAt` timestamp NULL
	  ) $charset_collate; ";




    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'ss_options_install');

//menu items
add_action('admin_menu','tiqs_events_modifymenu');
function tiqs_events_modifymenu() {
	
	//this is the main item for the menu
	add_menu_page('Tiqs Events', //page title
	'Tiqs Events', //menu title
	'manage_options', //capabilities
	'tiqs_events_list', //menu slug
	'tiqs_events_list' //function
	);
	
	//this is a submenu
	add_submenu_page('tiqs_events_list', //parent slug
	'Add New Event', //page title
	'Add New', //menu title
	'manage_options', //capability
	'tiqs_events_create', //menu slug
	'tiqs_events_create'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update Event', //page title
	'Update', //menu title
	'manage_options', //capability
	'tiqs_events_update', //menu slug
	'tiqs_events_update'); //function

	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Block Event', //page title
	'Block', //menu title
	'manage_options', //capability
	'tiqs_events_block', //menu slug
	'tiqs_events_block'); //function

	add_action( 'admin_init', 'update_tiqs_events_info' );  
	
}

// Create function to register plugin settings in the database 

if( !function_exists("update_tiqs_events_info") ) { 
	function update_tiqs_events_info() {
		register_setting( 'tiqs-events-info-settings', 'tiqs_events_info' ); 
		register_setting( 'tiqs-events-info-settings', 'tiqs_events_affiliate' ); 
	} 
}  

// Plugin logic for adding extra info to posts 

if( !function_exists("tiqs_events_info") ) {   
	function tiqs_events_info($content) {     
		$extra_info = get_option('tiqs_events_info');     
		return $content . $extra_info;   
	} 
}
add_action( 'wp_head', 'tiqs_events_header' );
add_action( 'wp_footer', 'tiqs_events_footer_js' );
add_shortcode( 'tiqs-events', 'tiqs_events_info_short_code' );
add_shortcode( 'tiqs-events-upcomming', 'tiqs_events_firstevent_short_code' );
add_shortcode( 'tiqs-event-details', 'tiqs_event_detail_info_short_code' );

add_filter( 'query_vars', 'add_custom_query_var' );
function add_custom_query_var( $query_vars ) {
    $query_vars[] = 'event';
    return $query_vars;
}

function get_api_events() {
	$extra_info = get_option('tiqs_events_info');     
	$body = array('vendorId' => $extra_info);

	$data = array(
		'body'	=> $body
	);

	$response = wp_remote_post( 'https://tiqs.com/alfred/Api/ScannerApiV2/VendorEvents', $data );
	$resp = wp_remote_retrieve_body( $response );

	return json_decode($resp);
}

add_action( 'wp_ajax_update_status_db', 'TOED_Update_status_db_callback' );
define('TOED_EVENTROOTDIR', plugin_dir_path(__FILE__));
require_once(TOED_EVENTROOTDIR . 'events-list.php');
require_once(TOED_EVENTROOTDIR . 'events-create.php');
require_once(TOED_EVENTROOTDIR . 'events-update.php');
require_once(TOED_EVENTROOTDIR . 'events-block.php');
require_once(TOED_EVENTROOTDIR . 'events-front-side.php');
require_once(TOED_EVENTROOTDIR . 'update-event-tags.php');
