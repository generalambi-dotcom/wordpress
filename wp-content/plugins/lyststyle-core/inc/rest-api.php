<?php
/**
 * REST API Endpoints
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register REST API routes
 */
function lyststyle_register_rest_routes() {
	// Wishlist endpoints
	register_rest_route( 'lyststyle/v1', '/wishlist/sync', array(
		'methods'             => 'POST',
		'callback'            => 'lyststyle_rest_wishlist_sync',
		'permission_callback' => function() {
			return is_user_logged_in();
		},
	) );

	register_rest_route( 'lyststyle/v1', '/wishlist/get', array(
		'methods'             => 'GET',
		'callback'            => 'lyststyle_rest_wishlist_get',
		'permission_callback' => function() {
			return is_user_logged_in();
		},
	) );

	// Events endpoint
	register_rest_route( 'lyststyle/v1', '/events/log', array(
		'methods'             => 'POST',
		'callback'            => 'lyststyle_rest_log_event',
		'permission_callback' => '__return_true', // Allow guests
	) );

	// Products endpoint (for guest wishlist)
	register_rest_route( 'lyststyle/v1', '/products/by-ids', array(
		'methods'             => 'POST',
		'callback'            => 'lyststyle_rest_get_products_by_ids',
		'permission_callback' => '__return_true',
	) );
}
add_action( 'rest_api_init', 'lyststyle_register_rest_routes' );

/**
 * Wishlist sync endpoint
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response Response object.
 */
function lyststyle_rest_wishlist_sync( $request ) {
	$user_id = get_current_user_id();

	if ( ! $user_id ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'User not logged in',
		), 401 );
	}

	$product_id = intval( $request->get_param( 'product_id' ) );
	$action = sanitize_text_field( $request->get_param( 'action' ) );

	if ( ! $product_id || ! in_array( $action, array( 'add', 'remove' ) ) ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Invalid parameters',
		), 400 );
	}

	if ( $action === 'add' ) {
		$result = lyststyle_add_to_wishlist( $user_id, $product_id );
	} else {
		$result = lyststyle_remove_from_wishlist( $user_id, $product_id );
	}

	return new WP_REST_Response( array(
		'success' => $result,
		'action'  => $action,
		'wishlist' => lyststyle_get_user_wishlist( $user_id ),
	), 200 );
}

/**
 * Get wishlist endpoint
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response Response object.
 */
function lyststyle_rest_wishlist_get( $request ) {
	$user_id = get_current_user_id();

	if ( ! $user_id ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'User not logged in',
		), 401 );
	}

	$wishlist = lyststyle_get_user_wishlist( $user_id );

	return new WP_REST_Response( array(
		'success'  => true,
		'wishlist' => $wishlist,
	), 200 );
}

/**
 * Log event endpoint
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response Response object.
 */
function lyststyle_rest_log_event( $request ) {
	$event_type = sanitize_text_field( $request->get_param( 'event_type' ) );
	$product_id = intval( $request->get_param( 'product_id' ) );
	$event_value = $request->get_param( 'event_value' );
	$meta = $request->get_param( 'meta' );

	if ( empty( $event_type ) ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Event type is required',
		), 400 );
	}

	$event_args = array(
		'event_type'  => $event_type,
		'product_id'  => $product_id,
		'event_value' => $event_value,
		'meta'        => $meta,
	);

	// Add user_id if logged in, otherwise use session
	if ( is_user_logged_in() ) {
		$event_args['user_id'] = get_current_user_id();
	} else {
		$event_args['user_id'] = 0;
		$event_args['session_id'] = lyststyle_get_session_id();
	}

	$event_id = lyststyle_log_event( $event_args );

	return new WP_REST_Response( array(
		'success'  => $event_id !== false,
		'event_id' => $event_id,
	), 200 );
}

/**
 * Get products by IDs endpoint
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response Response object.
 */
function lyststyle_rest_get_products_by_ids( $request ) {
	$product_ids = $request->get_param( 'product_ids' );

	if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Invalid product IDs',
		), 400 );
	}

	// Sanitize IDs
	$product_ids = array_map( 'intval', $product_ids );

	// Get products
	$products = array();

	foreach ( $product_ids as $product_id ) {
		$product = get_post( $product_id );

		if ( ! $product || $product->post_type !== 'product' ) {
			continue;
		}

		$brand = lyststyle_get_product_brand( $product_id );
		$min_price = lyststyle_get_product_min_price( $product_id );
		$currency = lyststyle_get_product_currency( $product_id );
		$thumbnail = get_the_post_thumbnail_url( $product_id, 'medium' );

		$products[] = array(
			'id'        => $product_id,
			'title'     => get_the_title( $product_id ),
			'permalink' => get_permalink( $product_id ),
			'brand'     => $brand ? $brand->name : '',
			'price'     => $min_price,
			'currency'  => $currency,
			'image'     => $thumbnail ? $thumbnail : '',
		);
	}

	return new WP_REST_Response( array(
		'success'  => true,
		'products' => $products,
	), 200 );
}
