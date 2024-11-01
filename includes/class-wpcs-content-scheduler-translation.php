<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'WPCS_Content_Scheduler_Translation' ) ) {

	class WPCS_Content_Scheduler_Translation {

		public function __construct() {

			add_action( 'init', array( $this, 'textdomain' ) );

		}

		public function textdomain() {

			load_plugin_textdomain( 'wpcs-content-scheduler', false, dirname( plugin_basename( __DIR__ ) ) . '/languages' );

		}

	}

}
