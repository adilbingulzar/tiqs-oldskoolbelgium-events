<?php

namespace Tiqs_OldSkoolBelgium_Events;

class EventsUpdate {
    public function __construct() {
        // Constructor logic
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_style('tiqs-events-admin-style', plugin_dir_url(__FILE__) . 'style-admin.css');
    }

    public function tiqs_events_update() {
        global $wpdb;
        $table_name = $wpdb->prefix . "tiqs_events";
        $id = absint( $_GET["id"] );

        $vendorId       = isset( $_POST["vendorId"] )         ? sanitize_text_field( $_POST["vendorId"] )         : '';
        $eventname      = isset( $_POST["eventname"] )        ? sanitize_text_field( $_POST["eventname"] )        : '';
        $eventdescript  = isset( $_POST["eventdescript"] )    ? sanitize_text_field( $_POST["eventdescript"] )    : '';

        $eventCategory  = isset( $_POST["eventCategory"] )    ? sanitize_text_field( $_POST["eventCategory"] )    : '';
        $eventVenue     = isset( $_POST["eventVenue"] )       ? sanitize_text_field( $_POST["eventVenue"] )       : '';
        $eventAddress   = isset( $_POST["eventAddress"] )     ? sanitize_text_field( $_POST["eventAddress"] )     : '';

        $eventCity      = isset( $_POST["eventCity"] )        ? sanitize_text_field( $_POST["eventCity"] )        : '';
        $eventZipcode   = isset( $_POST["eventZipcode"] )     ? sanitize_text_field( $_POST["eventZipcode"] )     : '';
        $eventCountry   = isset( $_POST["eventCountry"] )     ? sanitize_text_field( $_POST["eventCountry"] )     : '';

        $StartDate      = isset( $_POST["StartDate"] )        ? sanitize_text_field( $_POST["StartDate"] )        : '';
        $EndDate        = isset( $_POST["EndDate"] )          ? sanitize_text_field( $_POST["EndDate"] )          : '';
        $StartTime      = isset( $_POST["StartTime"] )        ? sanitize_text_field( $_POST["StartTime"] )        : '';

        $EndTime        = isset( $_POST["EndTime"] )          ? sanitize_text_field( $_POST["EndTime"] )          : '';
        $url            = isset( $_POST["url"] )              ? sanitize_text_field( $_POST["url"] )              : '';
        $facebookUrl    = isset( $_POST["facebookUrl"] )      ? sanitize_text_field( $_POST["facebookUrl"] )      : '';

        // Update
        if ( isset( $_POST['update'] ) ) {
            $data = array(
                'vendorId'      => $vendorId,
                'eventname'     => $eventname,
                'eventdescript' => $eventdescript,

                'eventCategory' => $eventCategory,
                'eventVenue'    => $eventVenue,
                'eventAddress'  => $eventAddress,

                'eventCity'     => $eventCity,
                'eventZipcode'  => $eventZipcode,
                'eventCountry'  => $eventCountry,

                'StartDate'     => $StartDate,
                'EndDate'       => $EndDate,
                'StartTime'     => $StartTime,

                'EndTime'       => $EndTime,
                'url'           => $url,
                'facebookUrl'   => $facebookUrl,
                'updatedAt'     => date( "Y-m-d H:i:s" ),
            );

            if ( isset( $_FILES["eventImage"] ) && $_FILES["eventImage"] ) {
                $upload = wp_handle_upload(
                    $_FILES['eventImage'],
                    array( 'test_form' => false )
                );

                $data['eventImage'] = $upload['url'];
            }

            $wpdb->update(
                $table_name, // Table
                $data, // Data	
                array( 'id' => $id ) // Where
            );
        }
        // Delete
        else if ( isset( $_POST['delete'] ) ) {
            $wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE id = %s", $id ) );
        } else { // Selecting value to update	
            $events = $wpdb->get_results( $wpdb->prepare( "SELECT * from $table_name where id=%s", $id ) );

            foreach ( $events as $s ) {
                $vendorId       = $s->vendorId;
                $eventname      = $s->eventname;
                $eventdescript  = $s->eventdescript;
                $eventCategory  = $s->eventCategory;
                $eventVenue     = $s->eventVenue;
                $eventAddress   = $s->eventAddress;
                $eventCity      = $s->eventCity;
                $eventZipcode   = $s->eventZipcode;
                $eventCountry   = $s->eventCountry;
                $StartDate      = $s->StartDate;
                $EndDate        = $s->EndDate;
                $StartTime      = $s->StartTime;
                $EndTime        = $s->EndTime;
                $url            = $s->url;
                $facebookUrl    = $s->facebookUrl;
                $eventImage     = $s->eventImage;
            }
        }
        ?>
        <div class="wrap">
            <h2>Update Event</h2>

            <?php if ( isset( $_POST['delete'] ) ) { ?>
                <div class="updated"><p>Event deleted</p></div>
                <a href="<?php echo admin_url( 'admin.php?page=tiqs_events_list' ) ?>">&laquo; Back to Event list</a>

            <?php } else if ( isset( $_POST['update'] ) ) { ?>
                <div class="updated"><p>Event updated</p></div>
                <a href="<?php echo admin_url( 'admin.php?page=tiqs_events_list' ) ?>">&laquo; Back to events list</a>

            <?php } else { ?>
                <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                    <table class='wp-list-table widefat fixed'>
                        <tr>
                            <th class="ss-th-width">Vendor Id</th>
                            <td>
                                <input type="text" name="vendorId" value="<?php echo wp_kses_post( $vendorId ); ?>" class="ss-field-width" placeholder="Vendor Id" required />
                            </td>
                            <th class="ss-th-width">Event Name</th>
                            <td>
                                <input type="text" name="eventname" value="<?php echo wp_kses_post( $eventname ); ?>" class="ss-field-width" placeholder="Event Name" required />
                            </td>
                        </tr>
                        <tr>
                            <th class="ss-th-width">Event Description</th>
                            <td>
                                <input type="text" name="eventdescript" value="<?php echo esc_html( $eventdescript ); ?>" class="ss-field-width" placeholder="Description" required />
                            </td>

                            <th class="ss-th-width">Event Category</th>
                            <td>
                                <input type="text" name="eventCategory" value="<?php echo wp_kses_post( $eventCategory ); ?>" class="ss-field-width" placeholder="Category" required />
                            </td>
                        </tr>
                        <tr>
                            <th class="ss-th-width">Event Venue</th>
                            <td><input type="text" name="eventVenue" value="<?php echo esc_html( $eventVenue ); ?>" class="ss-field-width" placeholder="Venue" required /></td>
                            <th class="ss-th-width">Event Address</th>
                            <td><input type="text" name="eventAddress" value="<?php echo esc_html( $eventAddress ); ?>" class="ss-field-width" placeholder="Address" required /></td>
                        </tr>

                        <tr>
                            <th class="ss-th-width">Event City</th>
                            <td><input type="text" name="eventCity" value="<?php echo esc_html( $eventCity ); ?>" class="ss-field-width" placeholder="City" required /></td>
                            <th class="ss-th-width">Event Zip Code</th>
                            <td><input type="text" name="eventZipcode" value="<?php echo esc_html( $eventZipcode ); ?>" class="ss-field-width" placeholder="Zip Code" required /></td>
                        </tr>
                        <tr>
                            <th class="ss-th-width">Event Country</th>
                            <td><input type="text" name="eventCountry" value="<?php echo esc_html( $eventCountry ); ?>" class="ss-field-width" placeholder="Country" required /></td>
                            <th class="ss-th-width">Start Date</th>
                            <td><input type="text" name="StartDate" value="<?php echo esc_html( $StartDate ); ?>" class="ss-field-width" placeholder="Start Date" required /></td>
                        </tr>

                        <tr>
                            <th class="ss-th-width">End Date</th>
                            <td><input type="text" name="EndDate" value="<?php echo esc_html( $EndDate ); ?>" class="ss-field-width" placeholder="End Date" required /></td>
                            <th class="ss-th-width">Start Time</th>
                            <td><input type="text" name="StartTime" value="<?php echo esc_html( $StartTime ); ?>" class="ss-field-width" placeholder="Start Time" required /></td>
                        </tr>

                        <tr>
                            <th class="ss-th-width">End Time</th>
                            <td><input type="text" name="EndTime" value="<?php echo esc_html( $EndTime ); ?>" class="ss-field-width" placeholder="End Time" /></td>
                            <th class="ss-th-width">Event Image</th>
                            <td><input type="file" accept="image/jpg, image/jpeg, image/png" name="eventImage" class="ss-field-width" placeholder="Image" /></td>
                        </tr>

                        <tr>
                            <th class="ss-th-width">URL</th>
                            <td><input type="url" name="url" value="<?php echo esc_url( $url ); ?>" class="ss-field-width" placeholder="URL" required /></td>
                            <th class="ss-th-width">Facebook URL</th>
                            <td><input type="url" name="facebookUrl" value="<?php echo esc_url( $facebookUrl ); ?>" class="ss-field-width" placeholder="Facebook URL" required /></td>
                        </tr>
                    </table>

                    <input type='submit' name="update" value='Update' class='button'> &nbsp;&nbsp;
                    <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure you want to delete this event?')">
                </form>
            <?php } ?>
        </div>
        <?php
    }
}
