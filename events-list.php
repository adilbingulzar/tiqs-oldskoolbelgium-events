<?php

function tiqs_events_list() {
    wp_enqueue_script( 'tiqs-events-script-8', TOED_Get_page_url('includes/js/custom.js') );
    wp_localize_script( 'tiqs-events-script-8', 'data', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

    ?>
    <link type="text/css" href="<?php echo esc_url(WP_PLUGIN_URL . '/tiqs-events/style-admin.css') ?>" rel="stylesheet" />
    <h1>Tiqs Events Info</h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'tiqs-events-info-settings' ); ?>     
        <?php do_settings_sections( 'tiqs-events-info-settings' ); ?>     
        <table class="form-table">       
            <tr valign="top">       
                <th scope="row">Full Page Shortcode:</th>      
                <td>
                    [tiqs-events]
                </td>       
            </tr>
            <tr valign="top">       
                <th scope="row">Vendor Id:</th>      
                <td>
                    <input type="text" name="tiqs_events_info" value="<?php echo get_option('tiqs_events_info'); ?>"/>
                </td>       
            </tr>   
            <tr valign="top">       
                <th scope="row">Affiliate:</th>      
                <td>
                    <input type="text" name="tiqs_events_affiliate" value="<?php echo get_option('tiqs_events_affiliate'); ?>"/>
                </td>       
            </tr>     
        </table>   
        
        <?php submit_button(); ?>   
    </form>  

    
    <div class="wrap">
        <h2>Tiqs Events</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=tiqs_events_create'); ?>">Add New Event</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "tiqs_events";

        $rows = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where type = 'manual' AND vendorId=%s", get_option('tiqs_events_info')));

        $blockedApiEvents = $wpdb->get_results($wpdb->prepare("SELECT GROUP_CONCAT(event_id) AS 'eventId' FROM $table_name WHERE type = 'api' AND is_blocked = '1' AND vendorId=%s", get_option('tiqs_events_info')));
        
        $blockedApiEvents = isset($blockedApiEvents[0]->eventId) ? $HiddenProducts = explode(',',$blockedApiEvents[0]->eventId) : array();


        $apiEventsUpdates = $wpdb->get_results($wpdb->prepare("SELECT event_id, vendorId,tag FROM $table_name WHERE type = 'api' AND tag != '' AND tag IS NOT NULL AND vendorId=%s", get_option('tiqs_events_info')));

        $apiRows = get_api_events();
        
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <!-- <th class="manage-column ss-list-width">Vendor Id</th> -->
                <th class="manage-column ss-list-width">Name</th>

                <th class="manage-column ss-list-width">Description</th>
                <th class="manage-column ss-list-width">Tag</th>
                <th class="manage-column ss-list-width">URL</th>
                <th class="manage-column ss-list-width">Facebook Url</th>
                <th class="manage-column ss-list-width">Category</th>
                <th class="manage-column ss-list-width">Venue</th>

                <th class="manage-column ss-list-width">Address</th>
                <th class="manage-column ss-list-width">City</th>
                <th class="manage-column ss-list-width">Zip Code</th>

                <th class="manage-column ss-list-width">Country</th>
                <th class="manage-column ss-list-width">Start Date</th>
                <th class="manage-column ss-list-width">End Date</th>

                <th class="manage-column ss-list-width">Start Time</th>
                <th class="manage-column ss-list-width">End Time</th>
                <th class="manage-column ss-list-width">Image</th>

                <th class="manage-column ss-list-width">Type</th>
                <th class="manage-column ss-list-width">Blocked</th>
                <th class="manage-column ss-list-width">created At</th>
                <th class="manage-column ss-list-width">Action</th>

                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->eventname); ?></td>

                    <td class="manage-column ss-list-width"><?php echo strlen($row->eventdescript) > 50 ? substr($row->eventdescript,0,30)."..." : $row->eventdescript;; ?></td>
                    <td class="manage-column ss-list-width"><div data-id="<?php echo esc_html($row->id) ?>" data-vendor="<?php echo (esc_html) ?>" data-type="<?php echo esc_html($row->type) ?>"><input type="text" class="tag-input" value="<?php $row->tag ?>" /></div></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->url); ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->facebookUrl ? esc_html($row->facebookUrl) : '---'; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->eventCategory ? esc_html($row->eventCategory) : '---'; ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->eventVenue); ?></td>

                    <td class="manage-column ss-list-width"><?php echo esc_html($row->eventAddress); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->eventCity); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->eventZipcode); ?></td>

                    <td class="manage-column ss-list-width"><?php echo esc_html($row->eventCountry); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->StartDate); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->EndDate); ?></td>

                    <td class="manage-column ss-list-width"><?php echo esc_html($row->StartTime); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->EndTime); ?></td>
                    <td class="manage-column ss-list-width"><?php echo isset($row->eventImage) ? '<img src="' . esc_url($row->eventImage) . '" width="100">' : '---'; ?></td>

                    <td class="manage-column ss-list-width"><?php echo esc_html($row->type); ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->is_blocked ? 'Yes' : 'No'; ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($row->createdAt); ?></td>

                    <td><a href="<?php echo admin_url('admin.php?page=tiqs_events_update&id=' . $row->id); ?>">Update</a> | <a href="<?php echo admin_url('admin.php?page=tiqs_events_block&type=manual&id=' . $row->id); ?>"><?php echo $row->is_blocked ? 'Un-Block' : 'Block'; ?></a></td>
                </tr>
            <?php } ?>

            <?php foreach ($apiRows as $apiRow) { 
                $search = $apiRow->id;
                $isDataExist = array_filter($apiEventsUpdates, function($item) use ($search) {
                    return $item->event_id == $search;
                });

                $tag = isset($isDataExist[array_key_first($isDataExist)]->tag) ? $isDataExist[array_key_first($isDataExist)]->tag : '';
            ?>
                <tr>
                    <!-- <td class="manage-column ss-list-width"><?php // echo $apiRow->vendorId; ?></td> -->
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->eventname); ?></td>

                    <td class="manage-column ss-list-width"><?php echo strlen($apiRow->eventdescript) > 50 ? substr(htmlspecialchars($apiRow->eventdescript),0,30)."..." : $apiRow->eventdescript;; ?></td>
                    <td class="manage-column ss-list-width"><div data-id="<?php echo esc_html($apiRow->id) ?>" data-vendor="<?php echo esc_html($apiRow->vendorId) ?>" data-type="<?php echo 'api' ?>"><input type="text" class="tag-input" value="<?php echo esc_html($tag) ?>" /></div></td>
                    <td class="manage-column ss-list-width"><?php echo $apiRow->redirectShopUrl ? esc_html($apiRow->redirectShopUrl) : ('http://tiqs.com/alfred/events/shop/' . $apiRow->id) ?></td>
                    <td class="manage-column ss-list-width"><?php echo $apiRow->facebookUrl ? esc_html($apiRow->facebookUrl) : '---'; ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->eventCategory); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->eventVenue); ?></td>

                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->eventAddress); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->eventCity); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->eventZipcode); ?></td>

                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->eventCountry); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->StartDate); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->EndDate); ?></td>

                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->StartTime); ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->EndTime); ?></td>
                    <td class="manage-column ss-list-width"><?php echo (isset($apiRow->eventImage) && $apiRow->eventImage) ? ('<img width="100" src="' . esc_url('https://tiqs.com/alfred/assets/images/events/' . $apiRow->eventImage ) . '">') : '---'; ?></td>

                    <td class="manage-column ss-list-width">api</td>
                    <td class="manage-column ss-list-width"><?php echo in_array($apiRow->id, $blockedApiEvents) ? 'Yes' : 'No'; ?></td>
                    <td class="manage-column ss-list-width"><?php echo esc_html($apiRow->createdAt); ?></td>

                    <td><a href="<?php echo admin_url('admin.php?page=tiqs_events_block&type=api&id=' . $apiRow->id . '&vendorId=' . $apiRow->vendorId); ?>"><?php echo in_array($apiRow->id, $blockedApiEvents) ? 'Un-Block' : 'Block'; ?></a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}

function TOED_Get_page_url( $path = '' ) {
	$url = plugins_url( $path , __FILE__);

	if ( is_ssl()
	and 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}