<?php

if ( !defined( 'ABSPATH' ) ) {

	exit;

}

if ( !class_exists( 'WPCS_Content_Scheduler_Notes' ) ) {

	class WPCS_Content_Scheduler_Notes {

		public function __construct() {

			add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save' ) );

		}

		public function meta_boxes() {

			$settings = WPCS_Content_Scheduler_Settings::get();

			if ( !empty( $settings ) ) {

				if ( isset( $settings['notes'] ) && isset( $settings['post_types'] ) ) {

					$notes = $settings['notes'];

					if ( 'yes' == $notes ) {

						$post_types = $settings['post_types'];

						if ( !empty( $post_types ) ) {

							add_meta_box(
								'wpcs-content-scheduler-notes',
								__( 'Content Scheduler Notes', 'wpcs-content-scheduler' ),
								array( $this, 'meta_box_notes' ),
								$post_types,
								'side', // Side because if normal in block editor it would get displayed after scrolling through all content of page
								'default'
							);

						}

					}

				}

			}

		}

		public function meta_box_notes() {

			global $post;

			$notes = get_post_meta( $post->ID, '_wpcs_content_scheduler_notes', true );

			wp_nonce_field( 'wpcs_content_scheduler_notes_save', 'wpcs_content_scheduler_notes_save_nonce' );
			echo '<textarea name="wpcs_content_scheduler_notes" placeholder="' . esc_html__( 'Save any notes here.', 'wpcs-content-scheduler' ) . '" style="width: 100%; height: 120px; margin: 10px 0 0 0; padding: 10px; border: 1px solid #c3c4c7;">' . wp_kses_post( $notes ) . '</textarea>'; // Inline CSS used here as not worth enqueing a file just for this (assets/styles.css is enqueued conditionally so can not include within that)

		}

		public function save( $post_id ) {

			if ( isset( $_POST['wpcs_content_scheduler_notes_save_nonce'] ) ) {

				if ( wp_verify_nonce( sanitize_key( $_POST['wpcs_content_scheduler_notes_save_nonce'] ), 'wpcs_content_scheduler_notes_save' ) ) {

					if ( isset( $_POST['wpcs_content_scheduler_notes'] ) ) {

						update_post_meta( $post_id, '_wpcs_content_scheduler_notes', sanitize_textarea_field( $_POST['wpcs_content_scheduler_notes'] ) );

					}

				}

			}

		}

	}

}
