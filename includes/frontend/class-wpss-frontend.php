<?php
/**
 * WP_Slideshow Frontend.
 *
 * @class    WPSS_Frontend
 * @version  1.0.0
 * @package  WP_Slideshow/Frontend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPSS_Frontend Class
 */
class WPSS_Frontend {

	/**
	 * WPSS_Frontend Constructor
	 */
	public function __construct() {
		add_shortcode('wp_slideshow', array( $this, 'wp_slideshow_shortcode' ));
		add_action('wp_enqueue_scripts', array( $this, 'wp_slideshow_enqueue_scripts' ) );
	}

	/**
	 * Enqueue Frontend Scripts and Styles.
	 */
	public function wp_slideshow_enqueue_scripts() {
		if (!is_admin()) {
			/**
			 * Enqueue Library files.
			 */
			wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
			wp_enqueue_style('slick-slider-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
			wp_enqueue_script('slick-slider-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);

			/**
			 * Enqueue Plugin scripts and styles files.
			 */
			wp_enqueue_style('wp-slideshow-style', WPSS()->plugin_url() . '/assets/frontend/css/frontend.css');
			wp_enqueue_script('wp-slideshow-script', WPSS()->plugin_url() . '/assets/frontend/js/frontend-scripts.js', array('jquery', 'slick-slider-js'), '1.0', true);
		}
	}

	/**
	 * Render Shortcode.
	 *
	 * @param array $atts Attributes.
	 */
	public function wp_slideshow_shortcode($atts) {
		$atts = shortcode_atts(array(
			'id' => get_the_ID(),
		), $atts, 'wp_slideshow');

		$saved_images = get_post_meta($atts['id'], 'wp_slideshow_images', true);
		$saved_images = $saved_images ? explode(',', $saved_images) : array();

		if (empty($saved_images)) {
			$global_images = get_option('wp_slideshow_global_images', '');
			$saved_images = $global_images ? explode(',', $global_images) : array();
		}

		$transition_effect = get_option('wp_slideshow_transition_effect', 'fade');
		$autoplay_speed = get_option('wp_slideshow_autoplay_speed', 3000);

		ob_start();

		if (!empty($saved_images)) {
			?>
			<div class="wp-slideshow-container" data-transition="<?php echo esc_attr($transition_effect); ?>" data-autoplay="<?php echo esc_attr($autoplay_speed); ?>">
				<?php foreach ($saved_images as $image_id) : ?>
					<?php $image_url = wp_get_attachment_url($image_id); ?>
					<div class="slide">
						<img src="<?php echo esc_url($image_url); ?>" alt="Slideshow Image">
					</div>
				<?php endforeach; ?>
			</div>
			<?php
		} else {
			echo esc_html__( '<p>No images found for the slideshow.</p>', 'wp-slideshow' );
		}

		return ob_get_clean();
	}
}

return new WPSS_Frontend();
