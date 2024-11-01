<?php

if ( !defined( 'ABSPATH' ) ) {

	exit;

}

if ( !class_exists( 'WPCS_Content_Scheduler_Activation' ) ) {

	class WPCS_Content_Scheduler_Activation {

		public function __construct() {

			register_activation_hook( plugin_dir_path( __DIR__ ) . 'wpcs-content-scheduler.php', array( $this, 'install' ) );

		}

		public function install() {

			WPCS_Content_Scheduler_Upgrade::upgrade();
			update_option( 'wpcs_content_scheduler_rewrites_flushed', 'no' );

		}

	}

}
