<?php
/**
 * WP_Slideshow Admin.
 *
 * @class    WPSS_Admin
 * @version  1.0.0
 * @package  WP_Slideshow/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPSS_Admin Class
 */
class WPSS_Admin {

	/**
	 * WPSS_Admin Constructor
	 */
	public function __construct() {
		add_action('admin_menu', array( $this, 'wp_slideshow_admin_menu' ) );
		/**
		 * Admin Scripting.
		 */
		add_action('admin_enqueue_scripts', array( $this, 'wp_slideshow_enqueue_admin_scripts' ) );
		/**
		 * Global Settings.
		 */
		add_action('admin_init', array($this, 'wp_slideshow_register_settings'));
		add_action('admin_notices', array($this, 'wp_slideshow_admin_notice'));
	}

	/**
	 * Main Menu for this Plugin named WP Slideshow.
	 */
	public function wp_slideshow_admin_menu() {
		add_menu_page(
			__( 'WP Slideshow Settings', 'wp-slideshow' ),
			__( 'WP Slideshow', 'wp-slideshow' ),
			'manage_options',
			'wp-slideshow-settings',
			array( $this, 'wp_slideshow_settings_page' ),
			'dashicons-images-alt2',
			100
		);
	}

	/**
	 * Enqueue admin scripts
	 */
	public function wp_slideshow_enqueue_admin_scripts() {
		if (is_admin()) {
			wp_enqueue_media();
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('wp-slideshow-admin-scripts', WPSS()->plugin_url() . '/assets/admin/admin-scripts.js', array('jquery'), '1.0', true);
		}
	}

	/**
	 * Render Global Settings Fields.
	 */
	public function wp_slideshow_settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'WP Slideshow Settings', 'wp-slideshow' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields('wp_slideshow_options_group');
				do_settings_sections('wp-slideshow-settings');
				?>
				<?php submit_button('Save Settings'); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register new global options group.
	 */
	public function wp_slideshow_register_settings() {
		register_setting('wp_slideshow_options_group', 'wp_slideshow_transition_effect');
		register_setting('wp_slideshow_options_group', 'wp_slideshow_autoplay_speed');
		register_setting('wp_slideshow_options_group', 'wp_slideshow_global_images');

		add_settings_section(
			'wp_slideshow_main_section',
			__( 'Global Slideshow Settings', 'wp-slideshow' ),
			array($this, 'wp_slideshow_section_text'),
			'wp-slideshow-settings'
		);

		add_settings_field(
			'wp_slideshow_transition_effect',
			__( 'Transition Effect', 'wp-slideshow' ),
			array($this, 'wp_slideshow_transition_effect_input'),
			'wp-slideshow-settings',
			'wp_slideshow_main_section'
		);

		add_settings_field(
			'wp_slideshow_autoplay_speed',
			__( 'Autoplay Speed (ms)', 'wp-slideshow' ),
			array($this, 'wp_slideshow_autoplay_speed_input'),
			'wp-slideshow-settings',
			'wp_slideshow_main_section'
		);

		add_settings_field(
			'wp_slideshow_global_images',
			__( 'Global Slideshow Images', 'wp-slideshow' ),
			array($this, 'wp_slideshow_global_images_input'),
			'wp-slideshow-settings',
			'wp_slideshow_main_section'
		);
	}

	/**
	 * Return Global options Section Text.
	 */
    public function wp_slideshow_section_text() {
		echo esc_html__( '<p>Configure global settings for the slideshow.</p>', 'wp-slideshow' );
    }

	/**
	 * HTML Wrapper for Transition effect input in Global Settings.
	 */
	public function wp_slideshow_transition_effect_input() {
		$transition_effect = get_option('wp_slideshow_transition_effect', 'fade');
		?>
		<select name="wp_slideshow_transition_effect">
			<option value="fade" <?php selected($transition_effect, 'fade'); ?>><?php esc_html_e( 'Fade', 'wp-slideshow' ); ?></option>
			<option value="slide" <?php selected($transition_effect, 'slide'); ?>><?php esc_html_e( 'Slide', 'wp-slideshow' ); ?></option>
		</select>
		<?php
	}

	/**
	 * HTML Wrapper for Autoplay speed input in Global Settings.
	 */
	public function wp_slideshow_autoplay_speed_input() {
		$autoplay_speed = get_option('wp_slideshow_autoplay_speed', 3000);
		?>
		<input type="number" name="wp_slideshow_autoplay_speed" value="<?php echo esc_attr($autoplay_speed); ?>">
		<?php
	}

	/**
	 * HTML Wrapper for Global images input in Global Settings.
	 */
	public function wp_slideshow_global_images_input() {
		$global_images = get_option('wp_slideshow_global_images', '');
		$global_images = $global_images ? explode(',', $global_images) : array();
		?>
		<input type="hidden" id="wp_slideshow_global_images" name="wp_slideshow_global_images" value="<?php echo esc_attr(implode(',', $global_images)); ?>">
		<div id="wp_slideshow_global_images_container">
			<?php foreach ($global_images as $image_id) : ?>
				<?php $image_url = wp_get_attachment_url($image_id); ?>
				<div class="wp_slideshow_image" data-image-id="<?php echo esc_attr($image_id); ?>">
					<img src="<?php echo esc_url($image_url); ?>" width="100">
					<button class="remove_image"><?php esc_html_e( 'Remove', 'wp-slideshow' ); ?></button>
				</div>
			<?php endforeach; ?>
		</div>
		<button id="upload_global_images_button" class="button"><?php esc_html_e( 'Add Images', 'wp-slideshow' ); ?></button>
		<?php
	}

	 /**
	  * Display Notice when Global Settings saved successfully.
	  */
	 public function wp_slideshow_admin_notice() {
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Settings saved successfully!', 'wp-slideshow' ); ?></p>
            </div>
            <?php
        }
    }

}

return new WPSS_Admin();
