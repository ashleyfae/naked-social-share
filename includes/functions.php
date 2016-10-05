<?php
/**
 * Functions
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load Front-End Aassets
 *
 * @since 1.0.0
 * @return void
 */
function nss_enqueue_assets() {
	global $nss_options;

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Load Font Awesome if it's enabled.
	if ( isset( $nss_options['load_fa'] ) && $nss_options['load_fa'] == true ) {
		wp_register_style( 'font-awesome', NSS_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '4.3.0' );
		wp_enqueue_style( 'font-awesome' );
	}

	// Load the default styles if they're enabled.
	if ( isset( $nss_options['load_styles'] ) && $nss_options['load_styles'] == true ) {
		wp_register_style( 'nss-frontend', NSS_PLUGIN_URL . 'assets/css/naked-social-share.css', array(), NSS_VERSION );
		wp_enqueue_style( 'nss-frontend' );
	}

	wp_register_script( 'nss-frontend', NSS_PLUGIN_URL . 'assets/js/naked-social-share' . $suffix . '.js', array( 'jquery' ), NSS_VERSION, true );
	wp_enqueue_script( 'nss-frontend' );

	$settings = array(
		'ajaxurl'    => admin_url( 'admin-ajax.php' ),
		'disable_js' => ( array_key_exists( 'disable_js', $nss_options ) && $nss_options['disable_js'] ) ? true : false,
		'nonce'      => wp_create_nonce( 'nss_update_share_numbers' )
	);

	wp_localize_script( 'nss-frontend', 'NSS', $settings );
}

add_action( 'wp_enqueue_scripts', 'nss_enqueue_assets' );

/**
 * The main function used for displaying the share markup.
 * This can be placed in your theme template file.
 *
 * @since 1.0.0
 * @return void
 */
function naked_social_share_buttons() {
	$share_obj = new Naked_Social_Share_Buttons();
	$share_obj->display_share_markup();
}

/**
 * Filters the_content
 *
 * Adds the social share buttons below blog posts if we've opted to display them automatically.
 *
 * @param string $content Unfiltered post content
 *
 * @access public
 * @since  1.0.0
 * @return string Content with buttons after it
 */
function nss_auto_add_buttons( $content ) {
	$auto_add_to = nss_get_option( 'auto_add' );

	// We do not want to automatically add buttons -- bail.
	if ( ! $auto_add_to || ! is_array( $auto_add_to ) ) {
		return $content;
	}

	// Proceed with post type checks.
	global $post;
	$post_type = get_post_type( $post );

	// This isn't a post or a page -- bail.
	if ( $post_type != 'page' && $post_type != 'post' ) {
		return $content;
	}

	// This is a page and we haven't specified to add pages -- bail.
	if ( $post_type == 'page' && ! array_key_exists( 'pages', $auto_add_to ) ) {
		return $content;
	}

	// This is a post in the archive and we haven't specified to
	// add the buttons there -- bail.
	if ( ! is_single() && $post_type == 'post' && ! array_key_exists( 'blog_archive', $auto_add_to ) ) {
		return $content;
	}

	// This is a single post page and we haven't specified to
	// add the buttons there -- bail.
	if ( is_single() && $post_type == 'post' && ! array_key_exists( 'blog_single', $auto_add_to ) ) {
		return $content;
	}

	// Add the social share buttons after the post content.
	ob_start();
	naked_social_share_buttons();

	return $content . ob_get_clean();
}

add_filter( 'the_content', 'nss_auto_add_buttons' );

/**
 * Button Shortcode
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Shortcode content.
 *
 * @since 1.3.0
 * @return string
 */
function nss_buttons_shortcode( $atts, $content = '' ) {

	// @todo do something with attributes

	ob_start();
	naked_social_share_buttons();

	return apply_filters( 'naked-social-share/shortcode/output', ob_get_clean(), $atts, $content );

}

add_shortcode( 'naked-social-share', 'nss_buttons_shortcode' );

/**
 * Ajax CB: Update Share Numbers
 *
 * @since 1.3.0
 * @return void
 */
function nss_update_share_numbers() {
	check_ajax_referer( 'nss_update_share_numbers', 'nonce' );

	$post_id = $_POST['post_id'];

	if ( ! $post_id || ! is_numeric( $post_id ) ) {
		wp_send_json_error();
	}

	$buttons     = new Naked_Social_Share_Buttons( $post_id );
	$new_numbers = $buttons->update_share_numbers();

	wp_send_json_success( $new_numbers );

	exit;
}

add_action( 'wp_ajax_nss_update_share_numbers', 'nss_update_share_numbers' );
add_action( 'wp_ajax_nopriv_nss_update_share_numbers', 'nss_update_share_numbers' );