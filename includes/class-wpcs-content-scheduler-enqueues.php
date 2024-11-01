<?php

if ( !defined( 'ABSPATH' ) ) {

	exit;

}

if ( !class_exists( 'WPCS_Content_Scheduler_Enqueues' ) ) {

	class WPCS_Content_Scheduler_Enqueues {

		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'register' ) );

		}

		public function register() {

			global $pagenow;

			// jQuery

			wp_enqueue_script( 'jquery' );

			if ( 'admin.php' == $pagenow ) {

				if ( isset( $_GET['page'] ) ) {

					if ( 'wpcs-content-scheduler' == sanitize_text_field( $_GET['page'] ) || 'wpcs-content-scheduler-settings' == sanitize_text_field( $_GET['page'] ) ) {

						// Color picker

						wp_enqueue_style( 'wp-color-picker' );

						// Content scheduler

						wp_enqueue_style(
							'wpcs-content-scheduler-admin',
							plugins_url( 'assets/css/admin.min.css', __DIR__ ),
							array(),
							WPCS_CONTENT_SCHEDULER_VERSION,
							'all'
						);

						wp_enqueue_script(
							'wpcs-content-scheduler-admin',
							plugins_url( 'assets/js/admin.min.js', __DIR__ ),
							array(
								'jquery',
								'wp-i18n',
								'wp-color-picker',
							),
							WPCS_CONTENT_SCHEDULER_VERSION
						);

						// Select2

						wp_enqueue_script(
							'wpcs-content-scheduler-select2',
							plugins_url( 'libraries/select2/dist/js/select2.min.js', __DIR__ ),
							array(
								'jquery',
							),
							WPCS_CONTENT_SCHEDULER_VERSION
						);

						wp_enqueue_style(
							'wpcs-content-scheduler-select2',
							plugins_url( 'libraries/select2/dist/css/select2.min.css', __DIR__ ),
							array(),
							WPCS_CONTENT_SCHEDULER_VERSION,
							'all'
						);

					}

					if ( 'wpcs-content-scheduler' == sanitize_text_field( $_GET['page'] ) ) {

						// Full calendar

						wp_enqueue_style(
							'wpcs-content-scheduler-fullcalendar',
							plugins_url( 'libraries/fullcalendar/lib/main.min.css', __DIR__ ),
							array(),
							WPCS_CONTENT_SCHEDULER_VERSION,
							'all'
						);

						wp_enqueue_script(
							'wpcs-content-scheduler-fullcalendar',
							plugins_url( 'libraries/fullcalendar/lib/main.min.js', __DIR__ ),
							array(),
							WPCS_CONTENT_SCHEDULER_VERSION
						);

						wp_enqueue_script(
							'wpcs-content-scheduler-fullcalendar-locales',
							plugins_url( 'libraries/fullcalendar/lib/locales-all.min.js', __DIR__ ),
							array(),
							WPCS_CONTENT_SCHEDULER_VERSION
						);

					}

				}

			} elseif ( 'post-new.php' == $pagenow || 'post.php' == $pagenow ) {

				if ( isset( $_GET['wpcs_content_scheduler_popup'] ) ) {

					if ( sanitize_text_field( $_GET['wpcs_content_scheduler_popup'] ) == '1' ) {

						// Popup

						wp_enqueue_style(
							'wpcs-content-scheduler-admin-popup',
							plugins_url( 'assets/css/admin-popup.min.css', __DIR__ ),
							array(),
							WPCS_CONTENT_SCHEDULER_VERSION,
							'all'
						);

						wp_enqueue_script(
							'wpcs-content-scheduler-admin-popup',
							plugins_url( 'assets/js/admin-popup.min.js', __DIR__ ),
							array(
								'jquery',
								'wp-i18n',
							),
							WPCS_CONTENT_SCHEDULER_VERSION
						);

					}

				}

			}

		}

	}

}
