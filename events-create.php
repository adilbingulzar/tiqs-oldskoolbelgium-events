<?php

function tiqs_events_create() {
    $vendorId       = isset($_POST["vendorId"])         ? sanitize_text_field($_POST["vendorId"])        : '';
    $eventname      = isset($_POST["eventname"])        ? sanitize_text_field($_POST["eventname"])       : '';
    $eventdescript  = isset($_POST["eventdescript"])    ? sanitize_text_field($_POST["eventdescript"])   : '';

    $eventCategory  = isset($_POST["eventCategory"])    ? sanitize_text_field($_POST["eventCategory"])   : '';
    $eventVenue     = isset($_POST["eventVenue"])       ? sanitize_text_field($_POST["eventVenue"])      : '';
    $eventAddress   = isset($_POST["eventAddress"])     ? sanitize_text_field($_POST["eventAddress"])    : '';
    
    $eventCity      = isset($_POST["eventCity"])        ? sanitize_text_field($_POST["eventCity"])       : '';
    $eventZipcode   = isset($_POST["eventZipcode"])     ? sanitize_text_field($_POST["eventZipcode"])    : '';
    $eventCountry   = isset($_POST["eventCountry"])     ? sanitize_text_field($_POST["eventCountry"])    : '';

    $StartDate      = isset($_POST["StartDate"])        ? sanitize_text_field($_POST["StartDate"])       : '';
    $EndDate        = isset($_POST["EndDate"])          ? sanitize_text_field($_POST["EndDate"])         : '';
    $StartTime      = isset($_POST["StartTime"])        ? sanitize_text_field($_POST["StartTime"])       : '';

    $EndTime        = isset($_POST["EndTime"])          ? sanitize_text_field($_POST["EndTime"])         : '';
    $url            = isset($_POST["url"])              ? sanitize_text_field($_POST["url"])             : '';
    $facebookUrl    = isset($_POST["facebookUrl"])      ? sanitize_text_field($_POST["facebookUrl"])     : '';


    //insert
    if (isset($_POST['insert'])) {
        

        global $wpdb;
        $table_name = $wpdb->prefix . "tiqs_events";

        $upload = wp_handle_upload( 
            $_FILES[ 'eventImage' ], 
            array( 'test_form' => false ) 
        );

        $wpdb->insert(
                $table_name, //table
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

                    'StartDate'     => date("Y-m-d", strtotime($StartDate)),
                    'EndDate'       => date("Y-m-d", strtotime($EndDate)),
                    'StartTime'     => date("H:i:s", strtotime($StartTime)),

                    'EndTime'       => date("H:i:s", strtotime($EndTime)),
                    'eventImage'    => $upload['url'],
                    'url'           => $url,
                    'facebookUrl'   => $facebookUrl,
                    'createdAt'     => date("Y-m-d H:i:s"),
                    'updatedAt'     => date("Y-m-d H:i:s"),

                ), //data		
        );
        $message ="Event insert successfully.";
    }
    ?>
    <link type="text/css" href="<?php echo esc_url(WP_PLUGIN_URL . '/tiqs-events/style-admin.css') ?>" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New Event</h2>
        <?php if (isset($message)): ?>
            <div class="updated">
                <p><?php echo esc_html($message); ?></p>
            </div>
            <a href="<?php echo admin_url('admin.php?page=tiqs_events_list') ?>">&laquo; Back to events list</a>
        <?php exit; endif; ?>
        <form method="post" enctype="multipart/form-data" action="<?php echo esc_html($_SERVER['REQUEST_URI']); ?>">
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Vendor Id</th>
                    <td>
                        <input type="text" name="vendorId" value="<?php echo esc_html($vendorId); ?>" class="ss-field-width" placeholder="Vendor Id" required />
                    </td>
                    <th class="ss-th-width">Event Name</th>
                    <td>
                        <input type="text" name="eventname" value="<?php echo esc_html($eventname); ?>" class="ss-field-width" placeholder="Event Name" required />
                    </td>
                </tr>
                <tr>
                    <th class="ss-th-width">Event Description</th>
                    <td>
                        <input type="text" name="eventdescript" value="<?php echo esc_html($eventdescript); ?>" class="ss-field-width" placeholder="Description" required />
                    </td>
                    <th class="ss-th-width">Event Category</th>
                    <td>
                        <input type="text" name="eventCategory" value="<?php echo esc_html($eventCategory); ?>" class="ss-field-width" placeholder="Category" required />
                    </td>
                </tr>
                <tr>
                    <th class="ss-th-width">Event Venue</th>
                    <td><input type="text" name="eventVenue" value="<?php echo esc_html($eventVenue); ?>" class="ss-field-width" placeholder="Venue" required /></td>
                    <th class="ss-th-width">Event Address</th>
                    <td><input type="text" name="eventAddress" value="<?php echo esc_html($eventAddress); ?>" class="ss-field-width" placeholder="Address" required /></td>
                </tr>


                <tr>
                    <th class="ss-th-width">Event City</th>
                    <td><input type="text" name="eventCity" value="<?php echo esc_html($eventCity); ?>" class="ss-field-width" placeholder="City" required /></td>
                    <th class="ss-th-width">Event Zip Code</th>
                    <td><input type="text" name="eventZipcode" value="<?php echo esc_html($eventZipcode); ?>" class="ss-field-width" placeholder="Zip Code" required /></td>
                </tr>
                <tr>
                    <th class="ss-th-width">Event Country</th>
                    <td><input type="text" name="eventCountry" value="<?php echo esc_html($eventCountry); ?>" class="ss-field-width" placeholder="Country" required /></td>
                    <th class="ss-th-width">Start Date</th>
                    <td><input type="date" name="StartDate" value="<?php echo esc_html($StartDate); ?>" class="ss-field-width" placeholder="Start Date" required /></td>
                </tr>

                <tr>
                    <th class="ss-th-width">End Date</th>
                    <td><input type="date" name="EndDate" value="<?php echo esc_html($EndDate); ?>" class="ss-field-width" placeholder="End Date" required /></td>
                    <th class="ss-th-width">Start Time</th>
                    <td><input type="time" name="StartTime" value="<?php echo esc_html($StartTime); ?>" class="ss-field-width" placeholder="Start Time" required /></td>
                </tr>



                <tr>
                    <th class="ss-th-width">End Time</th>
                    <td><input type="time" name="EndTime" value="<?php echo esc_html($EndTime); ?>" class="ss-field-width" placeholder="End Time" /></td>
                    <th class="ss-th-width">Event Image</th>
                    <td><input type="file" accept="image/jpg, image/jpeg, image/png" name="eventImage" class="ss-field-width" placeholder="Image" /></td>
                </tr>

                <tr>
                    <th class="ss-th-width">URL</th>
                    <td><input type="url" name="url" value="<?php echo esc_html($url); ?>" class="ss-field-width" placeholder="URL" /></td>
                    <th class="ss-th-width">Facebook Url</th>
                    <td><input type="url" name="facebookUrl" value="<?php echo esc_html($facebookUrl); ?>" class="ss-field-width" placeholder="Facebook Url" /></td>
                </tr>

            </table>
            <br>
            <input type='submit' name="insert" value='Save' class='button'>
        </form>
    </div>
    <?php
}