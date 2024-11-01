<?php

if ( !defined( 'ABSPATH' ) ) {

	exit;

}

if ( !class_exists( 'WPCS_Content_Scheduler_Dashboard' ) ) {

	class WPCS_Content_Scheduler_Dashboard {

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'menu_pages' ) );
			add_action( 'init', array( $this, 'json_endpoint_rewrites' ) );
			add_action( 'template_redirect', array( $this, 'json_endpoint_data_events' ) );
			add_action( 'wp_ajax_wpcs_content_scheduler_save_event_ajax', array( $this, 'save_event_ajax' ) );

		}

		public function menu_pages() {

			add_menu_page(
				__( 'Content Scheduler', 'wpcs-content-scheduler' ),
				__( 'Content Scheduler', 'wpcs-content-scheduler' ),
				apply_filters( 'wpcs_content_scheduler_capability', WPCS_CONTENT_SCHEDULER_CAPABILITY_DEFAULT ),
				'wpcs-content-scheduler',
				array( $this, 'page' ),
				'dashicons-calendar-alt',
				'26'
			);

			add_submenu_page( // This effectively removes the default sub menu which would normally get added by default
				'wpcs-content-scheduler', // points to top level menu_page page
				__( 'Content Scheduler - Dashboard', 'wpcs-content-scheduler' ), // This will end up as the browser title for all pages
				__( 'Dashboard', 'wpcs-content-scheduler' ),
				apply_filters( 'wpcs_content_scheduler_capability', WPCS_CONTENT_SCHEDULER_CAPABILITY_DEFAULT ),
				'wpcs-content-scheduler' // points to top level menu_page page
			);

		}

		public function page() {

			global $_wp_admin_css_colors;

			$settings = WPCS_Content_Scheduler_Settings::get();

			$post_types = ( isset( $settings['post_types'] ) ? $settings['post_types'] : array() );
			$post_statuses = ( isset( $settings['post_statuses'] ) ? $settings['post_statuses'] : array() );
			$taxonomies = ( isset( $settings['taxonomies'] ) ? $settings['taxonomies'] : array() );
			$user_roles = ( isset( $settings['user_roles'] ) ? $settings['user_roles'] : array() );
			$notes = ( isset( $settings['notes'] ) ? $settings['notes'] : 'no' );

			$users = get_users(
				array(
					'role__in'	=> $user_roles,
					'fields'	=> array(
						'ID',
						'user_nicename',
						'user_email',
					)
				)
			);

			$dashboard_color_scheme = get_user_option( 'admin_color', get_current_user_id() );
			$dashboard_color_scheme = ( !empty( $dashboard_color_scheme ) ? $_wp_admin_css_colors[$dashboard_color_scheme] : false );

			if ( !empty( $dashboard_color_scheme ) ) { ?>

				<style>
					#wpcs-content-scheduler-calendar .fc-button {
						background: <?php echo esc_html( $dashboard_color_scheme->colors[2] ); ?> !important;
					}
					#wpcs-content-scheduler-calendar .fc-button.fc-button-active {
						background: <?php echo esc_html( $dashboard_color_scheme->colors[3] ); ?> !important;
					}
				</style>

			<?php }	?>

			<div class="wrap">
				<h1 class="wp-heading-inline"><?php esc_html_e( 'Content Scheduler - Dashboard', 'wpcs-content-scheduler' ); ?></h1>
				<a href="<?php echo admin_url( 'admin.php?page=wpcs-content-scheduler-settings' ); ?>" class="page-title-action"><?php esc_html_e( 'Settings', 'wpcs-content-scheduler' ); ?></a>
				<div id="wpcs-content-scheduler-dashboard">
					<div id="wpcs-content-scheduler-sidebar">
						<div id="wpcs-content-scheduler-sidebar-section-add-new" class="wpcs-content-scheduler-sidebar-section">
							<label for="wpcs-content-scheduler-add-new-post-type" class="wpcs-content-scheduler-sidebar-section-heading"><?php esc_html_e( 'Add new', 'wpcs-content-scheduler' ); ?></label>
							<select id="wpcs-content-scheduler-add-new-post-type" class="wpcs-content-scheduler-select2">
								<?php foreach ( $post_types as $post_type ) {
									$post_type_object = get_post_type_object( $post_type ); ?>
									<option value="<?php echo esc_url( get_admin_url() ) . 'post-new.php?post_type=' . $post_type . '&wpcs_content_scheduler_popup=1'; ?>"><?php echo $post_type_object->labels->singular_name; ?></option>
								<?php } ?>
							</select>
							<button id="wpcs-content-scheduler-add-new" class="button button-primary button-small"><?php esc_html_e( 'Add', 'wpcs-content-scheduler' ); ?></button>
						</div>
						<?php if ( !empty( $post_types ) ) { ?>

							<div id="wpcs-content-scheduler-sidebar-section-post-types" class="wpcs-content-scheduler-sidebar-section">
								<label for="wpcs-content-scheduler-filter-post-type" class="wpcs-content-scheduler-sidebar-section-heading"><?php esc_html_e( 'Post types', 'wpcs-content-scheduler' ); ?></label>
								<select id="wpcs-content-scheduler-filter-post-type" class="wpcs-content-scheduler-filter wpcs-content-scheduler-select2" multiple>
									<?php foreach ( $post_types as $post_type ) {
										$post_type_object = get_post_type_object( $post_type ); ?>
										<option value="<?php echo $post_type; ?>" title="<?php esc_html_e( 'ID:', 'wpcs-content-scheduler' ); ?> <?php echo esc_html( $post_type ); ?>"><?php echo esc_html( $post_type_object->label ); ?></option>
									<?php } ?>
								</select>
							</div>

						<?php }

						if ( !empty( $post_statuses ) ) { ?>

							<div id="wpcs-content-scheduler-sidebar-section-post-statuses" class="wpcs-content-scheduler-sidebar-section">
								<label for="wpcs-content-scheduler-filter-post-status" class="wpcs-content-scheduler-sidebar-section-heading"><?php esc_html_e( 'Post statuses', 'wpcs-content-scheduler' ); ?></label>
								<select id="wpcs-content-scheduler-filter-post-status" class="wpcs-content-scheduler-filter wpcs-content-scheduler-select2" multiple>
									<?php foreach ( $post_statuses as $post_status ) {
										$post_status_object = get_post_status_object( $post_status ); ?>
										<option value="<?php echo esc_html( $post_status ); ?>" title="<?php esc_html_e( 'ID:', 'wpcs-content-scheduler' ); ?> <?php echo esc_html( $post_status ); ?>"><?php echo esc_html( $post_status_object->label ); ?></option>
									<?php } ?>
								</select>
							</div>

						<?php }

						if ( !empty( $taxonomies ) ) { ?>

							<div id="wpcs-content-scheduler-sidebar-section-taxonomies" class="wpcs-content-scheduler-sidebar-section">
								<label for="wpcs-content-scheduler-filter-taxonomy" class="wpcs-content-scheduler-sidebar-section-heading"><?php esc_html_e( 'Taxonomies', 'wpcs-content-scheduler' ); ?></label>
								<select id="wpcs-content-scheduler-filter-taxonomy" class="wpcs-content-scheduler-filter wpcs-content-scheduler-select2" multiple>
									<?php foreach ( $taxonomies as $taxonomy ) {
										$taxonomy_object = get_taxonomy( $taxonomy );
										$terms = get_terms(
											array( 
												'hide_empty'	=> false,
												'taxonomy'		=> $taxonomy,
											)
										); ?>
										<optgroup label="<?php echo esc_html( $taxonomy_object->label ); ?>" title="<?php esc_html_e( 'Taxonomy ID:', 'wpcs-content-scheduler' ); ?> <?php echo esc_html( $taxonomy ); ?>">
											<?php foreach ( $terms as $term ) { ?>
												<option value="<?php echo esc_html( $term->term_id ); ?>" title="<?php esc_html_e( 'Term ID:', 'wpcs-content-scheduler' ); ?> <?php echo esc_html( $term->term_id ); ?><?php esc_html_e( ',', 'wpcs-content-scheduler' ); ?> <?php esc_html_e( 'Taxonomy ID:', 'wpcs-content-scheduler' ); ?> <?php echo esc_html( $taxonomy ); ?>"><?php echo esc_html( $term->name ); ?></option>
											<?php } ?>
										</optgroup>
									<?php } ?>
								</select>
							</div>

						<?php }

						if ( !empty( $users ) ) { ?>

							<div id="wpcs-content-scheduler-sidebar-section-users" class="wpcs-content-scheduler-sidebar-section">
								<label for="wpcs-content-scheduler-filter-user" class="wpcs-content-scheduler-sidebar-section-heading"><?php esc_html_e( 'Users', 'wpcs-content-scheduler' ); ?></label>
								<select id="wpcs-content-scheduler-filter-user" class="wpcs-content-scheduler-filter wpcs-content-scheduler-select2" multiple>
									<?php foreach ( $users as $user ) { ?>
										<option value="<?php echo esc_html( $user->ID ); ?>" title="<?php echo esc_html( $user->user_nicename ); ?> <?php esc_html_e( '(', 'wpcs-content-scheduler' ); ?><?php echo esc_html( $user->user_email ); ?><?php esc_html_e( ')', 'wpcs-content-scheduler' ); ?>"><?php echo esc_html( $user->user_nicename ); ?> <?php esc_html_e( '(', 'wpcs-content-scheduler' ); ?><?php echo esc_html( $user->user_email ); ?><?php esc_html_e( ')', 'wpcs-content-scheduler' ); ?></option>
									<?php } ?>
								</select>
							</div>

						<?php }

						if ( !empty( $post_statuses ) ) { ?>

							<div id="wpcs-content-scheduler-sidebar-section-color-key" class="wpcs-content-scheduler-sidebar-section">
								<p class="wpcs-content-scheduler-sidebar-section-heading"><?php esc_html_e( 'Color key', 'wpcs-content-scheduler' ); ?></p>
								<?php foreach ( $post_statuses as $post_status ) {
									$post_status_object = get_post_status_object( $post_status ); ?>
									<a href="#" class="wpcs-content-scheduler-color-key" style="<?php echo ( !empty( $settings['colors'][$post_status] ) ? "background-color: " . esc_html( $settings['colors'][$post_status] ) . ";" : '' ); ?>" title="<?php esc_html_e( 'ID:', 'wpcs-content-scheduler' ); ?> <?php echo esc_html( $post_status ); ?>" data-post-status="<?php echo esc_html( $post_status ); ?>"><small><?php echo esc_html( $post_status_object->label ); ?></small></a>
								<?php } ?>
							</div>

						<?php } ?>

						<div id="wpcs-content-scheduler-sidebar-section-help" class="wpcs-content-scheduler-sidebar-section">
							<p class="wpcs-content-scheduler-sidebar-section-heading"><?php esc_html_e( 'Help', 'wpcs-content-scheduler' ); ?></p>
							<p><small><?php esc_html_e( 'To edit a post click it. Hover over it for post details. For faster navigation use the left/right keys on your keyboard. You can drag posts to a different date. If you wish to drag a post to a different calendar view (e.g. drag an event from January to February) press left/right on your keyboard while dragging the post.', 'wpcs-content-scheduler' ); ?></small></p>
						</div>
					</div>
					<div id="wpcs-content-scheduler-calendar"></div>
				</div>
				<div id="wpcs-content-scheduler-notice"></div>
			</div>

			<script>

				document.addEventListener( 'DOMContentLoaded', function() {

					// jQuery

					$ = jQuery;

					// Popup

					function popup( url, calendar ) {

						popupWidth = <?php echo ( isset( $settings['popup']['width'] ) ? esc_html( $settings['popup']['width'] ) : 960 ); ?>; // Fallbacks here are the same as the initial setting population
						popupHeight = <?php echo ( isset( $settings['popup']['height'] ) ? esc_html( $settings['popup']['height'] ) : 768 ); ?>; // Fallbacks here are the same as the initial setting population
						popupTop = window.top.outerHeight / 2 + window.top.screenY - ( popupHeight / 2);
						popupLeft = window.top.outerWidth / 2 + window.top.screenX - ( popupWidth / 2);
						popupWindow = window.open( url, 'wpcs-content-scheduler-popup', 'width=' + popupWidth + ',height=' + popupHeight + ',top=' + popupTop + ',left=' + popupLeft );
						popupWindow.focus();

						var timer = setInterval( function() {

							if ( popupWindow.closed ) {

								calendar.refetchEvents();
								clearInterval( timer );

							}

						}, 1000 );

					}

					// Calendar configuration

					var calendarElement = document.getElementById( 'wpcs-content-scheduler-calendar' );

					var calendar = new FullCalendar.Calendar( calendarElement, {
						allDaySlot: false,
						editable: true,
						eventDurationEditable: false,
						eventSources: {
							url: '<?php echo get_site_url(); ?>/wpcs-content-scheduler/events/',
						},
						fixedWeekCount: true,
						footerToolbar: {
							right: 'prev,next today'
						},
						headerToolbar: {
							left: 'title',
							center: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
							right: 'prev,next today'
						},
						height: 'auto',
						showNonCurrentDates: true,
						timeZone: 'UTC', // Without this uses local time and can cause times to be 1 hour out when dragging events
						eventClassNames( args ) {

							var classes = [];

							<?php if ( !empty( $post_types ) ) { ?>

								if ( $( '#wpcs-content-scheduler-filter-post-type' ).val().length > 0 ) {

									if ( !$( '#wpcs-content-scheduler-filter-post-type' ).val().includes( args.event.extendedProps.post_type ) ) {

										classes.push( 'wpcs-content-scheduler-event-hide' );

									}

								}

							<?php }	?>

							<?php if ( !empty( $post_statuses ) ) { ?>

								if ( $( '#wpcs-content-scheduler-filter-post-status' ).val().length > 0 ) {

									if ( !$( '#wpcs-content-scheduler-filter-post-status' ).val().includes( args.event.extendedProps.post_status ) ) {

										classes.push( 'wpcs-content-scheduler-event-hide' );

									}

								}

							<?php } ?>

							<?php if ( !empty( $taxonomies ) ) { ?>

								if ( $( '#wpcs-content-scheduler-filter-taxonomy' ).val().length > 0 ) {

									showEvent = false;
									filteredTaxonomies = $( '#wpcs-content-scheduler-filter-taxonomy' ).val();

									for ( var filteredTaxonomiesIteration = 0; filteredTaxonomiesIteration < filteredTaxonomies.length; filteredTaxonomiesIteration++ ) {

										if ( filteredTaxonomies[filteredTaxonomiesIteration] in args.event.extendedProps.terms ) {

											showEvent = true;

										}

									}

									if ( showEvent == false ) {

										classes.push( 'wpcs-content-scheduler-event-hide' );

									}

								}

							<?php } ?>

							<?php if ( !empty( $users ) ) { ?>

								if ( $( '#wpcs-content-scheduler-filter-user' ).val().length > 0 && !$( '#wpcs-content-scheduler-filter-user' ).val().includes( args.event.extendedProps.user ) ) {

									classes.push( 'wpcs-content-scheduler-event-hide' );

								}

							<?php } ?>

							return classes;

						},
						eventClick: function( info ) {

							info.jsEvent.preventDefault();
							popup( info.event.url, info.view.calendar );

						},
						eventDragStart: function( info ) {

							$( info.el ).focus(); // If trying to drag an event using left/right keys after a select2 is focused the left/right keys would be trying to navigate through the select2 rather than the calendar without this

							$( '#wpcs-content-scheduler-notice' ).attr( 'data-type', 'info' ).text( '<?php esc_html_e( 'Use the left/right keys while dragging to drop on dates outside current view', 'wpcs-content-scheduler' ); ?>' ).show();

						},
						eventDragStop: function( info ) {

							$( '#wpcs-content-scheduler-notice' ).hide();

						},
						eventDrop: function( info ) {

							$( '#wpcs-content-scheduler-notice' ).attr( 'data-type', 'info' ).text( '<?php esc_html_e( 'Saving:', 'wpcs-content-scheduler' ); ?> ' + info.event.title ).show();

							var data = {
								'action': 'wpcs_content_scheduler_save_event_ajax',
								'nonce': '<?php echo esc_html( wp_create_nonce( 'wpcs_content_scheduler_save_event_ajax' ) ); ?>',
								'post_id': info.event.id,
								'post_status': info.event.extendedProps.post_status,
								'start': info.event.start.toISOString(),
							};

							saveErrorNotice = "<?php esc_html_e( 'Sorry, there was an error saving. Refresh this page and retry', 'wpcs-content-scheduler' ); ?>";

							$.post('<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>', data, function( response ) {

								if ( '0' == response ) {

									info.revert();
									$( '#wpcs-content-scheduler-notice' ).attr( 'data-type', 'error' ).text( saveErrorNotice ).show();

								} else {

									if ( '1' == response ) {

										calendar.refetchEvents();
										$( '#wpcs-content-scheduler-notice' ).attr( 'data-type', 'success' ).text( '<?php esc_html_e( 'Saved:', 'wpcs-content-scheduler' ); ?> ' + info.event.title ).show();
										$( '#wpcs-content-scheduler-notice' ).delay( 7500 ).hide( 0 );

									} else {

										if ( '2' == response ) {

											info.revert();
											$( '#wpcs-content-scheduler-notice' ).attr( 'data-type', 'error' ).text( info.event.title + ' <?php esc_html_e( 'can not be moved as draft/pending - schedule or publish first', 'wpcs-content-scheduler' ); ?>' ).show();
											$( '#wpcs-content-scheduler-notice' ).delay( 7500 ).hide( 0 );											

										}

									}

								}

							}).fail( function( response ) {

								info.revert();
								$( '#wpcs-content-scheduler-notice' ).attr( 'data-type', 'error' ).text( saveErrorNotice ).show();

							});

						},
						eventMouseEnter: function( info ) {

							titleAttribute = info.event.title + ' <?php esc_html_e( '-', 'wpcs-content-scheduler' ); ?> ' + info.event.extendedProps.post_status_nice_name + ' <?php esc_html_e( '-', 'wpcs-content-scheduler' ); ?> ' + info.event.extendedProps.post_date;

							<?php if ( wpcs_content_scheduler_freemius()->can_use_premium_code__premium_only() && 'yes' == $notes ) { ?>

								if ( info.event.extendedProps.notes ) {

									titleAttribute = titleAttribute + '\n\n' + '<?php esc_html_e( 'Notes:', 'wpcs-content-scheduler' ); ?>' + '\n' + info.event.extendedProps.notes;

								}

							<?php } ?>

							$( info.el ).attr( 'title', titleAttribute );

						},

					});

					// Calendar locale

					try {

						calendar.setOption( 'locale', '<?php echo strtolower( str_replace( '_', '-', get_locale() ) ); ?>' );

					} catch ( e ) {

						// Default locale, try and catch must be used as if the locale is not found it throws an Uncaught RangeError

					}


					// Calendar initial render

					calendar.render();

					// Refetch events on return to focus (e.g. if creating a page in new tab and returning to calendar tab)

					$( window ).focus( function() {

						calendar.refetchEvents();

					});

					// Left/right keyboard controls

					$( 'body' ).keydown( function( e ) {

						if ( e.keyCode == 37 ) { // Left key

							calendar.prev();

						} else if ( e.keyCode == 39 ) { // Right key
							
							calendar.next();

						}

					});

					// Filter changes

					$( '.wpcs-content-scheduler-filter' ).on( 'change', function() {

						calendar.render();

					});

					// New popup

					$( '#wpcs-content-scheduler-add-new' ).on( 'click', function() {

						popup( $( '#wpcs-content-scheduler-add-new-post-type' ).val(), calendar );

					});

				});

			</script>

			<?php
			
		}

		public function json_endpoint_rewrites() {

			add_rewrite_tag( '%wpcs_content_scheduler_events%', '([^&]+)' );
			add_rewrite_rule( 'wpcs-content-scheduler/([^&]+)/?', 'index.php?wpcs_content_scheduler_events=$matches[1]', 'top' );
		 
			if ( 'no' == get_option( 'wpcs_content_scheduler_rewrites_flushed' ) ) {

				flush_rewrite_rules();
				update_option( 'wpcs_content_scheduler_rewrites_flushed', 'yes' );

			}

		}

		public function json_endpoint_data_events() {
 
			global $wp_query;

			if ( current_user_can( apply_filters( 'wpcs_content_scheduler_capability', WPCS_CONTENT_SCHEDULER_CAPABILITY_DEFAULT ) ) ) {

				if ( !$wp_query->get( 'wpcs_content_scheduler_events' ) ) {

					return;

				}

				$settings = WPCS_Content_Scheduler_Settings::get();

				if ( !empty( $settings ) ) {

					$date_format = get_option( 'date_format' );
					$time_format = get_option( 'time_format' );
					$post_types = ( isset( $settings['post_types'] ) ? $settings['post_types'] : array() );
					$post_statuses = ( isset( $settings['post_statuses'] ) ? $settings['post_statuses'] : array() );
					$taxonomies = ( isset( $settings['taxonomies'] ) ? $settings['taxonomies'] : array() );
					$colors = ( isset( $settings['colors'] ) ? $settings['colors'] : array() );

					$posts = array();

					if ( !empty( $post_types ) ) {

						$args = array(
							'fields'		=> 'ids',
							'numberposts'	=> -1,
							'post_type'		=> $post_types,
							'post_status'	=> $post_statuses,
						);

						$posts = get_posts( $args );

					}

					$events = array();

					if ( !empty( $posts ) ) {

						foreach ( $posts as $post_id ) {

							$color = '';
							$notes = get_post_meta( $post_id, '_wpcs_content_scheduler_notes', true );
							$post_date = get_the_date( $date_format . ' ' . $time_format, $post_id ); // This is the formatted post date that will be used when hovering over posts
							$post_type = get_post_type( $post_id );
							$post_type_object = get_post_type_object( $post_type );
							$post_status = get_post_status( $post_id );
							$post_status_object = get_post_status_object( $post_status );
							$start = get_the_date( 'Y-m-d', $post_id ) . 'T' . get_the_date( 'H:i:s', $post_id ); // This is the start/date time in the required Fullcalendar format
							$terms = array();
							$title = ( !empty( get_the_title( $post_id ) ) ? get_the_title( $post_id ) : esc_html__( '(no title)', 'wpcs-content-scheduler' ) ). ' [' . $post_type_object->labels->singular_name . ']'; // (no title) is matched to the same text as posts with no title get on the posts list in dashboard

							if ( !empty( $colors ) ) {

								foreach ( $colors as $color_post_status => $color_post_status_hex ) {

									if ( $post_status == $color_post_status ) {

										$color = $color_post_status_hex;
										break;

									}

								}

							}

							if ( !empty( $taxonomies ) ) {

								foreach ( $taxonomies as $taxonomy ) {

									if ( !is_wp_error( get_terms( array( 'taxonomy' => $taxonomy, 'object_ids' => $post_id ) ) ) ) {

										$post_taxonomy_terms = get_terms(
											array(
												'object_ids'	=> $post_id,
												'taxonomy'		=> $taxonomy
											)
										);

										foreach ( $post_taxonomy_terms as $post_taxonomy_term ) {

											$terms[$post_taxonomy_term->term_id] = $post_taxonomy_term->term_id;

										}

									}

								}

							}

							$events[] = array(
								'allDay'					=> false,
								'color'						=> $color,
								'editable'					=> true,
								'id'						=> $post_id,
								'notes'						=> $notes,
								'post_date'					=> $post_date,
								'post_type'					=> $post_type,
								'post_status'				=> $post_status,
								'post_status_nice_name'		=> $post_status_object->label,
								'start'						=> $start,
								'terms'						=> $terms,
								'title'						=> $title,
								'url'						=> esc_url( get_admin_url() ) . 'post.php?post=' . esc_html( $post_id ) . '&action=edit&wpcs_content_scheduler_popup=1',
								'user'						=> get_post_field( 'post_author', $post_id ),
							);
						}
					}

				} else {

					$events = array();

				}

				wp_send_json( $events );

			}
		 
		}

		public function save_event_ajax() {

			$return = '0';

			if ( isset( $_POST['nonce'] ) ) {

				if ( wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wpcs_content_scheduler_save_event_ajax' ) ) {

					if ( isset( $_POST['post_id'] ) && isset( $_POST['post_status'] ) && isset( $_POST['start'] ) ) {

						if ( !in_array( sanitize_text_field( $_POST['post_status'] ), array( 'draft', 'pending' ) ) ) {

							$post_date = date( 'Y-m-d H:i:s', strtotime( sanitize_text_field( $_POST['start'] ) ) ); // Converts the start date/time from Fullcalendar into the required date format for the post meta

							$result = wp_update_post(
								array(
									'ID'            => sanitize_text_field( $_POST['post_id'] ), // ID of the post to update
									'post_date'     => $post_date,
									'post_date_gmt' => get_gmt_from_date( $post_date )
								)
							);

							if ( !is_wp_error( $result ) ) {

								$return = '1';

							}

						} else {

							$return = '2';

						}

					}

				}

			}

			echo esc_html( $return );

			exit;

		}

	}

}
