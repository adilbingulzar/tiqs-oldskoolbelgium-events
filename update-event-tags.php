<?php

function TOED_Update_status_db_callback() {
    global $wpdb;

    $type = sanitize_text_field($_POST['type']);
    $id = sanitize_text_field($_POST['id']);
    $tag = sanitize_text_field($_POST['tag']);
    $table_name = $wpdb->prefix . "tiqs_events";

    if($type == 'manual') {
        $result = $wpdb->update( $table_name, array( 'tag' => $tag, 'updatedAt'  => date("Y-m-d H:i:s") ), array( 'id' => $id,'type' => 'manual' ));
    } else if($type === 'api') {
        $vendorId = sanitize_text_field($_POST['vendorId']);
        $events = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where event_id=%s AND type=%s", $id, $type));

        if(isset($events[0])) {
            $result = $wpdb->update(
                $table_name, //table
                array(
                    'tag' => $tag,
                    'updatedAt'  => date("Y-m-d H:i:s"),

                ), //data	
                array('event_id' => $id, 'type' => $type), //where
            );
        } else {
            $result = $wpdb->insert(
                $table_name, //table
                array(
                    'event_id'      => $id, 
                    'vendorId'      => $vendorId,
                    'tag'           => $tag,
                    'type'          => 'api',
                    'createdAt'     => date("Y-m-d H:i:s"),
                    'updatedAt'     => date("Y-m-d H:i:s"),

                ), //data		
            );
        }
    }
    
    if (!$result) {
        echo "FAILED TO UPDATE";
    } else {
        $result;
        echo "WILL UPDATE SUCCESSFULLY - CALL RESULT FUNCTION";
    };
    
    wp_die();
}