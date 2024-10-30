<?php
/*
@package kw-photo-2-wall-paper
@internal  General settings

@since 0.1
@todo
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'woocommerce_settings_tabs_array', 'kw_p2wp_add_settings_tab', 50 );
function kw_p2wp_add_settings_tab( $settings_tabs ) {
	$settings_tabs['kw_p2wp'] = __( 'Wall Paper', KW_P2WP_DOMAIN );
	return $settings_tabs;
}

add_action( 'woocommerce_settings_tabs_kw_p2wp', 'kw_p2wp_settings_tab' );
function kw_p2wp_settings_tab() {
    woocommerce_admin_fields( kw_p2wp_get_kw_p2wp_settings() );
}

add_action( 'woocommerce_update_options_kw_p2wp', 'kw_p2wp_update_kw_p2wp_settings' );
function kw_p2wp_update_kw_p2wp_settings() {
    woocommerce_update_options( kw_p2wp_get_kw_p2wp_settings() );
}

function kw_p2wp_get_kw_p2wp_settings() {
	$kw_p2wp_default_css = 'max-width:150px;';

	$kw_p2wp_settings_section_title = array();
	$kw_p2wp_settings_section_title['name'] = __( 'Wall Paper Settings', KW_P2WP_DOMAIN );
	$kw_p2wp_settings_section_title['type'] = 'title';
	$kw_p2wp_settings_section_title['desc'] = __( 'The following options are used to configure Wall Paper.', KW_P2WP_DOMAIN );
	$kw_p2wp_settings_section_title['id'] = 'kw_p2wp_settings_section_title';

	$kw_p2wp_settings_units_options = array( 'm' => __( 'metric', KW_P2WP_DOMAIN ), 'in' => __( 'imperial', KW_P2WP_DOMAIN ) ); // Future settings
	$kw_p2wp_settings_units_options = array( 'm' => __( 'metric', KW_P2WP_DOMAIN ) );
	$kw_p2wp_strings_for_translation = array( 'm' => __( 'Square meter', KW_P2WP_DOMAIN ), 'in' => __( 'Square inch', KW_P2WP_DOMAIN ) );
	$kw_p2wp_strings_for_translation = array( 'm' => __( 'Square meters', KW_P2WP_DOMAIN ), 'in' => __( 'Square inches', KW_P2WP_DOMAIN ) );
	$kw_p2wp_strings_for_translation = array( 'm' => __( 'Meter', KW_P2WP_DOMAIN ), 'in' => __( 'Inch', KW_P2WP_DOMAIN ) );
	$kw_p2wp_strings_for_translation = array( 'm' => __( 'Meters', KW_P2WP_DOMAIN ), 'in' => __( 'Inches', KW_P2WP_DOMAIN ) );
	$kw_p2wp_settings_units = array();
	$kw_p2wp_settings_units['name'] = __( 'Units', KW_P2WP_DOMAIN );
	$kw_p2wp_settings_units['type'] = 'select';
	$kw_p2wp_settings_units['options'] = $kw_p2wp_settings_units_options;
	$kw_p2wp_settings_units['desc'] = __( 'These units are used for the surface price.', KW_P2WP_DOMAIN );
	$kw_p2wp_settings_units['id'] = 'kw_p2wp_settings_units';

	$kw_p2wp_settings_resolution_options = array( '75' => '75', '150' => '150', '300' => '300' ); // Future settings
	$kw_p2wp_settings_resolution_options = array( '150' => '150' );
	$kw_p2wp_settings_resolution['name'] = __( 'Wall paper print resolution', KW_P2WP_DOMAIN );
	$kw_p2wp_settings_resolution['type'] = 'select';
	$kw_p2wp_settings_resolution['css'] = $kw_p2wp_default_css;
	$kw_p2wp_settings_resolution['options'] = $kw_p2wp_settings_resolution_options;
	$kw_p2wp_settings_resolution['desc'] = __( 'Wall paper print resolution. Consider 150 DPI as a maximum.', KW_P2WP_DOMAIN );
	$kw_p2wp_settings_resolution['id'] = 'kw_p2wp_settings_resolution';

/*
	$kw_p2wp_settings_max_filesize['name'] = __( 'Maximum file size', KW_P2WP_DOMAIN );
	$kw_p2wp_settings_max_filesize['type'] = 'text';
	$kw_p2wp_settings_max_filesize['css'] = $kw_p2wp_default_css;
	$kw_p2wp_settings_max_filesize['desc'] = __( 'Maximum file size MB', KW_P2WP_DOMAIN );
	$kw_p2wp_settings_max_filesize['id'] = 'kw_p2wp_settings_max_filesize';
*/

	$kw_p2wp_settings_section_end = array();
	$kw_p2wp_settings_section_end['type'] = 'sectionend';
	$kw_p2wp_settings_section_end['id'] = 'kw_p2wp_settings_section_end';

	$settings['kw_p2wp_settings_section_title'] = $kw_p2wp_settings_section_title;
	$settings['kw_p2wp_settings_units'] = $kw_p2wp_settings_units;
	$settings['kw_p2wp_settings_resolution'] = $kw_p2wp_settings_resolution;
//	$settings['kw_p2wp_settings_max_filesize'] = $kw_p2wp_settings_max_filesize;
	$settings['kw_p2wp_settings_section_end'] = $kw_p2wp_settings_section_end;

	return apply_filters( 'kw_p2wp_settings', $settings );
}

function kw_p2wp_delete_kw_p2wp_settings() {
	delete_option( 'kw_p2wp_settings_units' );
	delete_option( 'kw_p2wp_settings_resolution' );
//	delete_option( 'kw_p2wp_settings_max_filesize' );
}