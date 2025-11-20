<?php
/**
 * Helper Functions
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get product affiliate links
 *
 * @param int $product_id Product ID.
 * @return array Array of affiliate link objects.
 */
function lyststyle_get_product_affiliate_links( $product_id ) {
	$links_json = get_post_meta( $product_id, '_product_affiliate_links', true );

	if ( empty( $links_json ) ) {
		return array();
	}

	$links = json_decode( $links_json, true );

	return is_array( $links ) ? $links : array();
}

/**
 * Get product minimum price from affiliate links
 *
 * @param int $product_id Product ID.
 * @return float|null Minimum price or null if no prices available.
 */
function lyststyle_get_product_min_price( $product_id ) {
	$base_price = get_post_meta( $product_id, '_product_base_price', true );
	$links = lyststyle_get_product_affiliate_links( $product_id );

	$prices = array();

	if ( ! empty( $base_price ) && $base_price > 0 ) {
		$prices[] = floatval( $base_price );
	}

	foreach ( $links as $link ) {
		if ( ! empty( $link['price'] ) && $link['price'] > 0 ) {
			$prices[] = floatval( $link['price'] );
		}
	}

	if ( empty( $prices ) ) {
		return null;
	}

	return min( $prices );
}

/**
 * Get product gender
 *
 * @param int $product_id Product ID.
 * @return string Gender value (women, men, unisex, all).
 */
function lyststyle_get_product_gender( $product_id ) {
	$gender = get_post_meta( $product_id, '_product_gender', true );
	return ! empty( $gender ) ? $gender : 'all';
}

/**
 * Get product colour
 *
 * @param int $product_id Product ID.
 * @return string Colour value.
 */
function lyststyle_get_product_colour( $product_id ) {
	return get_post_meta( $product_id, '_product_color', true );
}

/**
 * Get product currency
 *
 * @param int $product_id Product ID.
 * @return string Currency code.
 */
function lyststyle_get_product_currency( $product_id ) {
	$currency = get_post_meta( $product_id, '_product_currency', true );
	return ! empty( $currency ) ? $currency : 'GBP';
}

/**
 * Format price with currency symbol
 *
 * @param float  $price    Price value.
 * @param string $currency Currency code.
 * @return string Formatted price.
 */
function lyststyle_format_price( $price, $currency = 'GBP' ) {
	$symbols = array(
		'GBP' => '£',
		'USD' => '$',
		'EUR' => '€',
	);

	$symbol = $symbols[ $currency ] ?? $currency;

	return $symbol . number_format( $price, 2 );
}

/**
 * Get first brand term for a product
 *
 * @param int $product_id Product ID.
 * @return WP_Term|null Brand term object or null.
 */
function lyststyle_get_product_brand( $product_id ) {
	$brands = get_the_terms( $product_id, 'brand' );

	if ( empty( $brands ) || is_wp_error( $brands ) ) {
		return null;
	}

	return reset( $brands );
}

/**
 * Get product categories
 *
 * @param int $product_id Product ID.
 * @return array Array of term objects.
 */
function lyststyle_get_product_categories( $product_id ) {
	$categories = get_the_terms( $product_id, 'product_category' );

	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return array();
	}

	return $categories;
}

/**
 * Get session ID for tracking
 *
 * @return string Session ID.
 */
function lyststyle_get_session_id() {
	if ( ! session_id() ) {
		session_start();
	}

	if ( ! isset( $_SESSION['lyststyle_session_id'] ) ) {
		$_SESSION['lyststyle_session_id'] = wp_generate_uuid4();
	}

	return $_SESSION['lyststyle_session_id'];
}

/**
 * Get currency symbol
 *
 * @param string $currency Currency code.
 * @return string Currency symbol.
 */
function lyststyle_get_currency_symbol( $currency = 'GBP' ) {
	$symbols = array(
		'GBP' => '£',
		'USD' => '$',
		'EUR' => '€',
	);

	return $symbols[ $currency ] ?? $currency;
}

/**
 * Get available colours for filters
 *
 * @return array Array of colour options.
 */
function lyststyle_get_available_colours() {
	return array(
		'black'       => __( 'Black', 'lyststyle-core' ),
		'white'       => __( 'White', 'lyststyle-core' ),
		'grey'        => __( 'Grey', 'lyststyle-core' ),
		'navy'        => __( 'Navy', 'lyststyle-core' ),
		'brown'       => __( 'Brown', 'lyststyle-core' ),
		'beige'       => __( 'Beige', 'lyststyle-core' ),
		'green'       => __( 'Green', 'lyststyle-core' ),
		'blue'        => __( 'Blue', 'lyststyle-core' ),
		'red'         => __( 'Red', 'lyststyle-core' ),
		'pink'        => __( 'Pink', 'lyststyle-core' ),
		'purple'      => __( 'Purple', 'lyststyle-core' ),
		'yellow'      => __( 'Yellow', 'lyststyle-core' ),
		'orange'      => __( 'Orange', 'lyststyle-core' ),
		'metallic'    => __( 'Metallic', 'lyststyle-core' ),
		'multicolour' => __( 'Multicolour', 'lyststyle-core' ),
	);
}

/**
 * Get available occasions
 *
 * @return array Array of occasion options.
 */
function lyststyle_get_available_occasions() {
	return array(
		'work'          => __( 'Work', 'lyststyle-core' ),
		'weekend'       => __( 'Weekend', 'lyststyle-core' ),
		'night_out'     => __( 'Night Out', 'lyststyle-core' ),
		'holiday'       => __( 'Holiday', 'lyststyle-core' ),
		'wedding_guest' => __( 'Wedding Guest', 'lyststyle-core' ),
		'cold_weather'  => __( 'Cold Weather', 'lyststyle-core' ),
		'gym'           => __( 'Gym', 'lyststyle-core' ),
		'travel'        => __( 'Travel', 'lyststyle-core' ),
		'everyday'      => __( 'Everyday', 'lyststyle-core' ),
	);
}

/**
 * Get available price bands
 *
 * @return array Array of price band options.
 */
function lyststyle_get_price_bands() {
	return array(
		'budget'       => __( 'Budget (Under £50)', 'lyststyle-core' ),
		'mid'          => __( 'Mid (£50-£200)', 'lyststyle-core' ),
		'premium'      => __( 'Premium (£200-£500)', 'lyststyle-core' ),
		'luxury'       => __( 'Luxury (£500-£1000)', 'lyststyle-core' ),
		'ultra_luxury' => __( 'Ultra Luxury (£1000+)', 'lyststyle-core' ),
	);
}

/**
 * Get price range for price band
 *
 * @param string $band Price band key.
 * @return array Array with min and max values.
 */
function lyststyle_get_price_band_range( $band ) {
	$ranges = array(
		'budget'       => array( 'min' => 0, 'max' => 50 ),
		'mid'          => array( 'min' => 50, 'max' => 200 ),
		'premium'      => array( 'min' => 200, 'max' => 500 ),
		'luxury'       => array( 'min' => 500, 'max' => 1000 ),
		'ultra_luxury' => array( 'min' => 1000, 'max' => 999999 ),
	);

	return $ranges[ $band ] ?? array( 'min' => 0, 'max' => 999999 );
}
