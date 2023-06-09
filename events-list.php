<?php

namespace Tiqs_OldSkoolBelgium_Events;

class EventsList {
    public function __construct() {
        // Constructor logic
    }

    public function tiqs_events_list() {
        wp_enqueue_script( 'tiqs-events-script-8', $this->toed_get_page_url('includes/js/custom.js'), array( 'jquery' ), null, true );
        wp_localize_script( 'tiqs-events-script-8', 'data', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_style( 'tiqs-events-admin', plugin_dir_url( __FILE__ ) . 'style-admin.css' );

        echo '<h1>Tiqs Events Info</h1>';
        ?>
        <form method="post" action="options.php">
            <?php settings_fields( 'tiqs-events-info-settings' ); ?>     
            <?php do_settings_sections( 'tiqs-events-info-settings' ); ?>     
            <table class="form-table">       
                <!-- Form fields -->
            </table>   
            
            <?php submit_button(); ?>   
        </form>  
    
        <div class="wrap">
            <h2>Tiqs Events</h2>
            <div class="tablenav top">
                <div class="alignleft actions">
                    <a href="<?php echo esc_url( admin_url('admin.php?page=tiqs_events_create') ); ?>">Add New Event</a>
                </div>
                <br class="clear">
            </div>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'tiqs_events';

            $rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE type = 'manual' AND vendorId = %s", get_option('tiqs_events_info') ) );

            $blockedApiEvents = $wpdb->get_results( $wpdb->prepare( "SELECT GROUP_CONCAT(event_id) AS 'eventId' FROM $table_name WHERE type = 'api' AND is_blocked = '1' AND vendorId = %s", get_option('tiqs_events_info') ) );
            $blockedApiEvents = isset( $blockedApiEvents[0]->eventId ) ? explode( ',', $blockedApiEvents[0]->eventId ) : array();

            $apiEventsUpdates = $wpdb->get_results( $wpdb->prepare( "SELECT event_id, vendorId, tag FROM $table_name WHERE type = 'api' AND tag != '' AND tag IS NOT NULL AND vendorId = %s", get_option('tiqs_events_info') ) );

            $events_plugin = new \Tiqs_OldSkoolBelgium_Events\EventsPlugin();
            $apiRows = $events_plugin->get_api_events();

            ?>
            <table class="wp-list-table widefat fixed striped posts">
                <thead>
                    <tr>
                        <!-- Table headers -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) { ?>
                    <tr>
                        <!-- Table rows and data -->
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    public function toed_get_page_url( $path = '' ) {
        $url = plugins_url( $path , __FILE__);

        if ( is_ssl() && 'http:' === substr( $url, 0, 5 ) ) {
            $url = 'https:' . substr( $url, 5 );
        }

        return $url;
    }
}
