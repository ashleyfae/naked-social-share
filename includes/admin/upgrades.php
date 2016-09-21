<?php
/**
 * Plugin Upgrades
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
 * Check for Upgrades
 *
 * If the database version doesn't match the plugin version, we may need to
 * perform upgrades. Then update the version number.
 *
 * @uses  nss_do_upgrade()
 *
 * @since 1.3.0
 * @return void
 */
function nss_check_upgrades() {
	$version_number = get_option( 'naked_social_share_version' );
	if ( ! $version_number ) {
		$version_number = 0;
	}

	// The versions match - bail.
	if ( $version_number == NSS_VERSION ) {
		return;
	}

	nss_do_upgrade( $version_number );

	update_option( 'naked_social_share_version', NSS_VERSION );
}

add_action( 'admin_init', 'nss_check_upgrades' );

/**
 * Perform Upgrade Routines
 *
 * @param string $db_version
 *
 * @since 1.3.0
 * @return void
 */
function nss_do_upgrade( $db_version ) {

	/*
	 * 1.2.0 - Add new social sites.
	 */
	if ( version_compare( $db_version, '1.2.0', '<' ) ) {
		$old_settings = get_option( 'naked_ss__settings', array() );
		$social_sites = ( array_key_exists( 'social_sites', $old_settings ) ) ? $old_settings['social_sites'] : array();

		// Add LinkedIn.
		if ( ! array_key_exists( 'linkedin', $social_sites['enabled'] ) && ! array_key_exists( 'linkedin', $social_sites['disabled'] ) ) {
			$social_sites['disabled']['linkedin'] = array(
				'name' => __( 'LinkedIn', 'naked-social-share' )
			);

			$old_settings['social_sites'] = $social_sites;

			update_option( 'naked_ss__settings', $old_settings );
		}
	}

	/*
	 * 1.3.0 - Switch formatting of enabled sites array and move to new option.
	 * @todo Delete old settings.
	 */
	if ( version_compare( $db_version, '1.3.0', '<' ) ) {
		$old_settings = get_option( 'naked_ss__settings', array() );
		$social_sites = ( array_key_exists( 'social_sites', $old_settings ) ) ? $old_settings['social_sites']['enabled'] : array();

		if ( array_key_exists( 'placebo', $social_sites ) ) {
			unset( $social_sites['placebo'] );
		}

		if ( array_key_exists( 'enabled', $old_settings ) ) {
			unset( $old_settings['enabled'] );
		}
		if ( array_key_exists( 'disabled', $old_settings ) ) {
			unset( $old_settings['disabled'] );
		}

		$old_settings['social_sites'] = array_keys( $social_sites );
		$new_settings                 = $old_settings;

		update_option( 'naked_social_share_settings', $new_settings );
	}

}