<?php
/*
@package kw-photo-2-wall-paper
@internal  Product page
@reference https://pluginrepublic.com/add-custom-fields-woocommerce-product/
@since 0.1
@todo
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('woocommerce_before_add_to_cart_button', 'kw_p2wp_button_on_product_page', 30);
function kw_p2wp_button_on_product_page( $post_id ) {
	global $post;
	$product = wc_get_product( $post->ID );
	
	$preview = '';
	$dpi = null !== ( get_option( 'kw_p2wp_settings_resolution' ) ) ? get_option( 'kw_p2wp_settings_resolution' ) : '150';
	$price = $product->get_regular_price();
	$currency = get_option( 'woocommerce_currency' );
	$kw_p2wp_active = $product->get_meta( 'kw_p2wp_active' );
	if ( $kw_p2wp_active == true ) {
		$kw_p2wp_height = $product->get_meta( 'kw_p2wp_height' );
		$kw_p2wp_width = $product->get_meta( 'kw_p2wp_width' );
		$kw_p2wp_picture = $product->get_meta( 'kw_p2wp_picture' );
		$kw_p2wp_picture_id = time().random_int( 0, 9999 );

		?><script>
		function update_area() {
			var height = document.getElementById("kw_p2wp_height").value;
			var width = document.getElementById("kw_p2wp_width").value;
			var area = height * width;
			var dpi = <?php echo $dpi ?>;
			var price = <?php echo $price ?>;
			var resolution_per_meter = dpi * 2.54 * 100;
			var total_price = area * price;
			if ( area != 0 ) {
				jQuery("#area").html( "<?php _e( 'Wall Paper area is " + area + " square meters / (" + total_price + " '. $currency .').', KW_P2WP_DOMAIN ) ?>" );
				jQuery("#resolution").html( "<?php _e( 'To match the defined area, the photo should be " + height * resolution_per_meter + " by " + width * resolution_per_meter + " pixels.', KW_P2WP_DOMAIN ) ?>" );
			}
			else {
				jQuery("#area").html( "<?php _e( 'Please enter height and width of the Wall Paper.', KW_P2WP_DOMAIN ) ?>" );
				jQuery("#resolution").html( "<?php _e( 'Please enter height and width of the Wall Paper to know the required picture size in pixels.', KW_P2WP_DOMAIN ) ?>" );
			}
		}
		</script>
		<script>
		jQuery(document).on("click", ".single_add_to_cart_button", function (e) {
			var file_data = jQuery("#kw_p2wp_picture").prop("files")[0];
			var form_data = new FormData();

			form_data.append( 'file', file_data );
			form_data.append( 'action', 'kw_p2wp_upload_picture' );
			form_data.append( 'uploadedpicture', <?php echo $kw_p2wp_picture_id ?> );

			jQuery.ajax({
				url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
				type: 'POST',
				contentType: false,
				processData: false,
				data: form_data,
				success: function (response) {
					jQuery(".Success-div").html("Form Submit Successfully")
					console.log( 'success' );
				},  
				error: function (response) {
					console.log( 'error' );
				}
			});
		});
		</script>
		<h2><?php _e( 'Customize the wall paper', KW_P2WP_DOMAIN ) ?></h2>
		<h3><?php _e( 'Define wall paper size', KW_P2WP_DOMAIN ) ?></h3>
		<div>
		<label for="kw_p2wp_height"><?php _e( 'Height: ', KW_P2WP_DOMAIN ) ?> </label><input id="kw_p2wp_height" class="input-text qty text" type="text" name="kw_p2wp_height" style="max-width:75px;text-align:center;" onBlur="update_area()"> <?php _e( 'Meters', KW_P2WP_DOMAIN ) ?>
		</div>
		<div>
		<label for="kw_p2wp_width"><?php _e( 'Width: ', KW_P2WP_DOMAIN ) ?> </label> <input id="kw_p2wp_width" class="input-text qty text" type="text" name="kw_p2wp_width" style="max-width:75px;text-align:center;" onBlur="update_area()"> <?php _e( 'Meters', KW_P2WP_DOMAIN ) ?><br />
		</div>
		<p id="area"><?php _e( 'Please enter height and width of the Wall Paper.', KW_P2WP_DOMAIN ) ?></p>
		<h3><?php _e( 'Select photo', KW_P2WP_DOMAIN ) ?></h3>

		<div>
		<input type="hidden" name="MAX_FILE_SIZE" value="80000000" />
		<input type="hidden" id="kw_p2wp_picture_id" name="kw_p2wp_picture_id" value="<?php echo $kw_p2wp_picture_id ?>" />
		<input type="file" id="kw_p2wp_picture" name="kw_p2wp_picture" accept="image/jpeg" onBlur="update_area()"><br /><br />
		</div>
		<p id="resolution"><?php _e( 'Please enter height and width of the Wall Paper to know the required picture size in pixels.', KW_P2WP_DOMAIN ) ?></p>
<?php
	// Blur addto cart button
	}
}

add_filter( 'woocommerce_add_to_cart_validation', 'kw_p2wp_validate_custom_field', 10, 3 );
function kw_p2wp_validate_custom_field( $passed, $product_id, $quantity ) {
	if( empty( sanitize_text_field( $_POST['kw_p2wp_height'] ) ) OR ! is_numeric( sanitize_text_field( $_POST['kw_p2wp_height'] ) ) ) {
		$passed = false;
		wc_add_notice( __( 'Please enter a height value.', KW_P2WP_DOMAIN ), 'error' );
	}
	if( empty( sanitize_text_field( $_POST['kw_p2wp_width'] ) ) OR ! is_numeric( sanitize_text_field( $_POST['kw_p2wp_width'] ) ) ) {
		$passed = false;
		wc_add_notice( __( 'Please enter a width value.', KW_P2WP_DOMAIN ), 'error' );
	}
/*
	if( empty( $_POST['kw_p2wp_picture'] ) ) {
		$passed = false;
		wc_add_notice( __( 'Please select a picture.', KW_P2WP_DOMAIN ), 'error' );
	}
*/
	return $passed;
}

add_filter( 'woocommerce_add_cart_item_data', 'kw_p2wp_add_custom_field_item_data', 10, 4 );
function kw_p2wp_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
	if( ! empty( sanitize_text_field( $_POST['kw_p2wp_height'] ) ) ) {
		$cart_item_data['kw_p2wp_height'] = sanitize_text_field( $_POST['kw_p2wp_height'] );
	}
	if( ! empty( sanitize_text_field( $_POST['kw_p2wp_width'] ) ) ) {
		$cart_item_data['kw_p2wp_width'] = sanitize_text_field( $_POST['kw_p2wp_width'] );
	}
	if( ! empty( sanitize_text_field( $_POST['kw_p2wp_picture_id'] ) ) ) {
		$cart_item_data['kw_p2wp_picture_id'] = sanitize_text_field( $_POST['kw_p2wp_picture_id'] );
	}
	return $cart_item_data;
}