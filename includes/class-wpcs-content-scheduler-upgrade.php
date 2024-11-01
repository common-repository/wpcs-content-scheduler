<?php

if ( !defined( 'ABSPATH' ) ) {

	exit;

}

if ( !class_exists( 'WPCS_Content_Scheduler_Upgrade' ) ) {

	class WPCS_Content_Scheduler_Upgrade {

		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'upgrade' ) );

		}

		public static function upgrade() {

			$version = get_option( 'wpcs_content_scheduler_version' );

			if ( WPCS_CONTENT_SCHEDULER_VERSION !== $version ) {

				global $wpdb;

				if ( version_compare( $version, '1.0.0', '<' ) ) {

					// 1.0.0 - Populate options

					if ( get_option( 'wpcs_content_scheduler_settings' ) === false ) {

						$settings = array(
							'colors' => array(
								'draft'		=> '#d63638',
								'future'	=> '#f78c00',
								'pending'	=> '#8224e3',
								'private'	=> '#333333',
								'publish'	=> '#00a32a',
							),
							'popup' => array(
								'width'		=> '960',
								'height'	=> '768',
							),
							'post_statuses' => array(
								'0'	=> 'draft',
								'1'	=> 'future',
								'2'	=> 'pending',
								'3'	=> 'private',
								'4'	=> 'publish',
							),
							'post_types' => array(
								'0'	=> 'post',
								'1'	=> 'page',
							),
							'taxonomies' => array(
								'0'	=> 'category',
							),
							'user_roles' => array(
								'0'	=> 'administrator',
								'1'	=> 'author',
								'2'	=> 'contributor',
								'3'	=> 'editor',
								'4'	=> 'subscriber',
							),
						);

						update_option( 'wpcs_content_scheduler_settings', $settings );

					}

				}

				if ( version_compare( $version, '1.1.0', '<' ) ) {

					// 1.1.0 - Add notes setting

					$settings = get_option( 'wpcs_content_scheduler_settings' );
					$settings['notes'] = 'yes';
					ksort( $settings );
					update_option( 'wpcs_content_scheduler_settings', $settings );

				}

				update_option( 'wpcs_content_scheduler_version', WPCS_CONTENT_SCHEDULER_VERSION );

			}

		}

	}

}
