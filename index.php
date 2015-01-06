<?php
/*
Plugin Name: WP Rizzo Menu
Plugin Group: Lonely Planet
Plugin URI: http://lonelyplanet.com/
Author: Eric King
Author URI: http://webdeveric.com/
Description: This plugin parses the body header from WP Rizzo and looks for menu items.
Version: 0.1.3
*/

defined('ABSPATH') || exit;

if (is_admin()) {

    if (version_compare(PHP_VERSION, '5.3.0', '<')) {

        function wp_rizzo_menu_requirements_not_met()
        {
            echo '<div class="error"><p>PHP 5.3+ is required for WP Rizzo Menu. You have PHP ', PHP_VERSION, ' installed. This plugin has been deactivated.</p></div>';
            deactivate_plugins(plugin_basename(__FILE__));
            unset($_GET['activate']);
        }
        add_action( 'admin_notices', 'wp_rizzo_menu_requirements_not_met' );

        return;
    }

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    if ( ! is_plugin_active('wp-rizzo/index.php')) {

        function wp_rizzo_menu_dependency_not_met()
        {
            echo '<div class="error"><p>The WP Rizzo plugin is required for this plugin to work. Please activate it first. This plugin has been deactivated.</p></div>';
            deactivate_plugins(plugin_basename(__FILE__));
            unset($_GET['activate']);
        }
        add_action( 'admin_notices', 'wp_rizzo_menu_dependency_not_met' );

        return;
    }

}

define('WP_RIZZO_MENU_FILE', __FILE__);

include dirname(__FILE__) . '/wp-rizzo-menu.php';
