<?php
/*
@package kw-photo-2-wall-paper
@internal  Cart page
@reference https://pluginrepublic.com/add-custom-fields-woocommerce-product/
@since 0.1
@todo
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


add_filter( 'woocommerce_cart_item_name', 'kw_p2wp_cart_item_name', 10, 3 );
function kw_p2wp_cart_item_name( $name, $cart_item, $cart_item_key ) {
	if( isset( $cart_item['kw_p2wp_height'] ) ) {
		$name .= sprintf(
			'<p>'. __( 'Size', KW_P2WP_DOMAIN ) .' %1$s x %2$s m</p>',
			esc_attr( $cart_item['kw_p2wp_height'] ),
			esc_attr( $cart_item['kw_p2wp_width'] )
		);
	}
	return $name;
}

add_filter( 'woocommerce_cart_item_thumbnail', 'kw_p2wp_cart_item_image', 10, 3 );
function kw_p2wp_cart_item_image( $product_image, $cart_item, $cart_item_key ) {
//	$cart_item = WC()->cart->cart_contents[ $cart_item_key ];
	if ( $cart_item['kw_p2wp_picture_id'] ) {
		$product_image = '<img src="'. KW_P2WP_IMAGE_URL . 'thumbnail/' . esc_attr( $cart_item['kw_p2wp_picture_id'] ) .'.jpg" loading=lazy />';
	}
	else {
		$product_image = $product_image;
	}
	return $product_image;
}

// add_filter( 'woocommerce_cart_item_price', 'kw_p2wp_change_product_price_cart', 10, 3 );
function kw_p2wp_change_product_price_cart( $price, $cart_item, $cart_item_key ) {
    if ( WC()->cart->display_prices_including_tax() ) {
        $product_price = wc_get_price_including_tax( $cart_item['data'] );
    } else {
        $product_price = wc_get_price_excluding_tax( $cart_item['data'] );
    }

    if ( isset( $cart_item['kw_p2wp_height'] ) ){
		$price = wc_price( $value['kw_p2wp_height'] * $value['kw_p2wp_width'] * $product_price );
		return $price;
    }

}

add_action( 'woocommerce_before_calculate_totals', 'kw_p2wp_before_calculate_totals', 10, 1 );
function kw_p2wp_before_calculate_totals( $cart_obj ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	foreach( $cart_obj->get_cart() as $key=>$value ) {
		if ( WC()->cart->display_prices_including_tax() ) {
			$product_price = wc_get_price_including_tax( $value['data'] );
		} else {
			$product_price = wc_get_price_excluding_tax( $value['data'] );
		}
		if( isset( $value['kw_p2wp_height'] ) ) {
			$price = wc_price( $value['kw_p2wp_height'] * $value['kw_p2wp_width'] * $product_price );
			$value['data']->set_price( ( $price ) );
		}
	}
}