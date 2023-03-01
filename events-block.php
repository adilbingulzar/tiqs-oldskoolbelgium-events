<?php

function tiqs_events_block() {
    global $wpdb;
    $table_name = $wpdb->prefix . "tiqs_events";
    $id = sanitize_text_field($_GET["id"]);
    $type = sanitize_text_field($_GET["type"]);
    $isBlock = "";
    if($type == 'manual') {
        $events = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where id=%s AND type=%s", $id, $type));

        $isBlock = (isset($events[0]->is_blocked) && $events[0]->is_blocked) ? '0' : '1';
        $wpdb->update(
            $table_name, //table
            array(
                'is_blocked' => $isBlock,
                'updatedAt'  => date("Y-m-d H:i:s"),

            ), //data	
            array('id' => $id), //where
        );

    } else if ('api') {
        $vendorId = get_query_var($_GET["vendorId"]);
        $events = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where event_id=%s AND vendorId=%s AND type=%s", $id, $vendorId, $type));

        $isBlock = (isset($events[0]->is_blocked) && $events[0]->is_blocked) ? '0' : '1';

        if(isset($events[0]->is_blocked)) {
            $wpdb->update(
                $table_name, //table
                array(
                    'is_blocked' => $isBlock,
                    'updatedAt'  => date("Y-m-d H:i:s"),

                ), //data	
                array('event_id' => $id, 'vendorId' => $vendorId), //where
            );
        } else {
            $wpdb->insert(
                $table_name, //table
                array(
                    'event_id'      => $id, 
                    'vendorId'      => $vendorId,
                    'type'          => 'api',
                    'is_blocked'    => '1',
                    'createdAt'     => date("Y-m-d H:i:s"),
                    'updatedAt'     => date("Y-m-d H:i:s"),

                ), //data		
            );
        }
    }

    ?>

    <div class="updated"><p>Event <?php echo $isBlock == '1' ? 'Blocked' : 'Un-Blocked' ?></p></div>
    <a href="<?php echo admin_url('admin.php?page=tiqs_events_list') ?>">&laquo; Back to events list</a>


    <?php
}