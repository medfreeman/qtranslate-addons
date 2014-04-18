<?php
/**
 * qTranslate_addons
 *
 * @package   QTranslate_Addons
 * @author    Mehdi Lahlou <mehdi.lahlou@free.fr>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins
 * @copyright 2014 Mehdi Lahlou
 */

/**
 * Qtranslate_Addons class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-qtranslate-addons-admin.php`
 *
 * @package Qtranslate_Addons
 * @author  Mehdi Lahlou <mehdi.lahlou@free.fr>
 */
class QTranslate_Addons {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'qtranslate-addons';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_filter( 'admin_url', array( $this, 'qtranslate_admin_ajax_url' ), 10, 2 );
		
		if ( is_plugin_active( 'events-manager/events-manager.php' ) ) {
			add_action( 'em_event', array( $this, 'qtranslate_em_event' ), 0, 3 );
			add_action( 'em_location', array( $this, 'qtranslate_em_location' ), 0, 3 );
		}
		
		/*add_filter( 'qtranslate_get_term_slug', array( $this, 'qtranslate_get_term_slug' ), 10, 3 );
		add_filter( 'get_terms', array( $this, 'qtranslate_get_term_slug' ), 10, 3 );*/
		
		add_filter( 'body_class', array( $this, 'qtranslate_language_body_class_names' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Adds language parameter to ajax requests.
	 *
	 * @since    1.0.0
	 */
	public function qtranslate_admin_ajax_url( $url, $path ) {
		if ( ! is_admin() && $path == 'admin-ajax.php' && function_exists( 'qtrans_getLanguage' ) ) {
			$url .= '?lang=' . qtrans_getLanguage();
		}
		return $url;
	}
	
	/**
	 * Allows translation of events fields.
	 *
	 * @since    1.0.0
	 */
	public function qtranslate_em_event( $target, $arg1 = null, $arg2 = null, $arg3 = null ) {
		$target->event_name   = $this->qtranslate_string( $target->event_name );
		$target->event_owner  = $this->qtranslate_string( $target->event_owner );
		$target->post_content = $this->qtranslate_string( $target->post_content );
		$target->post_excerpt = $this->qtranslate_string( $target->post_excerpt );
	}
	
	/**
	 * Allows translation of event locations fields.
	 *
	 * @since    1.0.0
	 */
	public function qtranslate_em_location( $target, $arg1 = null, $arg2 = null, $arg3 = null ) {
		$target->location_name = $this->qtranslate_string( $target->location_name );
		$target->post_content  = $this->qtranslate_string( $target->post_content );
		$target->post_excerpt  = $this->qtranslate_string( $target->post_excerpt );
	}
	
	/**
	 * Allows translation of taxonomy term slug in get_term_by (the corresponding filter has to be passed as argument to get_term_by function) and get_terms (automatically added with get_terms filter)
	 *
	 * @since    1.0.0
	 */
	public function qtranslate_get_term_slug( $term, $taxonomy, $args ) {
		/*global $wpdb;
		$term = $wpdb->get_row( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = %s AND t.slug = %s LIMIT 1", $taxonomy[0], $term->slug ) );
		print_r( $term );*/
		return $term;
	}
	
	/**
	 * Allows currentlang-XX class to body.
	 *
	 * @since    1.0.0
	 */
	public function qtranslate_language_body_class_names( $classes ) {
		if ( function_exists( 'qtrans_getLanguage' ) ) {
			$classes[] = 'currentlang-' . qtrans_getLanguage();
		}
		return $classes;
	}
	
	
	/**
	 * Helper function to translate strings supported by qtranslate.
	 *
	 * @since    1.0.0
	 */
	private function qtranslate_string( $raw_string ) {
		if ( function_exists( 'qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage' ) ) {
			$output = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage( $raw_string );
		} else {
			$output = __( $raw_string );
		}
		return $output;
	}

}
