<?php

namespace Tiqs_OldSkoolBelgium_Events;

class EventsFrontSide {
  
	function tiqs_events_info_short_code() {
		
		ob_start();

		$eventsNodes = $this->toed_get_all_events();
		$calenderHtml = '<div class="dates-boxes" data-slug="%DATESLUG%">
							<div class="date fw-bold font-vanguard lh-1">
								%START_DATE_NUMBER%
							</div>
							<div>
								<div class="week text-uppercase font-vanguard fw-bold lh-1">
									%START_DATE_DAY%
								</div>
								<div class="month text-uppercase font-vanguard fw-bold lh-1">
									%START_DATE_MONTH_NAME%
								</div>
							</div>
						</div>';
		$allEvents = "";
		$calInnerHtml = "";
		$this->load_event_css();
		foreach ($eventsNodes as $key => $value) {
			$detailPageLink = "#";
			if($value->type == 'manual') {
				$detailPageLink = esc_url( site_url('event-detail?event=m-' . $value->id) );
			} else {
				$detailPageLink = esc_url( site_url('event-detail?event=a-' . $value->event_id) );
			}
	
			$lessDescription = $this->get_less_description($value->description);
	
			$eventHtml = $this->toed_get_event_html();
			$eventHtml = str_replace("%IMAGE_LINK%",			esc_url( $value->image ),				$eventHtml);
			$eventHtml = str_replace("%BOOK_NOW%",				esc_url( $value->link ),				$eventHtml);
			$eventHtml = str_replace("%RSVP_LINK%",				esc_url( $value->facebookUrl ),				$eventHtml);
			$eventHtml = str_replace("%EVENT_NAME%",			wp_kses_post( $value->title ),				$eventHtml);
			$eventHtml = str_replace("%START_DATE_NUMBER%",		wp_kses_post( $value->day ),				$eventHtml);
			$eventHtml = str_replace("%START_DATE_DAY%",		wp_kses_post( $value->startdayname ),		$eventHtml);
			$eventHtml = str_replace("%START_DATE_MONTH_NAME%",	wp_kses_post( $value->startmonthname ),		$eventHtml);
			$eventHtml = str_replace("%START_TIME%",			wp_kses_post( $value->starttime ),			$eventHtml);
			$eventHtml = str_replace("%END_TIME%",				wp_kses_post( $value->endtime ),			$eventHtml);
			$eventHtml = str_replace("%END_DATE%",				wp_kses_post( $value->enddate ),			$eventHtml);
			$eventHtml = str_replace("%LESS_DESCRIPT%",			wp_kses_post( $lessDescription ),		$eventHtml);
			$eventHtml = str_replace("%READ_MORE_LINK%",		$lessDescription ? '<a class="read_more_link pointer">Read More</a>' : NULL,		$eventHtml);
			$eventHtml = str_replace("%DISPLAY_LESS_DESCRIPT%",	$lessDescription ? 'block' : 'none',		$eventHtml);
			$eventHtml = str_replace("%DISPLAY_MORE_DESCRIPT%",	(!$lessDescription && $value->description) ? 'block' : 'none',		$eventHtml);
			$eventHtml = str_replace("%MORE_DESCRIPT%",			wp_kses_post( $value->description ),		$eventHtml);
			$eventHtml = str_replace("%DETAIL_LINK%",			$detailPageLink,			$eventHtml);
			$eventHtml = str_replace("%DATESLUG%", wp_kses_post( $value->day . $value->startdayname . $value->startmonthname ),	$eventHtml);
			$allEvents .= $eventHtml;
	
			$calHtml = $calenderHtml;
			$calHtml = str_replace("%START_DATE_NUMBER%",	wp_kses_post( $value->day ),	$calHtml);
			$calHtml = str_replace("%START_DATE_DAY%",		wp_kses_post( $value->startdayname ),	$calHtml);
			$calHtml = str_replace("%START_DATE_MONTH_NAME%", wp_kses_post( $value->startmonthname ),	$calHtml);
			$calHtml = str_replace("%DATESLUG%", wp_kses_post( $value->day . $value->startdayname . $value->startmonthname ),	$calHtml);
			$calInnerHtml .= $calHtml;
		}
	
		$calenderHtml = '<div class="dates-boxes-wrapper">' . $calInnerHtml . '</div>';
	
		echo wp_kses_post($calenderHtml . $allEvents);

		return ob_get_clean();
	}
	 
	function tiqs_events_firstevent_short_code() {
		ob_start();
		$eventsNodes = $this->toed_get_all_events();
		$value = isset($eventsNodes[0]) ? $eventsNodes[0] : NULL;
		$eventHtml = '';
	
		if ($value) {
			$this->load_event_css();
			$detailPageLink = "#";
			if ($value->type == 'manual') {
				$detailPageLink = esc_url( site_url('event-detail?event=m-' . $value->id) );
			} else {
				$detailPageLink = esc_url( site_url('event-detail?event=a-' . $value->event_id) );
			}
	
			$lessDescription = $this->get_less_description($value->description);
	
			$eventHtml = $this->TOED_GetEventHtml();
			$eventHtml = str_replace("%IMAGE_LINK%",			esc_url( $value->image ),				$eventHtml);
			$eventHtml = str_replace("%BOOK_NOW%",				esc_url( $value->link ),				$eventHtml);
			$eventHtml = str_replace("%RSVP_LINK%",				esc_url( $value->facebookUrl ),				$eventHtml);
			$eventHtml = str_replace("%EVENT_NAME%",			wp_kses_post( $value->title ),				$eventHtml);
			$eventHtml = str_replace("%START_DATE_NUMBER%",		wp_kses_post( $value->day ),				$eventHtml);
			$eventHtml = str_replace("%START_DATE_DAY%",		wp_kses_post( $value->startdayname ),		$eventHtml);
			$eventHtml = str_replace("%START_DATE_MONTH_NAME%",	wp_kses_post( $value->startmonthname ),		$eventHtml);
			$eventHtml = str_replace("%START_TIME%",			wp_kses_post( $value->starttime ),			$eventHtml);
			$eventHtml = str_replace("%END_TIME%",				wp_kses_post( $value->endtime ),			$eventHtml);
			$eventHtml = str_replace("%END_DATE%",				wp_kses_post( $value->enddate ),			$eventHtml);
			$eventHtml = str_replace("%LESS_DESCRIPT%",			esc_html( $lessDescription ),		$eventHtml);
			$eventHtml = str_replace("%READ_MORE_LINK%",		$lessDescription ? '<span class="read_more_link pointer">Read More</span>' : NULL,		$eventHtml);
			$eventHtml = str_replace("%DISPLAY_LESS_DESCRIPT%",	$lessDescription ? 'block' : 'none',		$eventHtml);
			$eventHtml = str_replace("%DISPLAY_MORE_DESCRIPT%",	(!$lessDescription && $value->description) ? 'block' : 'none',		$eventHtml);
			$eventHtml = str_replace("%MORE_DESCRIPT%",			esc_html( $value->description ),		$eventHtml);
			$eventHtml = str_replace("%DETAIL_LINK%",			$detailPageLink,			$eventHtml);
		}
		
		echo wp_kses_post($eventHtml);
		return ob_get_clean();
	}
	

	function tiqs_event_detail_info_short_code() {
		ob_start();
	
		if ( isset( $_GET['event'] ) && $_GET['event'] ) {
			$exploded_url = explode( '-', sanitize_text_field( wp_unslash( $_GET['event'] ) ) );
		
			if ( isset( $exploded_url[0] ) && isset( $exploded_url[1] ) && ( $exploded_url[0] == 'm' || $exploded_url[0] == 'a' ) ) {
				$eventDetail = $this->toed_get_single_event( $exploded_url[0], $exploded_url[1] );
		
				if ( $eventDetail ) {
					$this->load_event_details_css();
					$eventDetailHtml = $this->get_event_detail_html(); // This function is not defined in the code provided, so you will need to define it
					
					$eventDetailHtml = str_replace( '%IMAGE_LINK%', esc_url( $eventDetail->image ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%BOOK_NOW%', esc_url( $eventDetail->link ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%RSVP_LINK%', esc_url( $eventDetail->facebookUrl ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%EVENT_NAME%', wp_kses_post( $eventDetail->title ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%START_DATE_NUMBER%', wp_kses_post( $eventDetail->day ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%START_DATE_DAY%', wp_kses_post( $eventDetail->startdayname ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%START_DATE_MONTH_NAME%', wp_kses_post( $eventDetail->startmonthname ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%START_TIME%', wp_kses_post( $eventDetail->starttime ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%END_TIME%', wp_kses_post( $eventDetail->endtime ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%END_DATE%', wp_kses_post( $eventDetail->enddate ), $eventDetailHtml );
					$eventDetailHtml = str_replace( '%DESCRIPT%', wp_kses_post( $eventDetail->description ), $eventDetailHtml );
		
					echo wp_kses_post( $eventDetailHtml );
				}
			}
		}
		return ob_get_clean();
		
	}

	function toed_get_all_events() {
		global $wpdb;
		$table_name = $wpdb->prefix . "tiqs_events";

		$rows = [];
		$blockedApiEvents = [];
		$apiEventsUpdates = [];

		if(get_option('tiqs_events_info')) {
			
			$rows = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where type = 'manual' AND is_blocked = '0' AND vendorId=%s", get_option('tiqs_events_info')));

			$blockedApiEvents = $wpdb->get_results($wpdb->prepare("SELECT GROUP_CONCAT(event_id) AS 'eventId' FROM $table_name WHERE type = 'api' AND is_blocked = '1' AND vendorId=%s", get_option('tiqs_events_info')));
		
			$blockedApiEvents = isset($blockedApiEvents[0]->eventId) ? $HiddenProducts = explode(',',$blockedApiEvents[0]->eventId) : array();

			$apiEventsUpdates = $wpdb->get_results($wpdb->prepare("SELECT event_id, vendorId,tag FROM $table_name WHERE type = 'api' AND tag != '' AND tag IS NOT NULL AND vendorId=%s", get_option('tiqs_events_info')));
		}

		$events_plugin = new \Tiqs_OldSkoolBelgium_Events\EventsPlugin();
        $events = $events_plugin->get_api_events();

		$allEvents = array();
		foreach ($rows as $rKey => $rValue) {
			$obj = new \stdClass();
			$obj->color = "3";
			$obj->title = $rValue->eventname;
			$obj->description = $rValue->eventdescript;
			$obj->duration = "1";
			$obj->location = $rValue->eventAddress . ', ' . $rValue->eventCity . ', ' . $rValue->eventCountry;
			$obj->time = date("H:i", strtotime($rValue->StartTime)) . ' - ' . date("H:i", strtotime($rValue->EndTime));
			$obj->starttime = date("H:i", strtotime($rValue->StartTime));
			$obj->endtime = date("H:i", strtotime($rValue->EndTime));
			$obj->startdate = $rValue->StartDate;
			$obj->day = date("d", strtotime($rValue->StartDate));
			$obj->startdayname = date("D", strtotime($rValue->StartDate));
			$obj->month = date("m", strtotime($rValue->StartDate));
			$obj->startmonthname = date("M", strtotime($rValue->StartDate));
			$obj->year = date("Y", strtotime($rValue->StartDate));
			$obj->enddate = date("d-m-Y", strtotime($rValue->EndDate));
			$obj->image = $rValue->eventImage;
			$obj->id = $rValue->id;
			$obj->type = $rValue->type;
			$obj->facebookUrl = $rValue->facebookUrl;
			$link = $rValue->url;

			if($rValue->tag) {
				$link .= '?tag=' . $rValue->tag;
			}

			if(get_option('tiqs_events_affiliate')) {
				if($rValue->tag) {
					$link .= '&';
				} else {
					$link .= '?';
				}
				$link .= 'AMB=' . get_option('tiqs_events_affiliate');
			}

			$obj->link = $link;

			array_push($allEvents,$obj);
		}

		foreach ($events as $key => $value) {
			if(!in_array($value->id, $blockedApiEvents)) {
				$obj = new \stdClass();
				$obj->color = "3";
				$obj->title = $value->eventname;
				$obj->description = $value->eventdescript;
				$obj->duration = "1";
				$obj->location = $value->eventAddress . ', ' . $value->eventCity . ', ' . $value->eventCountry;
				$obj->time = date("H:i", strtotime($value->StartTime)) . ' - ' . date("H:i", strtotime($value->EndTime));
				$obj->starttime = date("H:i", strtotime($value->StartTime));
				$obj->endtime = date("H:i", strtotime($value->EndTime));
				$obj->startdate = $value->StartDate;
				$obj->day = date("d", strtotime($value->StartDate));
				$obj->startdayname = date("D", strtotime($value->StartDate));
				$obj->month = date("m", strtotime($value->StartDate));
				$obj->startmonthname = date("M", strtotime($value->StartDate));
				$obj->year = date("Y", strtotime($value->StartDate));
				$obj->enddate = date("d-m-Y", strtotime($value->EndDate));
				$obj->image = 'https://tiqs.com/alfred/assets/images/events/' . $value->eventImage;
				$obj->event_id = $value->id;
				$obj->type = 'api';
				$obj->facebookUrl = $value->facebookUrl;
				$obj->amb = get_option('tiqs_events_affiliate');

				$search = $value->id;
				$isDataExist = array_filter($apiEventsUpdates, function($item) use ($search) {
					return $item->event_id == $search;
				});

				$tag = isset($isDataExist[array_key_first($isDataExist)]->tag) ? $isDataExist[array_key_first($isDataExist)]->tag : '';


				$link = $value->redirectShopUrl && $this->check_shop_status($value->id) ? $value->redirectShopUrl : ('http://tiqs.com/alfred/events/shop/' . $value->id);

				if($tag) {
					$link .= '?tag=' . $tag;
				}

				if(get_option('tiqs_events_affiliate')) {
					if($tag) {
						$link .= '&';
					} else {
						$link .= '?';
					}
					$link .= 'AMB=' . get_option('tiqs_events_affiliate');
				}

				$obj->link = $link;

				array_push($allEvents,$obj);
			}
		}

		usort($allEvents, function($a1, $a2) {
			$value1 = strtotime($a1->startdate);
			$value2 = strtotime($a2->startdate);
			return $value1 - $value2;
		});

		return $allEvents;
	}

	function toed_get_single_event($type , $id) {
		$obj = new \stdClass();
		global $wpdb;
		$table_name = $wpdb->prefix . "tiqs_events";
		if($type == 'm') {
			$records = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where id=%s AND $type = 'manual' AND is_blocked = '0' AND vendorId=%s", get_option('tiqs_events_info') , $id));

			if(isset($records[0])) {
				$rValue = $records[0];
				$obj->color = "3";
				$obj->title = $rValue->eventname;
				$obj->description = $rValue->eventdescript;
				$obj->duration = "1";
				$obj->location = $rValue->eventAddress . ', ' . $rValue->eventCity . ', ' . $rValue->eventCountry;
				$obj->time = date("H:i", strtotime($rValue->StartTime)) . ' - ' . date("H:i", strtotime($rValue->EndTime));
				$obj->starttime = date("H:i", strtotime($rValue->StartTime));
				$obj->endtime = date("H:i", strtotime($rValue->EndTime));
				$obj->startdate = $rValue->StartDate;
				$obj->day = date("d", strtotime($rValue->StartDate));
				$obj->startdayname = date("D", strtotime($rValue->StartDate));
				$obj->month = date("m", strtotime($rValue->StartDate));
				$obj->startmonthname = date("M", strtotime($rValue->StartDate));
				$obj->year = date("Y", strtotime($rValue->StartDate));
				$obj->enddate = date("d-m-Y", strtotime($rValue->EndDate));
				$obj->image = $rValue->eventImage;
				$obj->id = $rValue->id;
				$obj->type = $rValue->type;
				$obj->facebookUrl = $rValue->facebookUrl;
				$link = $rValue->url;

				if($rValue->tag) {
					$link .= '?tag=' . $rValue->tag;
				}

				if(get_option('tiqs_events_affiliate')) {
					if($rValue->tag) {
						$link .= '&';
					} else {
						$link .= '?';
					}
					$link .= 'AMB=' . get_option('tiqs_events_affiliate');
				}

				$obj->link = $link;
			}

		} else if($type == 'a') { 
			$body = array('eventId' => $id);
			$data = array(
				'body'	=> $body
			);
			$response = wp_remote_post( 'https://tiqs.com/alfred/Api/ScannerApiV2/OneEvent', $data );
			$resp     = wp_remote_retrieve_body( $response );
		
			$records = json_decode($resp);

			if(isset($records[0])) {
				$apiEventsUpdates = $wpdb->get_results($wpdb->prepare("SELECT event_id, vendorId,tag FROM $table_name WHERE type = 'api' AND event_id != $id AND tag != '' AND tag IS NOT NULL AND vendorId=%s", get_option('tiqs_events_info')));

				$value = $records[0];
				$obj->color = "3";
				$obj->title = $value->eventname;
				$obj->description = $value->eventdescript;
				$obj->duration = "1";
				$obj->location = $value->eventAddress . ', ' . $value->eventCity . ', ' . $value->eventCountry;
				$obj->time = date("H:i", strtotime($value->StartTime)) . ' - ' . date("H:i", strtotime($value->EndTime));
				$obj->starttime = date("H:i", strtotime($value->StartTime));
				$obj->endtime = date("H:i", strtotime($value->EndTime));
				$obj->startdate = $value->StartDate;
				$obj->day = date("d", strtotime($value->StartDate));
				$obj->startdayname = date("D", strtotime($value->StartDate));
				$obj->month = date("m", strtotime($value->StartDate));
				$obj->startmonthname = date("M", strtotime($value->StartDate));
				$obj->year = date("Y", strtotime($value->StartDate));
				$obj->enddate = date("d-m-Y", strtotime($value->EndDate));
				$obj->image = 'https://tiqs.com/alfred/assets/images/events/' . $value->eventImage;
				$obj->event_id = $value->id;
				$obj->type = 'api';
				$obj->amb = get_option('tiqs_events_affiliate');
				$obj->facebookUrl = $value->facebookUrl;

				$search = $value->id;
				$isDataExist = array_filter($apiEventsUpdates, function($item) use ($search) {
					return $item->event_id == $search;
				});

				$tag = isset($isDataExist[array_key_first($isDataExist)]->tag) ? $isDataExist[array_key_first($isDataExist)]->tag : '';

				$link = $value->redirectShopUrl && $this->check_shop_status($value->id) ? $value->redirectShopUrl : ('http://tiqs.com/alfred/events/shop/' . $value->id);

				if($tag) {
					$link .= '?tag=' . $tag;
				}

				if(get_option('tiqs_events_affiliate')) {
					if($tag) {
						$link .= '&';
					} else {
						$link .= '?';
					}
					$link .= 'AMB=' . get_option('tiqs_events_affiliate');
				}

				$obj->link = $link;
			}
		}

		return $obj;
	}

	function toed_get_event_html() {
		return '<div id="%DATESLUG%" class="elementor-element elementor-element-2b1e1c1 elementor-grid-1 elementor-grid-tablet-1 elementor-grid-mobile-1 elementor-widget elementor-widget-loop-grid" data-id="2b1e1c1" data-element_type="widget" data-settings="{&quot;template_id&quot;:56,&quot;columns&quot;:1,&quot;columns_tablet&quot;:1,&quot;pagination_type&quot;:&quot;numbers_and_prev_next&quot;,&quot;_skin&quot;:&quot;post&quot;,&quot;columns_mobile&quot;:&quot;1&quot;,&quot;row_gap&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;row_gap_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;row_gap_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="loop-grid.post">
			<div class="elementor-widget-container">
				<div class="elementor-loop-container elementor-grid">
					
					<div data-elementor-type="loop-item" data-elementor-id="56" class="elementor elementor-56 e-loop-item-95 e-loop-item">
						<div class="elementor-element elementor-element-1b2c8ae e-con-boxed e-con" data-id="1b2c8ae" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;content_width&quot;:&quot;boxed&quot;}">
							<div class="e-con-inner" style="align-items: start;">
								<div class="elementor-element elementor-element-2846e20 elementor-widget elementor-widget-theme-post-featured-image elementor-widget-image" data-id="2846e20" data-element_type="widget" data-widget_type="theme-post-featured-image.default">
									<div class="elementor-widget-container">
										<a href="%DETAIL_LINK%">
										<img decoding="async" width="800" height="420" src="%IMAGE_LINK%" class="attachment-large size-large wp-image-48" alt="%EVENT_NAME%" loading="lazy" sizes="(max-width: 800px) 100vw, 800px">								</a>
									</div>
								</div>
								<div class="elementor-element elementor-element-dc45d04 e-con-boxed e-con" data-id="dc45d04" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;boxed&quot;}">
									<div class="e-con-inner">
										<div class="elementor-element elementor-element-52d58b3 elementor-widget elementor-widget-heading" data-id="52d58b3" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<h2 class="elementor-heading-title elementor-size-default"><a href="%DETAIL_LINK%"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">%EVENT_NAME%</font></font></a></h2>
											</div>
										</div>
										<div class="elementor-element elementor-element-9784b65 e-con-boxed e-con" data-id="9784b65" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;boxed&quot;}">
											<div class="e-con-inner">
												<div class="elementor-element elementor-element-6934619 e-con-boxed e-con" data-id="6934619" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;boxed&quot;}">
													<div class="e-con-inner">
														<div class="elementor-element elementor-element-01829f3 elementor-widget elementor-widget-shortcode" data-id="01829f3" data-element_type="widget" data-widget_type="shortcode.default">
															<div class="elementor-widget-container">
																<div class="elementor-shortcode">
																	<div class="eventdatetime">
																		<div class="startdateblock">
																			<span class="numberOfDay"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">%START_DATE_NUMBER%</font></font></span>
																			<div><span class="nameOfDay"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">%START_DATE_DAY% </font></font></span><br><span class="nameOfMonth"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">%START_DATE_MONTH_NAME%</font></font></span></div>
																		</div>
																		<div class="extradateinfoblock"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">From %START_TIME% </font></font><br><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">To %END_TIME% %END_DATE%</font></font></div>
																	</div>
																	
																</div>
															</div>
														</div>
														<div class="elementor-element elementor-element-8e709d6 elementor-widget elementor-widget-shortcode" data-id="8e709d6" data-element_type="widget" data-widget_type="shortcode.default">
															<div class="elementor-widget-container">
																<div class="elementor-shortcode">
																	<div class="eventbuttonsblock"><a href="%BOOK_NOW%" class="eventbtn" target="_blank" rel="noopener"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">TICKETS</font></font></a><a href="%RSVP_LINK%" class="eventbtn" target="_blank" rel="noopener"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">RSVP</font></font></a></div>
																	
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="elementor-element elementor-element-96fad1f elementor-widget elementor-widget-theme-post-excerpt" data-id="96fad1f" data-element_type="widget" data-widget_type="theme-post-excerpt.default">
											<div class="elementor-widget-container read-less" style="display: %DISPLAY_LESS_DESCRIPT%;">%LESS_DESCRIPT% %READ_MORE_LINK%</div>
											<div class="elementor-widget-container read-more" style="display: %DISPLAY_MORE_DESCRIPT%;">%MORE_DESCRIPT% <span class="read_less_link pointer">Read Less</span></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}

	function get_event_detail_html() {
		return '<div id="DIV_1">
					<div id="DIV_2" style="background: rgba(0, 0, 0, 0) url(%IMAGE_LINK%) no-repeat scroll 50% 50% / cover padding-box border-box;">
						<div id="DIV_3">
							<div id="DIV_4">
								<div id="DIV_5">
									
									<div id="DIV_7">
										<div id="DIV_8">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="DIV_9">
						<div id="DIV_10">
							<div id="DIV_11">
								<div id="DIV_12">
									<div id="DIV_13">
										
										<h1 id="H1_15">
											%EVENT_NAME%
										</h1>
									</div>
								</div>
								<div id="DIV_16">
									<div id="DIV_17">
										<div id="DIV_18">
											<div id="DIV_19">
												<div id="DIV_20">
													<span id="SPAN_21">%START_DATE_NUMBER%</span>
													<div id="DIV_22">
														<span id="SPAN_23">%START_DATE_DAY%</span><br id="BR_24" /><span id="SPAN_25">%START_DATE_MONTH_NAME%</span>
													</div>
												</div>
												<div id="DIV_26">
													Vanaf %START_TIME%<br id="BR_27" />Tot %END_TIME% %END_DATE%
												</div>
											</div>
											
										</div>
									</div>
								</div>
							</div>
							<div id="DIV_29">
								<div id="DIV_30">
									<div id="DIV_31">
										<div id="DIV_32">
											<div id="DIV_33">
												<a id="A_34" href="%BOOK_NOW%">TICKETS</a><a href="%RSVP_LINK%" id="A_35">RSVP</a>
											</div>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="DIV_37">
						<div id="DIV_38">
							<div id="DIV_39">
								<div id="DIV_40">
									<div id="DIV_41">
										%DESCRIPT%
										<p id="P_89">
											<a href="mailto:support@tiqs.com?subject=TOTALLY%2080%27S%20-%20THE%20NYE%20EDITION&amp;body=Please%20specify%20your%20question%20here:" rel="noreferrer noopener" id="A_90">CONTACT SUPPORT</a>
										</p>
										<p id="P_91">
											<!-- /wp:paragraph -->
											<!-- wp:paragraph -->
										</p>
										<p id="P_92">
											<a href="http://maps.google.com/?q=Zaal+Lux%2C+Dendermondsesteenweg+140%2C+Belgium" rel="noreferrer noopener" id="A_93">Zaal Lux, Dendermondsesteenweg 140, Belgium</a>
										</p>
										<p id="P_94">
											<!-- /wp:paragraph -->
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
	}

	function get_file_url( $path = '' ) {
		
		$url = plugins_url( $path , __FILE__);

		if ( is_ssl()
		and 'http:' == substr( $url, 0, 5 ) ) {
			$url = 'https:' . substr( $url, 5 );
		}

		return $url;
	}

	function load_event_details_css() {
		wp_register_style( 'tiqs-events-1', $this->get_file_url('assets/css/event-details.css') ); 
		wp_enqueue_style( 'tiqs-events-1' );
	}

	function load_event_css() {
		wp_register_style( 'tiqs-events-2', $this->get_file_url('assets/css/events.css') ); 
		wp_enqueue_style( 'tiqs-events-2' );
	}

	public static function tiqs_events_header() {
		global $post;
		
		if(has_shortcode($post->post_content, 'tiqs-events')) { ?>
			<style>
				.dates-boxes-wrapper{
					display: flex;
					flex-wrap: wrap;
					justify-content: center;
					gap: 40px;
					margin: 40px 0 20px;
				}

				.dates-boxes-wrapper .dates-boxes {
					border-radius: 12px;
					background-color: #7CD444;
					color: #fff;
					padding: 0px 10px;
					cursor: pointer;
					width: 85px;
					height: 69px;
					display: flex;
					justify-content: space-between;
					align-items: center;
					transition: all 0.3s ease-in-out;
				}
				.dates-boxes-wrapper .dates-boxes:hover {
					background-color: #fff;
					color: #7CD444;
				}
				.dates-boxes-wrapper .date{
					font-size: 2.7rem;
					line-height: 22px;
				}
				.dates-boxes-wrapper .week{
					margin-bottom: 4px;
					
				}
				.dates-boxes-wrapper .week,
				.dates-boxes-wrapper .month{
				font-size: 1.1rem;
				}
					.font-vanguard{
					font-family: "vanguard";
					}
				.lh-1 {
					line-height: 1;
				}

				.fw-bold {
					font-weight: 700;
				}

				.text-uppercase {
					text-transform: uppercase;
				}
			</style>
		<?php }
		
	}

	public static function tiqs_events_footer_js() {
		global $post;
		
		if(has_shortcode($post->post_content, 'tiqs-events') || has_shortcode($post->post_content, 'tiqs-events-upcomming')) { ?>
			<script>
				jQuery(document).ready(function() {
					jQuery(document).on("click" , ".read_more_link" , function(e) {
						jQuery(this).parent().hide()
						jQuery(this).parent().siblings().show()
					})

					jQuery(document).on("click" , ".read_less_link" , function(e) {
						jQuery(this).parent().hide()
						jQuery(this).parent().siblings().show()
					})

					jQuery(document).on("click" , ".dates-boxes" , function(e) {
						const dateSlug = jQuery(this).data("slug")
						jQuery('html, body').animate({
							scrollTop: (jQuery("#" + dateSlug).offset().top - 80)
						}, 1000);
					})
				})
			</script>
		<?php }
		
	}

	function check_shop_status($eventId) {
		$body = array('eventId' => $eventId);
		$data = array(
			'body'	=> $body
		);
		$response = wp_remote_post( 'https://tiqs.com/alfred/Api/Eventsnew/check_active_shop', $data );
		$resp     = wp_remote_retrieve_body( $response );

		$records = json_decode($resp);

		if(isset($records->status) && $records->status == 1) {
			return true;
		}
		return false;
	}

	function get_less_description($description) {
		$description = trim($description);
	
		if ($description && strlen($description) > 100) {
			libxml_use_internal_errors(true); // Enable error handling for the DOMDocument
			$dom = new \DOMDocument;
			$dom->loadHTML('<?xml encoding="UTF-8">' . $description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); // Add XML declaration to handle special characters
			
			$body = $dom->getElementsByTagName('body')->item(0);
			if ($body) {
				$elements = $body->childNodes;
				if ($elements->length > 0) {
					$text = $elements->item(0)->nodeValue;
					return substr($text, 0, 100) . '...';
				}
			}
		}
	
		return false;
	}
	
}
