<?php
/*
@package kw-photo-2-wall-paper
@internal  Check out page
@reference https://pluginrepublic.com/add-custom-fields-woocommerce-product/
@since 0.1
@todo
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'kw_p2wp_add_custom_data_to_order', 10, 4 );
function kw_p2wp_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	foreach( $item as $cart_item_key=>$values ) {
		if( isset( $values['kw_p2wp_height'] ) ) {
			$item->add_meta_data( __( 'Height (m)', KW_P2WP_DOMAIN ), $values['kw_p2wp_height'], true );
		}
		if( isset( $values['kw_p2wp_width'] ) ) {
			$item->add_meta_data( __( 'Width (m)', KW_P2WP_DOMAIN ), $values['kw_p2wp_width'], true );
		}
		if( isset( $values['kw_p2wp_picture_id'] ) ) {
			$item->add_meta_data( __( 'Download link:', KW_P2WP_DOMAIN ), KW_P2WP_IMAGE_URL . $values['kw_p2wp_picture_id'] .'.jpg', true );
		}
	}
}	