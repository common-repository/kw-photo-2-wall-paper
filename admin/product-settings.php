<?php
/*
@package kw-photo-2-wall-paper
@internal  Various settings
@since 0.1
@todo
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'woocommerce_product_options_general_product_data', 'kw_p2wp_create_custom_field' );
function kw_p2wp_create_custom_field() {
	$args = array(
		'id' => 'kw_p2wp_active',
		'label' => __( 'Is wall paper', KW_P2WP_DOMAIN ),
		'options' => array(
			'yes' =>  __( 'Yes', KW_P2WP_DOMAIN ),
			'no' => __( 'No', KW_P2WP_DOMAIN )
		),
		'description' =>  __( 'For wall-paper products, price needs to be defined for one square meter.', KW_P2WP_DOMAIN ),
		'class' => 'kw_p2wp_active'
	);
	woocommerce_wp_select( $args );
	$args = array(
		'id' => 'kw_p2wp_height'
	);
	woocommerce_wp_hidden_input( $args );
	$args = array(
		'id' => 'kw_p2wp_width'
	);
	woocommerce_wp_hidden_input( $args );
	$args = array(
		'id' => 'kw_p2wp_picture_id'
	);
	woocommerce_wp_hidden_input( $args );
}

add_action( 'woocommerce_process_product_meta', 'kw_p2wp_save_custom_field' );
function kw_p2wp_save_custom_field( $post_id ) {
	$product = wc_get_product( $post_id );
	$product->update_meta_data( 'kw_p2wp_active', sanitize_text_field( isset( $_POST['kw_p2wp_active'] ) ? $_POST['kw_p2wp_active'] : '' ) );
	$product->save();
}