<?php

namespace Tiqs_OldSkoolBelgium_Events;

class EventsCreate {
    public function __construct() {
        // Constructor logic
        add_action('admin_enqueue_scripts', array($this, 'tiqs_events_enqueue_styles'));
    }

    public function tiqs_events_create() {
        $vendorId       = isset( $_POST["vendorId"] )      ? sanitize_text_field( $_POST["vendorId"] )      : '';
        $eventname      = isset( $_POST["eventname"] )     ? sanitize_text_field( $_POST["eventname"] )     : '';
        $eventdescript  = isset( $_POST["eventdescript"] ) ? sanitize_text_field( $_POST["eventdescript"] ) : '';
        $eventCategory  = isset( $_POST["eventCategory"] ) ? sanitize_text_field( $_POST["eventCategory"] ) : '';
        $eventVenue     = isset( $_POST["eventVenue"] )    ? sanitize_text_field( $_POST["eventVenue"] )    : '';
        $eventAddress   = isset( $_POST["eventAddress"] )  ? sanitize_text_field( $_POST["eventAddress"] )  : '';
        $eventCity      = isset( $_POST["eventCity"] )     ? sanitize_text_field( $_POST["eventCity"] )     : '';
        $eventZipcode   = isset( $_POST["eventZipcode"] )  ? sanitize_text_field( $_POST["eventZipcode"] )  : '';
        $eventCountry   = isset( $_POST["eventCountry"] )  ? sanitize_text_field( $_POST["eventCountry"] )  : '';
        $StartDate      = isset( $_POST["StartDate"] )     ? sanitize_text_field( $_POST["StartDate"] )     : '';
        $EndDate        = isset( $_POST["EndDate"] )       ? sanitize_text_field( $_POST["EndDate"] )       : '';
        $StartTime      = isset( $_POST["StartTime"] )     ? sanitize_text_field( $_POST["StartTime"] )     : '';
        $EndTime        = isset( $_POST["EndTime"] )       ? sanitize_text_field( $_POST["EndTime"] )       : '';
        $url            = isset( $_POST["url"] )           ? sanitize_text_field( $_POST["url"] )           : '';
        $facebookUrl    = isset( $_POST["facebookUrl"] )   ? sanitize_text_field( $_POST["facebookUrl"] )   : '';

        if ( isset( $_POST['insert'] ) ) {
            global $wpdb;
            $table_name = $wpdb->prefix . "tiqs_events";

            $upload = wp_handle_upload(
                $_FILES['eventImage'],
                array( 'test_form' => false )
            );

            $wpdb->insert(
                $table_name,
                array(
                    'vendorId'      => $vendorId,
                    'eventname'     => $eventname,
                    'eventdescript' => $eventdescript,
                    'eventCategory' => $eventCategory,
                    'eventVenue'    => $eventVenue,
                    'eventAddress'  => $eventAddress,
                    'eventCity'     => $eventCity,
                    'eventZipcode'  => $eventZipcode,
                    'eventCountry'  => $eventCountry,
                    'StartDate'     => date( "Y-m-d", strtotime( $StartDate ) ),
                    'EndDate'       => date( "Y-m-d", strtotime( $EndDate ) ),
                    'StartTime'     => date( "H:i:s", strtotime( $StartTime ) ),
                    'EndTime'       => date( "H:i:s", strtotime( $EndTime ) ),
                    'eventImage'    => $upload['url'],
                    'url'           => $url,
                    'facebookUrl'   => $facebookUrl,
                    'createdAt'     => date( "Y-m-d H:i:s" ),
                    'updatedAt'     => date( "Y-m-d H:i:s" ),
                )
            );
            $message = "Event inserted successfully.";
        }
        ?>

        <div class="wrap">
            <h2>Add New Event</h2>

            <?php if ( isset( $message ) ) : ?>
                <div class="updated">
                    <p><?php echo esc_html( $message ); ?></p>
                </div>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=tiqs_events_list' ) ); ?>">&laquo; Back to events list</a>
                <?php exit; ?>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                <table class="wp-list-table widefat fixed">
                    <!-- Table rows and input fields -->
                </table>
                <br>
                <input type="submit" name="insert" value="Save" class="button">
            </form>
        </div>

        <?php
    }

    public function tiqs_events_enqueue_styles() {
        wp_enqueue_style( 'tiqs-events-admin', plugins_url( 'style-admin.css', __FILE__ ) );
    }
}
