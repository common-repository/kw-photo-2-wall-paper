<?php
/*
Plugin Name: Photo 2 wall-paper
Plugin URI: https://carlconrad.net/wordpress/plugins/
Description: A simple WooCommerce plug-in to create and sell wall-papers from photos
Version: 0.3.4
Author: Carl Conrad
Author URI: https://carlconrad.net
Text Domain: kw-photo-2-wall-paper
Domain Path: /lang/

@package kw-photo-2-wall-paper
@internal  Main plug-in file
@since 0.1
@todo
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'KW_P2WP_DOMAIN', 'kw-photo-2-wall-paper' );
define( 'KW_P2WP_PLUGIN_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
define( 'KW_P2WP_LANG_DIR', dirname( plugin_basename( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR );
$uploads = wp_upload_dir();
define( 'KW_P2WP_IMAGE_DIR', $uploads['basedir'] . '/p2wp/' );
define( 'KW_P2WP_IMAGE_URL', $uploads['baseurl'] . '/p2wp/' );

require_once __DIR__ . '/includes/functions.php';

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	if ( is_admin() ) {
		require_once __DIR__ . '/admin/settings.php';
		require_once __DIR__ . '/admin/product-settings.php';
	}
	else {
		require_once __DIR__ . '/public/woocommerce-product-page.php';
		require_once __DIR__ . '/public/woocommerce-cart.php';
		require_once __DIR__ . '/public/woocommerce-checkout.php';
	}
}

/*-----------------------------------------------------*/

add_action( 'plugins_loaded', 'kw_p2wp_load_textdomain' );
function kw_p2wp_load_textdomain() {
	load_plugin_textdomain( KW_P2WP_DOMAIN, false, KW_P2WP_LANG_DIR );
}

/*-----------------------------------------------------*/

add_action( 'wp_ajax_kw_p2wp_upload_picture', 'kw_p2wp_upload_picture' );
add_action( 'wp_ajax_nopriv_kw_p2wp_upload_picture', 'kw_p2wp_upload_picture' );
// Documentation: https://wordpress.stackexchange.com/questions/198781/wordpress-ajax-file-upload-frontend
function kw_p2wp_upload_picture() {
	$uploadedpicture = sanitize_text_field( $_POST['uploadedpicture'] );

	if ( !function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}

	switch( sanitize_text_field( $_FILES['file']['type'] ) ) {
		case( 'image/jpeg' ) :
			$extension = 'jpg';
			break;
		case( 'image/png' ) :
			$extension = 'png';
			break;
	}
	$thumbnail_destination_folder = KW_P2WP_IMAGE_DIR . 'thumbnail/';
	@mkdir( $thumbnail_destination_folder, 0755, true );
	$destination = KW_P2WP_IMAGE_DIR . $uploadedpicture . '.' . $extension;
	$thumbnail_destination = $thumbnail_destination_folder . $uploadedpicture . '.' . $extension;
	rename( sanitize_text_field( $_FILES['file']['tmp_name'] ), $destination );
	$image = wp_get_image_editor( $destination );
	$image->resize( 100, 100, true );
	$image->save( $thumbnail_destination );
	chmod( $destination, 0755 );
	chmod( $thumbnail_destination, 0755 );
	die();
}