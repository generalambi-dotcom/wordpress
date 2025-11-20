<?php
/**
 * Recommendations Engine
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get recommended products for user
 *
 * @param int   $user_id User ID.
 * @param array $args    Query arguments.
 * @return array Array of product IDs sorted by relevance.
 */
function lyststyle_get_recommended_products_for_user( $user_id, $args = array() ) {
	$defaults = array(
		'limit'   => 20,
		'exclude' => array(),
	);

	$args = wp_parse_args( $args, $defaults );

	// Try to get precomputed scores first
	$scores = lyststyle_get_user_product_scores( $user_id, array( 'limit' => $args['limit'] ) );

	if ( ! empty( $scores ) ) {
		$product_ids = wp_list_pluck( $scores, 'product_id' );

		// Filter out excluded products
		if ( ! empty( $args['exclude'] ) ) {
			$product_ids = array_diff( $product_ids, $args['exclude'] );
		}

		return array_values( $product_ids );
	}

	// Fall back to preference-based recommendations
	return lyststyle_get_products_by_preferences( $user_id, $args );
}

/**
 * Get products based on user preferences
 *
 * @param int   $user_id User ID.
 * @param array $args    Query arguments.
 * @return array Array of product IDs.
 */
function lyststyle_get_products_by_preferences( $user_id, $args = array() ) {
	$prefs = lyststyle_get_user_preferences( $user_id );

	// Build query args
	$query_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 100, // Get more than needed for scoring
		'fields'         => 'ids',
	);

	// Tax query
	$tax_query = array( 'relation' => 'OR' );

	if ( ! empty( $prefs['pref_product_categories'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_category',
			'field'    => 'term_id',
			'terms'    => $prefs['pref_product_categories'],
		);
	}

	if ( ! empty( $prefs['pref_brands'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'brand',
			'field'    => 'term_id',
			'terms'    => $prefs['pref_brands'],
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$query_args['tax_query'] = $tax_query;
	}

	// Meta query for gender and price
	$meta_query = array( 'relation' => 'AND' );

	if ( ! empty( $prefs['pref_gender'] ) && $prefs['pref_gender'] !== 'all' ) {
		$meta_query[] = array(
			'relation' => 'OR',
			array(
				'key'     => '_product_gender',
				'value'   => $prefs['pref_gender'],
				'compare' => '=',
			),
			array(
				'key'     => '_product_gender',
				'value'   => 'all',
				'compare' => '=',
			),
			array(
				'key'     => '_product_gender',
				'value'   => 'unisex',
				'compare' => '=',
			),
		);
	}

	// Price filter
	if ( ! empty( $prefs['pref_price_min'] ) || ! empty( $prefs['pref_price_max'] ) ) {
		$price_min = ! empty( $prefs['pref_price_min'] ) ? $prefs['pref_price_min'] : 0;
		$price_max = ! empty( $prefs['pref_price_max'] ) ? $prefs['pref_price_max'] : 999999;

		$meta_query[] = array(
			'key'     => '_product_base_price',
			'value'   => array( $price_min, $price_max ),
			'type'    => 'NUMERIC',
			'compare' => 'BETWEEN',
		);
	}

	if ( count( $meta_query ) > 1 ) {
		$query_args['meta_query'] = $meta_query;
	}

	// Exclude products
	if ( ! empty( $args['exclude'] ) ) {
		$query_args['post__not_in'] = $args['exclude'];
	}

	// Execute query
	$product_ids = get_posts( $query_args );

	// If we have products, score them
	if ( ! empty( $product_ids ) ) {
		$scored_products = lyststyle_score_products_for_user( $product_ids, $prefs );

		// Sort by score
		arsort( $scored_products );

		// Get top N
		$limit = ! empty( $args['limit'] ) ? $args['limit'] : 20;
		$top_products = array_slice( array_keys( $scored_products ), 0, $limit, true );

		return $top_products;
	}

	// Fallback: return recent products
	$fallback_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => ! empty( $args['limit'] ) ? $args['limit'] : 20,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'fields'         => 'ids',
	);

	if ( ! empty( $args['exclude'] ) ) {
		$fallback_args['post__not_in'] = $args['exclude'];
	}

	return get_posts( $fallback_args );
}

/**
 * Score products for a user based on preferences
 *
 * @param array $product_ids Product IDs to score.
 * @param array $prefs       User preferences.
 * @return array Array of product_id => score.
 */
function lyststyle_score_products_for_user( $product_ids, $prefs ) {
	$scores = array();

	foreach ( $product_ids as $product_id ) {
		$score = 0;

		// Category match (20 points)
		if ( ! empty( $prefs['pref_product_categories'] ) ) {
			$product_categories = wp_get_post_terms( $product_id, 'product_category', array( 'fields' => 'ids' ) );
			$category_matches = array_intersect( $prefs['pref_product_categories'], $product_categories );
			$score += count( $category_matches ) * 20;
		}

		// Brand match (25 points)
		if ( ! empty( $prefs['pref_brands'] ) ) {
			$product_brands = wp_get_post_terms( $product_id, 'brand', array( 'fields' => 'ids' ) );
			$brand_matches = array_intersect( $prefs['pref_brands'], $product_brands );
			$score += count( $brand_matches ) * 25;
		}

		// Style/tag match (15 points each)
		if ( ! empty( $prefs['pref_styles'] ) ) {
			$product_tags = wp_get_post_terms( $product_id, 'product_tag', array( 'fields' => 'slugs' ) );
			$style_matches = array_intersect( $prefs['pref_styles'], $product_tags );
			$score += count( $style_matches ) * 15;
		}

		// Colour match (10 points)
		if ( ! empty( $prefs['pref_colours'] ) ) {
			$product_color = lyststyle_get_product_colour( $product_id );
			if ( ! empty( $product_color ) && in_array( strtolower( $product_color ), $prefs['pref_colours'] ) ) {
				$score += 10;
			}
		}

		// Price fit (10 points if within preferred range)
		$product_price = lyststyle_get_product_min_price( $product_id );
		if ( $product_price ) {
			$price_min = ! empty( $prefs['pref_price_min'] ) ? $prefs['pref_price_min'] : 0;
			$price_max = ! empty( $prefs['pref_price_max'] ) ? $prefs['pref_price_max'] : 999999;

			if ( $product_price >= $price_min && $product_price <= $price_max ) {
				$score += 10;

				// Bonus for being in preferred price band
				if ( ! empty( $prefs['pref_price_band'] ) ) {
					$band_range = lyststyle_get_price_band_range( $prefs['pref_price_band'] );
					if ( $product_price >= $band_range['min'] && $product_price <= $band_range['max'] ) {
						$score += 5;
					}
				}
			}
		}

		// Gender match (5 points)
		$product_gender = lyststyle_get_product_gender( $product_id );
		if ( ! empty( $prefs['pref_gender'] ) && $prefs['pref_gender'] !== 'all' ) {
			if ( $product_gender === $prefs['pref_gender'] || $product_gender === 'all' || $product_gender === 'unisex' ) {
				$score += 5;
			}
		}

		$scores[ $product_id ] = $score;
	}

	return $scores;
}

/**
 * Get similar products to a given product
 *
 * @param int   $product_id Product ID.
 * @param array $args       Query arguments.
 * @return array Array of product IDs.
 */
function lyststyle_get_similar_products( $product_id, $args = array() ) {
	$defaults = array(
		'limit'   => 10,
		'exclude' => array( $product_id ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Get product's taxonomies
	$categories = wp_get_post_terms( $product_id, 'product_category', array( 'fields' => 'ids' ) );
	$brands = wp_get_post_terms( $product_id, 'brand', array( 'fields' => 'ids' ) );
	$tags = wp_get_post_terms( $product_id, 'product_tag', array( 'fields' => 'ids' ) );

	// Build query
	$query_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $args['limit'] * 2, // Get more for better matching
		'post__not_in'   => $args['exclude'],
		'fields'         => 'ids',
	);

	// Tax query
	$tax_query = array( 'relation' => 'OR' );

	if ( ! empty( $brands ) ) {
		$tax_query[] = array(
			'taxonomy' => 'brand',
			'field'    => 'term_id',
			'terms'    => $brands,
		);
	}

	if ( ! empty( $categories ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_category',
			'field'    => 'term_id',
			'terms'    => $categories,
		);
	}

	if ( ! empty( $tags ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_tag',
			'field'    => 'term_id',
			'terms'    => $tags,
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$query_args['tax_query'] = $tax_query;
	}

	$similar_ids = get_posts( $query_args );

	// Score similarity
	$scores = array();
	foreach ( $similar_ids as $similar_id ) {
		$score = 0;

		$similar_brands = wp_get_post_terms( $similar_id, 'brand', array( 'fields' => 'ids' ) );
		$similar_categories = wp_get_post_terms( $similar_id, 'product_category', array( 'fields' => 'ids' ) );
		$similar_tags = wp_get_post_terms( $similar_id, 'product_tag', array( 'fields' => 'ids' ) );

		// Same brand = high score
		if ( ! empty( array_intersect( $brands, $similar_brands ) ) ) {
			$score += 30;
		}

		// Same category
		$score += count( array_intersect( $categories, $similar_categories ) ) * 20;

		// Similar tags
		$score += count( array_intersect( $tags, $similar_tags ) ) * 10;

		$scores[ $similar_id ] = $score;
	}

	// Sort by score
	arsort( $scores );

	// Return top N
	return array_slice( array_keys( $scores ), 0, $args['limit'], true );
}
