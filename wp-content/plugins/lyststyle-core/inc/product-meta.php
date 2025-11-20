<?php
/**
 * Product Meta Fields & Admin UI
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register product meta fields
 */
function lyststyle_register_product_meta() {
	$meta_fields = array(
		'_product_sku' => array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
			'default'      => '',
		),
		'_product_base_price' => array(
			'type'         => 'number',
			'single'       => true,
			'show_in_rest' => true,
			'default'      => 0,
		),
		'_product_currency' => array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
			'default'      => 'GBP',
		),
		'_product_gender' => array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
			'default'      => 'all',
		),
		'_product_color' => array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
			'default'      => '',
		),
		'_product_material' => array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
			'default'      => '',
		),
		'_product_affiliate_links' => array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
			'default'      => '',
		),
	);

	foreach ( $meta_fields as $key => $args ) {
		register_post_meta( 'product', $key, $args );
	}
}
add_action( 'init', 'lyststyle_register_product_meta' );

/**
 * Add product meta boxes
 */
function lyststyle_add_product_meta_boxes() {
	add_meta_box(
		'lyststyle_product_core_details',
		__( 'Product Core Details', 'lyststyle-core' ),
		'lyststyle_render_product_core_details_meta_box',
		'product',
		'normal',
		'high'
	);

	add_meta_box(
		'lyststyle_product_affiliate_offers',
		__( 'Affiliate Offers', 'lyststyle-core' ),
		'lyststyle_render_product_affiliate_offers_meta_box',
		'product',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'lyststyle_add_product_meta_boxes' );

/**
 * Render product core details meta box
 */
function lyststyle_render_product_core_details_meta_box( $post ) {
	wp_nonce_field( 'lyststyle_product_meta', 'lyststyle_product_meta_nonce' );

	$sku          = get_post_meta( $post->ID, '_product_sku', true );
	$gender       = get_post_meta( $post->ID, '_product_gender', true ) ?: 'all';
	$color        = get_post_meta( $post->ID, '_product_color', true );
	$material     = get_post_meta( $post->ID, '_product_material', true );
	$base_price   = get_post_meta( $post->ID, '_product_base_price', true );
	$currency     = get_post_meta( $post->ID, '_product_currency', true ) ?: 'GBP';
	?>
	<table class="form-table">
		<tr>
			<th><label for="product_sku"><?php esc_html_e( 'SKU', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="text" id="product_sku" name="product_sku" value="<?php echo esc_attr( $sku ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="product_gender"><?php esc_html_e( 'Gender', 'lyststyle-core' ); ?></label></th>
			<td>
				<select id="product_gender" name="product_gender">
					<option value="all" <?php selected( $gender, 'all' ); ?>><?php esc_html_e( 'All', 'lyststyle-core' ); ?></option>
					<option value="women" <?php selected( $gender, 'women' ); ?>><?php esc_html_e( 'Women', 'lyststyle-core' ); ?></option>
					<option value="men" <?php selected( $gender, 'men' ); ?>><?php esc_html_e( 'Men', 'lyststyle-core' ); ?></option>
					<option value="unisex" <?php selected( $gender, 'unisex' ); ?>><?php esc_html_e( 'Unisex', 'lyststyle-core' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="product_color"><?php esc_html_e( 'Colour', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="text" id="product_color" name="product_color" value="<?php echo esc_attr( $color ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'e.g., Black, White, Navy, etc.', 'lyststyle-core' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="product_material"><?php esc_html_e( 'Material', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="text" id="product_material" name="product_material" value="<?php echo esc_attr( $material ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'e.g., Leather, Cotton, Wool, etc.', 'lyststyle-core' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="product_base_price"><?php esc_html_e( 'Base Price', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="number" step="0.01" id="product_base_price" name="product_base_price" value="<?php echo esc_attr( $base_price ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="product_currency"><?php esc_html_e( 'Currency', 'lyststyle-core' ); ?></label></th>
			<td>
				<select id="product_currency" name="product_currency">
					<option value="GBP" <?php selected( $currency, 'GBP' ); ?>>GBP (£)</option>
					<option value="USD" <?php selected( $currency, 'USD' ); ?>>USD ($)</option>
					<option value="EUR" <?php selected( $currency, 'EUR' ); ?>>EUR (€)</option>
				</select>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Render product affiliate offers meta box
 */
function lyststyle_render_product_affiliate_offers_meta_box( $post ) {
	$affiliate_links_json = get_post_meta( $post->ID, '_product_affiliate_links', true );
	$affiliate_links = ! empty( $affiliate_links_json ) ? json_decode( $affiliate_links_json, true ) : array();

	if ( ! is_array( $affiliate_links ) ) {
		$affiliate_links = array();
	}
	?>
	<div id="affiliate-offers-container">
		<div id="affiliate-offers-list">
			<?php
			if ( ! empty( $affiliate_links ) ) {
				foreach ( $affiliate_links as $index => $link ) {
					lyststyle_render_affiliate_offer_row( $index, $link );
				}
			}
			?>
		</div>
		<button type="button" class="button" id="add-affiliate-offer"><?php esc_html_e( 'Add Offer', 'lyststyle-core' ); ?></button>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var offerIndex = <?php echo count( $affiliate_links ); ?>;

		$('#add-affiliate-offer').on('click', function() {
			var template = '<div class="affiliate-offer-row" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; position: relative;">';
			template += '<button type="button" class="button remove-offer" style="position: absolute; top: 10px; right: 10px;">Remove</button>';
			template += '<table class="form-table"><tbody>';
			template += '<tr><th><label>Retailer Name</label></th><td><input type="text" name="affiliate_links[' + offerIndex + '][retailer_name]" class="regular-text" /></td></tr>';
			template += '<tr><th><label>Price</label></th><td><input type="number" step="0.01" name="affiliate_links[' + offerIndex + '][price]" class="regular-text" /></td></tr>';
			template += '<tr><th><label>Currency</label></th><td><select name="affiliate_links[' + offerIndex + '][currency]"><option value="GBP">GBP</option><option value="USD">USD</option><option value="EUR">EUR</option></select></td></tr>';
			template += '<tr><th><label>URL</label></th><td><input type="url" name="affiliate_links[' + offerIndex + '][url]" class="regular-text" /></td></tr>';
			template += '<tr><th><label>Shipping Info</label></th><td><input type="text" name="affiliate_links[' + offerIndex + '][shipping_info]" class="regular-text" placeholder="e.g., Free shipping" /></td></tr>';
			template += '</tbody></table></div>';

			$('#affiliate-offers-list').append(template);
			offerIndex++;
		});

		$(document).on('click', '.remove-offer', function() {
			$(this).closest('.affiliate-offer-row').remove();
		});
	});
	</script>
	<?php
}

/**
 * Render single affiliate offer row
 */
function lyststyle_render_affiliate_offer_row( $index, $link ) {
	?>
	<div class="affiliate-offer-row" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; position: relative;">
		<button type="button" class="button remove-offer" style="position: absolute; top: 10px; right: 10px;"><?php esc_html_e( 'Remove', 'lyststyle-core' ); ?></button>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label><?php esc_html_e( 'Retailer Name', 'lyststyle-core' ); ?></label></th>
					<td>
						<input type="text" name="affiliate_links[<?php echo esc_attr( $index ); ?>][retailer_name]" value="<?php echo esc_attr( $link['retailer_name'] ?? '' ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'Price', 'lyststyle-core' ); ?></label></th>
					<td>
						<input type="number" step="0.01" name="affiliate_links[<?php echo esc_attr( $index ); ?>][price]" value="<?php echo esc_attr( $link['price'] ?? '' ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'Currency', 'lyststyle-core' ); ?></label></th>
					<td>
						<select name="affiliate_links[<?php echo esc_attr( $index ); ?>][currency]">
							<option value="GBP" <?php selected( $link['currency'] ?? 'GBP', 'GBP' ); ?>>GBP</option>
							<option value="USD" <?php selected( $link['currency'] ?? 'GBP', 'USD' ); ?>>USD</option>
							<option value="EUR" <?php selected( $link['currency'] ?? 'GBP', 'EUR' ); ?>>EUR</option>
						</select>
					</td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'URL', 'lyststyle-core' ); ?></label></th>
					<td>
						<input type="url" name="affiliate_links[<?php echo esc_attr( $index ); ?>][url]" value="<?php echo esc_url( $link['url'] ?? '' ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'Shipping Info', 'lyststyle-core' ); ?></label></th>
					<td>
						<input type="text" name="affiliate_links[<?php echo esc_attr( $index ); ?>][shipping_info]" value="<?php echo esc_attr( $link['shipping_info'] ?? '' ); ?>" class="regular-text" placeholder="e.g., Free shipping" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Save product meta
 */
function lyststyle_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['lyststyle_product_meta_nonce'] ) || ! wp_verify_nonce( $_POST['lyststyle_product_meta_nonce'], 'lyststyle_product_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save core details
	if ( isset( $_POST['product_sku'] ) ) {
		update_post_meta( $post_id, '_product_sku', sanitize_text_field( $_POST['product_sku'] ) );
	}

	if ( isset( $_POST['product_gender'] ) ) {
		update_post_meta( $post_id, '_product_gender', sanitize_text_field( $_POST['product_gender'] ) );
	}

	if ( isset( $_POST['product_color'] ) ) {
		update_post_meta( $post_id, '_product_color', sanitize_text_field( $_POST['product_color'] ) );
	}

	if ( isset( $_POST['product_material'] ) ) {
		update_post_meta( $post_id, '_product_material', sanitize_text_field( $_POST['product_material'] ) );
	}

	if ( isset( $_POST['product_base_price'] ) ) {
		update_post_meta( $post_id, '_product_base_price', floatval( $_POST['product_base_price'] ) );
	}

	if ( isset( $_POST['product_currency'] ) ) {
		update_post_meta( $post_id, '_product_currency', sanitize_text_field( $_POST['product_currency'] ) );
	}

	// Save affiliate links
	if ( isset( $_POST['affiliate_links'] ) && is_array( $_POST['affiliate_links'] ) ) {
		$affiliate_links = array();

		foreach ( $_POST['affiliate_links'] as $link ) {
			if ( ! empty( $link['retailer_name'] ) && ! empty( $link['url'] ) ) {
				$affiliate_links[] = array(
					'retailer_name' => sanitize_text_field( $link['retailer_name'] ),
					'price'         => floatval( $link['price'] ?? 0 ),
					'currency'      => sanitize_text_field( $link['currency'] ?? 'GBP' ),
					'url'           => esc_url_raw( $link['url'] ),
					'shipping_info' => sanitize_text_field( $link['shipping_info'] ?? '' ),
				);
			}
		}

		update_post_meta( $post_id, '_product_affiliate_links', wp_json_encode( $affiliate_links ) );
	} else {
		update_post_meta( $post_id, '_product_affiliate_links', '' );
	}
}
add_action( 'save_post_product', 'lyststyle_save_product_meta' );
