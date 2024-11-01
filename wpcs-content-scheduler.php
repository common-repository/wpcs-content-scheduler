<?php

/**
 * Plugin Name: Content Scheduler
 * Plugin URI: https://99w.co.uk
 * Description: Manage all your content in an easy to use drag and drop editorial calendar.
 * Version: 1.3.0
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Author: 99w
 * Author URI: https://profiles.wordpress.org/ninetyninew/
 * Text Domain: wpcs-content-scheduler
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wpcs_content_scheduler_freemius' ) ) {
    wpcs_content_scheduler_freemius()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'wpcs_content_scheduler_freemius' ) ) {
        function wpcs_content_scheduler_freemius()
        {
            global  $wpcs_content_scheduler_freemius ;
            
            if ( !isset( $wpcs_content_scheduler_freemius ) ) {
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wpcs_content_scheduler_freemius = fs_dynamic_init( array(
                    'id'             => '9308',
                    'slug'           => 'wpcs-content-scheduler',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_7459122f248c5e60710793997043c',
                    'is_premium'     => false,
                    'premium_suffix' => 'Premium',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'       => 'wpcs-content-scheduler',
                    'first-path' => 'admin.php?page=wpcs-content-scheduler',
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $wpcs_content_scheduler_freemius;
        }
        
        wpcs_content_scheduler_freemius();
        do_action( 'wpcs_content_scheduler_freemius_loaded' );
    }
    
    
    if ( !class_exists( 'WPCS_Content_Scheduler' ) ) {
        define( 'WPCS_CONTENT_SCHEDULER_VERSION', '1.3.0' );
        define( 'WPCS_CONTENT_SCHEDULER_CAPABILITY_DEFAULT', 'edit_posts' );
        load_plugin_textdomain( 'wpcs-content-scheduler', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        class WPCS_Content_Scheduler
        {
            public function __construct()
            {
                require_once __DIR__ . '/includes/class-wpcs-content-scheduler-activation.php';
                require_once __DIR__ . '/includes/class-wpcs-content-scheduler-dashboard.php';
                require_once __DIR__ . '/includes/class-wpcs-content-scheduler-enqueues.php';
                require_once __DIR__ . '/includes/class-wpcs-content-scheduler-notes.php';
                require_once __DIR__ . '/includes/class-wpcs-content-scheduler-settings.php';
                require_once __DIR__ . '/includes/class-wpcs-content-scheduler-translation.php';
                require_once __DIR__ . '/includes/class-wpcs-content-scheduler-upgrade.php';
                new WPCS_Content_Scheduler_Activation();
                new WPCS_Content_Scheduler_Dashboard();
                new WPCS_Content_Scheduler_Enqueues();
                new WPCS_Content_Scheduler_Notes();
                new WPCS_Content_Scheduler_Settings();
                new WPCS_Content_Scheduler_Translation();
                new WPCS_Content_Scheduler_Upgrade();
            }
        
        }
        new WPCS_Content_Scheduler();
    }

}
