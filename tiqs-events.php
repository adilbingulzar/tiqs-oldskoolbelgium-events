<?php
/**
 * Plugin Name: Tiqs Oldskoolbelgium Events
 * Description: Display Tiqs Events
 * Author: Tiqs
 * Version: 1.0.0
 */

namespace Tiqs_OldSkoolBelgium_Events;

class EventsPlugin {
    public function __construct() {
        // Run the install scripts upon plugin activation
        register_activation_hook(__FILE__, array($this, 'ss_options_install'));

        // Add menu items and callbacks
        add_action('admin_menu', array($this, 'tiqs_events_modifymenu'));

        // Register plugin settings in the database
        add_action('admin_init', array($this, 'update_tiqs_events_info'));

        // Add plugin logic for extra info in posts
        add_filter('the_content', array($this, 'tiqs_events_info'));

        // Add plugin hooks and shortcodes
        add_action('wp_head', array('\Tiqs_OldSkoolBelgium_Events\EventsFrontSide', 'tiqs_events_header'));
        add_action('wp_footer', array('\Tiqs_OldSkoolBelgium_Events\EventsFrontSide', 'tiqs_events_footer_js'));
        add_shortcode('tiqs-events', array($this, 'tiqs_events_info_short_code'));
        add_shortcode('tiqs-events-upcomming', array($this, 'tiqs_events_firstevent_short_code'));
        add_shortcode('tiqs-event-details', array($this, 'tiqs_event_detail_info_short_code'));
        add_shortcode('tiqs_event_detail_info', array($this, 'tiqs_event_detail_info_short_code'));

        // Add custom query var
        add_filter('query_vars', array($this, 'add_custom_query_var'));

        // Register AJAX action
        add_action('wp_ajax_update_status_db', array('\Tiqs_OldSkoolBelgium_Events\UpdateEventTags', 'TOED_Update_status_db_callback'));

        // Include necessary files
        $this->include_files();
    }

    public function include_files() {
        define('TOED_EVENTROOTDIR', plugin_dir_path(__FILE__));
        require_once(TOED_EVENTROOTDIR . 'events-list.php');
        require_once(TOED_EVENTROOTDIR . 'events-create.php');
        require_once(TOED_EVENTROOTDIR . 'events-update.php');
        require_once(TOED_EVENTROOTDIR . 'events-block.php');
        require_once(TOED_EVENTROOTDIR . 'events-front-side.php');
        require_once(TOED_EVENTROOTDIR . 'update-event-tags.php');
    }

    public function ss_options_install() {
        global $wpdb;

        $table_name = $wpdb->prefix . "tiqs_events";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `event_id` int(11) NULL,
            `vendorId` int(11) NOT NULL,
            `eventname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `eventdescript` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `eventCategory` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `eventVenue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `eventAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `eventCity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `eventZipcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `eventCountry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `StartDate` date DEFAULT NULL,
            `EndDate` date DEFAULT NULL,
            `StartTime` time DEFAULT NULL,
            `EndTime` time DEFAULT NULL,
            `eventImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `facebookUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `type` enum('api','manual') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'manual',
            `is_blocked` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
            `createdAt` timestamp NULL DEFAULT NULL,
            `updatedAt` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate; ";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function tiqs_events_modifymenu() {
        // This is the main item for the menu
        add_menu_page(
            'Tiqs Events', // Page title
            'Tiqs Events', // Menu title
            'manage_options', // Capabilities
            'tiqs_events_list', // Menu slug
            function () {
                $events_list = new \Tiqs_OldSkoolBelgium_Events\EventsList();
                $events_list->tiqs_events_list();
            } // Updated callback
        );

        // This is a submenu
        add_submenu_page(
            'tiqs_events_list', // Parent slug
            'Add New Event', //
            'Add New', // Menu title
            'manage_options', // Capability
            'tiqs_events_create', // Menu slug
            function () {
                $events_create = new \Tiqs_OldSkoolBelgium_Events\EventsCreate();
                $events_create->tiqs_events_create();
            } // Updated callback
        );

        // This submenu is HIDDEN, however, we need to add it anyways
        add_submenu_page(
            null, // Parent slug
            'Update Event', // Page title
            'Update', // Menu title
            'manage_options', // Capability
            'tiqs_events_update', // Menu slug
            function () {
                $events_update = new \Tiqs_OldSkoolBelgium_Events\EventsUpdate();
                $events_update->tiqs_events_update();
            } // Updated callback
        );

        // This submenu is HIDDEN, however, we need to add it anyways
        add_submenu_page(
            null, // Parent slug
            'Block Event', // Page title
            'Block', // Menu title
            'manage_options', // Capability
            'tiqs_events_block', // Menu slug
            function () {
                $events_block = new \Tiqs_OldSkoolBelgium_Events\EventsBlock();
                $events_block->tiqs_events_block();
            } // Updated callback
        );
    }

    public function update_tiqs_events_info() {
        register_setting('tiqs-events-info-settings', 'tiqs_events_info');
        register_setting('tiqs-events-info-settings', 'tiqs_events_affiliate');
    }

    public function tiqs_events_info($content) {
        $extra_info = get_option('tiqs_events_info');
        return $content . $extra_info;
    }

    public function tiqs_events_info_short_code($atts) {
        // Shortcode logic here
        $events_front_side = new \Tiqs_OldSkoolBelgium_Events\EventsFrontSide();
        $events_front_side->tiqs_events_info_short_code();
    }

    public function tiqs_events_firstevent_short_code($atts) {
        // Shortcode logic here
        $events_front_side = new \Tiqs_OldSkoolBelgium_Events\EventsFrontSide();
        $events_front_side->tiqs_events_firstevent_short_code();
    }

    public function tiqs_event_detail_info_short_code($atts) {
        // Shortcode logic here
        $events_front_side = new \Tiqs_OldSkoolBelgium_Events\EventsFrontSide();
        $events_front_side->tiqs_event_detail_info_short_code();
    }

    public function add_custom_query_var($query_vars) {
        $query_vars[] = 'event';
        return $query_vars;
    }
}

// Instantiate the plugin
new EventsPlugin();
