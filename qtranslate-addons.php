<?php
/**
 * qTranslate addons
 *
 * Add features and fixes to qtranslate ( Ajax language support / Events manager fields translation support / Fix requested url in path mode, ... )
 *
 * @package   qTranslate_addons
 * @author    Mehdi Lahlou <mehdi.lahlou@free.fr>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins
 * @copyright 2014 Mehdi Lahlou
 *
 * @wordpress-plugin
 * Plugin Name:       qTranslate addons
 * Plugin URI:        http://wordpress.org/plugins
 * Description:       Add features and fixes to qtranslate (Ajax language support / Events manager fields translation support / Body class support ...)
 * Version:           1.0.0
 * Author:            Mehdi Lahlou
 * Author URI:        
 * Text Domain:       qtranslate-addons
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-qtranslate-addons.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'qTranslate_addons', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'qTranslate_addons', 'deactivate' ) );

if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
	if ( is_plugin_active( 'qtranslate/qtranslate.php' ) ) {
		add_action( 'plugins_loaded', array( 'qTranslate_addons', 'get_instance' ) );
	} 
}

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'admin/class-qtranslate-addons-admin.php' );
		add_action( 'plugins_loaded', array( 'qTranslate_addons_Admin', 'get_instance' ) );
}
