<?php
/*
@package kw-photo-2-wall-paper
@internal  Various functions
@since 0.1
@todo
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function kw_p2wp_is_wallpaper( $post_id ) {
	 global $post;
	 $product = wc_get_product( $post->ID );

	$kw_p2wp_active = $product->get_meta( 'kw_p2wp_active' );
	if ( isset( $kw_p2wp_active ) ) {
		if ( $kw_p2wp_active == 'yes' )
			$active = true;
		else
			$active = false;
	}
	return $active;
}