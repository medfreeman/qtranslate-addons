<?php
/**
 * qTranslate_addons
 *
 * @package   QTranslate_Addons_Admin
 * @author    Mehdi Lahlou <mehdi.lahlou@free.fr>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins
 * @copyright 2014 Mehdi Lahlou
 */

/**
 * Qtranslate_Addons_Admin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package Qtranslate_addons_Admin
 * @author  Mehdi Lahlou <mehdi.lahlou@free.fr>
 */
class QTranslate_Addons_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = Qtranslate_addons::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'admin_init', array( $this, 'qtranslate_check_activation' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/**
	 *
	 * Checks if qtranslate is activated. Add error message action hook if not.
	 *
	 */
	public function qtranslate_check_activation() {
		if ( ! is_plugin_active( 'qtranslate/qtranslate.php' ) ) {
			add_action( 'admin_notices', array( $this, 'qtranslate_disabled_admin_notice' ) );
		}
	}

	/**
	 *
	 * Shows an error message if qtranslate is not activated.
	 *
	 */
	public function qtranslate_disabled_admin_notice() {
		echo '<div id="message" class="error"><p><strong>' . __( 'You need to install and enable qTranslate for this plugin to function.', $this->plugin_slug ) . '</strong></p></div>';
	}

}
