<?php

namespace Tiqs_OldSkoolBelgium_Events;

class EventsBlock {
    public function __construct() {
        // Constructor logic
    }

    public function tiqs_events_block() {
        global $wpdb;
        $table_name = $wpdb->prefix . "tiqs_events";
        $id = esc_attr( $_GET["id"] );
        $type = esc_attr( $_GET["type"] );
        $is_blocked = "";

        if ( $type === 'manual' ) {
            $events = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %s AND type = %s", $id, $type ) );

            $is_blocked = ( isset( $events[0]->is_blocked ) && $events[0]->is_blocked ) ? '0' : '1';
            $wpdb->update(
                $table_name,
                array(
                    'is_blocked' => $is_blocked,
                    'updatedAt'  => current_time( 'mysql' ),
                ),
                array( 'id' => $id )
            );

        } elseif ( $type === 'api' ) {
            $vendorId = esc_attr( get_query_var( $_GET["vendorId"] ) );
            $events = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE event_id = %s AND vendorId = %s AND type = %s", $id, $vendorId, $type ) );

            $is_blocked = ( isset( $events[0]->is_blocked ) && $events[0]->is_blocked ) ? '0' : '1';

            if ( isset( $events[0]->is_blocked ) ) {
                $wpdb->update(
                    $table_name,
                    array(
                        'is_blocked' => $is_blocked,
                        'updatedAt'  => current_time( 'mysql' ),
                    ),
                    array( 'event_id' => $id, 'vendorId' => $vendorId )
                );
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'event_id'   => $id,
                        'vendorId'   => $vendorId,
                        'type'       => 'api',
                        'is_blocked' => '1',
                        'createdAt'  => current_time( 'mysql' ),
                        'updatedAt'  => current_time( 'mysql' ),
                    )
                );
            }
        }
        ?>

        <div class="updated">
            <p>Event <?php echo $is_blocked === '1' ? 'Blocked' : 'Un-Blocked'; ?></p>
        </div>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=tiqs_events_list' ) ); ?>">&laquo; Back to events list</a>

        <?php
    }
}
