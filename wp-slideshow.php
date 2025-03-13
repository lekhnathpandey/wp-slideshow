<?php
/**
 * Plugin Name: WP Slideshow
 * Description: A plugin to create a slideshow using a shortcode.
 * Version: 1.0.0
 * Author: Lekhnath Pandey
 * Text Domain: wp-slideshow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Slideshow' ) ) :

	/**
	 * Main WP_Slideshow Class.
	 *
	 * @class   WP_Slideshow
	 * @version 1.0.0
	 */
	final class WP_Slideshow {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $_instance = null;

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function instance() {
			// If the single instance hasn't been set, set it now.
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-slideshow' ), '1.0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-slideshow' ), '1.0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * WP_Slideshow Constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			do_action( 'wp_slideshow_loaded' );
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'init', array( $this, 'init' ), 0 );
			add_filter( 'plugin_action_links_' . WPSS_PLUGIN_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
		}

		/**
		 * Define WPSS Constants.
		 */
		private function define_constants() {
			$this->define( 'WPSS_DS', DIRECTORY_SEPARATOR );
			$this->define( 'WPSS_PLUGIN_FILE', __FILE__ );
			$this->define( 'WPSS_ABSPATH', __DIR__ . WPSS_DS );
			$this->define( 'WPSS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			$this->define( 'WPSS_VERSION', $this->version );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name Name.
		 * @param string|bool $value Value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 *
		 * @param  string $type admin, ajax, cron or frontend.
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Includes Files.
		 */
		private function includes() {

			if ( $this->is_request( 'admin' ) ) {
				include_once WPSS_ABSPATH . 'includes/admin/class-wpss-admin.php';
			}

			if ( $this->is_request( 'frontend' ) ) {
				include_once WPSS_ABSPATH . 'includes/frontend/class-wpss-frontend.php';
			}
		}

		/**
		 * Init WP_Slideshow when WordPress Initialises.
		 */
		public function init() {
			// Before init action.
			do_action( 'before_wp_slideshow_init' );

			// Set up localisation.
			$this->load_plugin_textdomain();

			// Init action.
			do_action( 'wp_slideshow_init' );
		}

		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 *
		 * Locales found in:
		 *      - WP_LANG_DIR/wp-slideshow/wp-slideshow-LOCALE.mo
		 *      - WP_LANG_DIR/plugins/wp-slideshow-LOCALE.mo
		 */
		public function load_plugin_textdomain() {
			$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
			$locale = apply_filters( 'plugin_locale', $locale, 'wp-slideshow' );

			unload_textdomain( 'wp-slideshow', true );
			load_textdomain( 'wp-slideshow', WP_LANG_DIR . '/wp-slideshow/wp-slideshow-' . $locale . '.mo' );
			load_plugin_textdomain( 'wp-slideshow', false, plugin_basename( __DIR__ ) . '/languages' );
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Display action links in the Plugins list table.
		 *
		 * @param  array $actions Plugin Action links.
		 * @return array
		 */
		public static function plugin_action_links( $actions ) {
			$new_actions = array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=wp-slideshow-settings' ) . '" aria-label="' . esc_attr__( 'View WP Slideshow settings', 'wp-slideshow' ) . '">' . esc_html__( 'Settings', 'wp-slideshow' ) . '</a>',
			);

			return array_merge( $new_actions, $actions );
		}
	}

endif;

/**
 * Check to see if WPSS already defined and resolve conflicts.
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'WPSS' ) ) {

	/**
	 * Main instance of WP_Slideshow.
	 *
	 * Returns the main instance of WPSS to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return WP_Slideshow
	 */
	function WPSS() {
		return WP_Slideshow::instance();
	}
}
// Global for backwards compatibility.
$GLOBALS['wp-slideshow'] = WPSS();
