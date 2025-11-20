<?php
/**
 * Events Tracking System
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create tracking tables on plugin activation
 */
function lyststyle_create_events_tables() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	// Events table
	$events_table = $wpdb->prefix . 'ls_events';
	$events_sql = "CREATE TABLE IF NOT EXISTS $events_table (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL DEFAULT 0,
		session_id varchar(64) NOT NULL DEFAULT '',
		product_id bigint(20) unsigned NOT NULL DEFAULT 0,
		event_type varchar(32) NOT NULL,
		event_value float DEFAULT NULL,
		meta_json longtext DEFAULT NULL,
		created_at datetime NOT NULL,
		PRIMARY KEY  (id),
		KEY user_date (user_id, created_at),
		KEY product_date (product_id, created_at),
		KEY type_date (event_type, created_at),
		KEY session_date (session_id, created_at)
	) $charset_collate;";

	// User product scores table
	$scores_table = $wpdb->prefix . 'ls_user_product_scores';
	$scores_sql = "CREATE TABLE IF NOT EXISTS $scores_table (
		user_id bigint(20) unsigned NOT NULL,
		product_id bigint(20) unsigned NOT NULL,
		score float NOT NULL DEFAULT 0,
		updated_at datetime NOT NULL,
		PRIMARY KEY  (user_id, product_id),
		KEY user_score (user_id, score)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $events_sql );
	dbDelta( $scores_sql );
}

/**
 * Log an event
 *
 * @param array $args Event arguments.
 * @return int|false Event ID on success, false on failure.
 */
function lyststyle_log_event( $args ) {
	global $wpdb;

	$defaults = array(
		'user_id'     => get_current_user_id(),
		'session_id'  => lyststyle_get_session_id(),
		'product_id'  => 0,
		'event_type'  => '',
		'event_value' => null,
		'meta'        => null,
	);

	$args = wp_parse_args( $args, $defaults );

	// Validate required fields
	if ( empty( $args['event_type'] ) ) {
		return false;
	}

	// Prepare meta_json
	$meta_json = null;
	if ( ! empty( $args['meta'] ) ) {
		$meta_json = is_string( $args['meta'] ) ? $args['meta'] : wp_json_encode( $args['meta'] );
	}

	// Insert event
	$result = $wpdb->insert(
		$wpdb->prefix . 'ls_events',
		array(
			'user_id'     => intval( $args['user_id'] ),
			'session_id'  => sanitize_text_field( $args['session_id'] ),
			'product_id'  => intval( $args['product_id'] ),
			'event_type'  => sanitize_text_field( $args['event_type'] ),
			'event_value' => $args['event_value'] !== null ? floatval( $args['event_value'] ) : null,
			'meta_json'   => $meta_json,
			'created_at'  => current_time( 'mysql' ),
		),
		array( '%d', '%s', '%d', '%s', '%f', '%s', '%s' )
	);

	if ( $result === false ) {
		return false;
	}

	return $wpdb->insert_id;
}

/**
 * Get events for a user
 *
 * @param int   $user_id User ID.
 * @param array $args    Query arguments.
 * @return array Array of event objects.
 */
function lyststyle_get_user_events( $user_id, $args = array() ) {
	global $wpdb;

	$defaults = array(
		'event_type' => '',
		'product_id' => 0,
		'limit'      => 100,
		'offset'     => 0,
		'order'      => 'DESC',
	);

	$args = wp_parse_args( $args, $defaults );

	$where = array( 'user_id = %d' );
	$where_values = array( $user_id );

	if ( ! empty( $args['event_type'] ) ) {
		$where[] = 'event_type = %s';
		$where_values[] = $args['event_type'];
	}

	if ( ! empty( $args['product_id'] ) ) {
		$where[] = 'product_id = %d';
		$where_values[] = $args['product_id'];
	}

	$where_sql = implode( ' AND ', $where );
	$order = $args['order'] === 'ASC' ? 'ASC' : 'DESC';

	$sql = $wpdb->prepare(
		"SELECT * FROM {$wpdb->prefix}ls_events
		WHERE $where_sql
		ORDER BY created_at $order
		LIMIT %d OFFSET %d",
		array_merge( $where_values, array( $args['limit'], $args['offset'] ) )
	);

	return $wpdb->get_results( $sql );
}

/**
 * Get popular products based on events
 *
 * @param array $args Query arguments.
 * @return array Array of product IDs.
 */
function lyststyle_get_popular_products( $args = array() ) {
	global $wpdb;

	$defaults = array(
		'event_type' => 'view_product',
		'days'       => 7,
		'limit'      => 20,
	);

	$args = wp_parse_args( $args, $defaults );

	$date_from = date( 'Y-m-d H:i:s', strtotime( "-{$args['days']} days" ) );

	$sql = $wpdb->prepare(
		"SELECT product_id, COUNT(*) as event_count
		FROM {$wpdb->prefix}ls_events
		WHERE event_type = %s
		AND created_at >= %s
		AND product_id > 0
		GROUP BY product_id
		ORDER BY event_count DESC
		LIMIT %d",
		$args['event_type'],
		$date_from,
		$args['limit']
	);

	$results = $wpdb->get_results( $sql );

	return wp_list_pluck( $results, 'product_id' );
}

/**
 * Update user product score
 *
 * @param int   $user_id    User ID.
 * @param int   $product_id Product ID.
 * @param float $score      Score value.
 * @return bool Success status.
 */
function lyststyle_update_user_product_score( $user_id, $product_id, $score ) {
	global $wpdb;

	$result = $wpdb->replace(
		$wpdb->prefix . 'ls_user_product_scores',
		array(
			'user_id'    => intval( $user_id ),
			'product_id' => intval( $product_id ),
			'score'      => floatval( $score ),
			'updated_at' => current_time( 'mysql' ),
		),
		array( '%d', '%d', '%f', '%s' )
	);

	return $result !== false;
}

/**
 * Get user product scores
 *
 * @param int   $user_id User ID.
 * @param array $args    Query arguments.
 * @return array Array of score objects.
 */
function lyststyle_get_user_product_scores( $user_id, $args = array() ) {
	global $wpdb;

	$defaults = array(
		'limit'  => 50,
		'offset' => 0,
	);

	$args = wp_parse_args( $args, $defaults );

	$sql = $wpdb->prepare(
		"SELECT * FROM {$wpdb->prefix}ls_user_product_scores
		WHERE user_id = %d
		ORDER BY score DESC
		LIMIT %d OFFSET %d",
		$user_id,
		$args['limit'],
		$args['offset']
	);

	return $wpdb->get_results( $sql );
}

/**
 * Get user's wishlist (from user meta)
 *
 * @param int $user_id User ID.
 * @return array Array of product IDs.
 */
function lyststyle_get_user_wishlist( $user_id ) {
	$wishlist = get_user_meta( $user_id, 'favourite_products', true );
	return ! empty( $wishlist ) && is_array( $wishlist ) ? $wishlist : array();
}

/**
 * Add product to user's wishlist
 *
 * @param int $user_id    User ID.
 * @param int $product_id Product ID.
 * @return bool Success status.
 */
function lyststyle_add_to_wishlist( $user_id, $product_id ) {
	$wishlist = lyststyle_get_user_wishlist( $user_id );

	if ( ! in_array( $product_id, $wishlist ) ) {
		$wishlist[] = intval( $product_id );
		update_user_meta( $user_id, 'favourite_products', $wishlist );

		// Log event
		lyststyle_log_event( array(
			'user_id'    => $user_id,
			'product_id' => $product_id,
			'event_type' => 'add_wishlist',
		) );

		return true;
	}

	return false;
}

/**
 * Remove product from user's wishlist
 *
 * @param int $user_id    User ID.
 * @param int $product_id Product ID.
 * @return bool Success status.
 */
function lyststyle_remove_from_wishlist( $user_id, $product_id ) {
	$wishlist = lyststyle_get_user_wishlist( $user_id );

	$key = array_search( $product_id, $wishlist );
	if ( $key !== false ) {
		unset( $wishlist[ $key ] );
		$wishlist = array_values( $wishlist ); // Re-index
		update_user_meta( $user_id, 'favourite_products', $wishlist );

		// Log event
		lyststyle_log_event( array(
			'user_id'    => $user_id,
			'product_id' => $product_id,
			'event_type' => 'remove_wishlist',
		) );

		return true;
	}

	return false;
}

/**
 * Check if product is in user's wishlist
 *
 * @param int $user_id    User ID.
 * @param int $product_id Product ID.
 * @return bool True if in wishlist, false otherwise.
 */
function lyststyle_is_in_wishlist( $user_id, $product_id ) {
	$wishlist = lyststyle_get_user_wishlist( $user_id );
	return in_array( $product_id, $wishlist );
}
