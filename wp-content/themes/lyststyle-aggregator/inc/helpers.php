<?php
/**
 * Helper Functions
 *
 * @package Lyststyle_Aggregator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get product price (lowest from base or affiliate prices)
 */
function lyststyle_get_product_price( $product_id ) {
    $base_price = get_post_meta( $product_id, '_product_base_price', true );
    $affiliate_links = get_post_meta( $product_id, '_product_affiliate_links', true );

    $prices = array();

    if ( $base_price ) {
        $prices[] = floatval( $base_price );
    }

    if ( $affiliate_links ) {
        $links = json_decode( $affiliate_links, true );
        if ( is_array( $links ) ) {
            foreach ( $links as $link ) {
                if ( isset( $link['price'] ) && $link['price'] > 0 ) {
                    $prices[] = floatval( $link['price'] );
                }
            }
        }
    }

    return ! empty( $prices ) ? min( $prices ) : 0;
}

/**
 * Get formatted price with currency
 */
function lyststyle_format_price( $price, $currency = 'GBP' ) {
    $symbols = array(
        'GBP' => '£',
        'USD' => '$',
        'EUR' => '€',
    );

    $symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : $currency . ' ';

    return $symbol . number_format( $price, 2 );
}

/**
 * Get product brand name
 */
function lyststyle_get_product_brand( $product_id ) {
    $brands = get_the_terms( $product_id, 'brand' );
    if ( $brands && ! is_wp_error( $brands ) ) {
        return $brands[0]->name;
    }
    return '';
}

/**
 * Get product category name
 */
function lyststyle_get_product_category( $product_id ) {
    $categories = get_the_terms( $product_id, 'product_category' );
    if ( $categories && ! is_wp_error( $categories ) ) {
        return $categories[0]->name;
    }
    return '';
}

/**
 * Get currency symbol
 */
function lyststyle_get_currency_symbol( $currency = 'GBP' ) {
    $symbols = array(
        'GBP' => '£',
        'USD' => '$',
        'EUR' => '€',
    );

    return isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : $currency;
}

/**
 * Get all available colors for filtering
 */
function lyststyle_get_all_colors() {
    global $wpdb;

    $colors = $wpdb->get_col( "
        SELECT DISTINCT meta_value
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_product_color'
        AND meta_value != ''
        ORDER BY meta_value ASC
    " );

    return $colors;
}

/**
 * Get available colours (alias for lyststyle_get_all_colors)
 */
function lyststyle_get_available_colours() {
    return lyststyle_get_all_colors();
}

/**
 * Get price range
 */
function lyststyle_get_price_range() {
    global $wpdb;

    $prices = $wpdb->get_results( "
        SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_price,
               MAX(CAST(meta_value AS DECIMAL(10,2))) as max_price
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_product_base_price'
        AND meta_value != ''
    " );

    if ( $prices && isset( $prices[0] ) ) {
        return array(
            'min' => floor( $prices[0]->min_price ),
            'max' => ceil( $prices[0]->max_price ),
        );
    }

    return array( 'min' => 0, 'max' => 1000 );
}

/**
 * Pagination
 */
function lyststyle_pagination() {
    global $wp_query;

    $big = 999999999;

    $pagination = paginate_links( array(
        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format'    => '?paged=%#%',
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'total'     => $wp_query->max_num_pages,
        'type'      => 'array',
        'prev_text' => '&laquo; ' . esc_html__( 'Previous', 'lyststyle-aggregator' ),
        'next_text' => esc_html__( 'Next', 'lyststyle-aggregator' ) . ' &raquo;',
    ) );

    if ( $pagination ) {
        echo '<nav class="pagination" role="navigation">';
        echo '<ul class="pagination-list">';
        foreach ( $pagination as $page ) {
            echo '<li>' . $page . '</li>';
        }
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Get SVG icon
 */
function lyststyle_get_icon( $icon ) {
    $icons = array(
        'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        'heart-filled' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'menu' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
        'close' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
        'external-link' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>',
    );

    return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Get social media icons
 */
function lyststyle_get_social_icon( $platform ) {
    $icons = array(
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
        'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>',
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        'x' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
    );

    return isset( $icons[ $platform ] ) ? $icons[ $platform ] : '';
}

/**
 * Check if product is in wishlist
 */
function lyststyle_is_in_wishlist( $product_id ) {
    // This will be handled by JavaScript on the frontend
    return false;
}

/**
 * Get product affiliate links
 */
function lyststyle_get_product_affiliate_links( $product_id ) {
    $affiliate_links = get_post_meta( $product_id, '_product_affiliate_links', true );
    $links = $affiliate_links ? json_decode( $affiliate_links, true ) : array();

    if ( empty( $links ) || ! is_array( $links ) ) {
        return array();
    }

    // Sort by price (lowest first)
    usort( $links, function( $a, $b ) {
        return floatval( $a['price'] ) - floatval( $b['price'] );
    } );

    return $links;
}

/**
 * Get minimum product price
 */
function lyststyle_get_product_min_price( $product_id ) {
    return lyststyle_get_product_price( $product_id );
}

/**
 * Get similar products based on brand and category
 */
function lyststyle_get_similar_products( $product_id, $limit = 4 ) {
    $brand = lyststyle_get_product_brand( $product_id );
    $category = lyststyle_get_product_category( $product_id );

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $limit,
        'post__not_in'   => array( $product_id ),
        'orderby'        => 'rand',
    );

    // Try to get products from same brand first
    if ( $brand ) {
        $brand_term = get_term_by( 'name', $brand, 'brand' );
        if ( $brand_term ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'brand',
                    'field'    => 'term_id',
                    'terms'    => $brand_term->term_id,
                ),
            );
        }
    }

    $related_products = new WP_Query( $args );

    // If not enough products from same brand, try same category
    if ( $related_products->post_count < $limit && $category ) {
        $category_term = get_term_by( 'name', $category, 'product_category' );
        if ( $category_term ) {
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => $limit - $related_products->post_count,
                'post__not_in'   => array( $product_id ),
                'orderby'        => 'rand',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'product_category',
                        'field'    => 'term_id',
                        'terms'    => $category_term->term_id,
                    ),
                ),
            );

            $category_products = new WP_Query( $args );
            if ( $category_products->have_posts() ) {
                $related_products->posts = array_merge( $related_products->posts, $category_products->posts );
                $related_products->post_count = count( $related_products->posts );
            }
        }
    }

    return $related_products;
}

/**
 * Breadcrumbs
 */
function lyststyle_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }

    echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    echo '<ul>';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'lyststyle-aggregator' ) . '</a></li>';

    if ( is_category() || is_single() ) {
        if ( is_single() ) {
            $categories = get_the_category();
            if ( $categories ) {
                $category = $categories[0];
                echo '<li><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></li>';
            }
            echo '<li aria-current="page">' . get_the_title() . '</li>';
        } else {
            echo '<li aria-current="page">' . single_cat_title( '', false ) . '</li>';
        }
    } elseif ( is_page() ) {
        echo '<li aria-current="page">' . get_the_title() . '</li>';
    } elseif ( is_search() ) {
        echo '<li aria-current="page">' . esc_html__( 'Search Results', 'lyststyle-aggregator' ) . '</li>';
    } elseif ( is_tax() ) {
        $term = get_queried_object();
        echo '<li aria-current="page">' . esc_html( $term->name ) . '</li>';
    } elseif ( is_post_type_archive() ) {
        echo '<li aria-current="page">' . post_type_archive_title( '', false ) . '</li>';
    }

    echo '</ul>';
    echo '</nav>';
}

/**
 * Get available price bands
 */
function lyststyle_get_price_bands() {
    return array(
        'budget'      => __( 'Budget (Under £100)', 'lyststyle-aggregator' ),
        'mid-range'   => __( 'Mid-Range (£100 - £500)', 'lyststyle-aggregator' ),
        'premium'     => __( 'Premium (£500 - £1,500)', 'lyststyle-aggregator' ),
        'luxury'      => __( 'Luxury (£1,500+)', 'lyststyle-aggregator' ),
    );
}

/**
 * Get available occasions
 */
function lyststyle_get_occasions() {
    return array(
        'casual'       => __( 'Casual', 'lyststyle-aggregator' ),
        'work'         => __( 'Work', 'lyststyle-aggregator' ),
        'formal'       => __( 'Formal', 'lyststyle-aggregator' ),
        'evening'      => __( 'Evening', 'lyststyle-aggregator' ),
        'sports'       => __( 'Sports & Active', 'lyststyle-aggregator' ),
        'wedding'      => __( 'Wedding', 'lyststyle-aggregator' ),
        'party'        => __( 'Party', 'lyststyle-aggregator' ),
        'vacation'     => __( 'Vacation', 'lyststyle-aggregator' ),
    );
}

/**
 * Get user preferences
 *
 * @param int $user_id User ID (defaults to current user).
 * @return array User preferences.
 */
function lyststyle_get_user_preferences( $user_id = 0 ) {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    if ( ! $user_id ) {
        return array();
    }

    $preferences = get_user_meta( $user_id, 'lyststyle_preferences', true );

    if ( ! is_array( $preferences ) ) {
        $preferences = array();
    }

    // Set defaults
    $defaults = array(
        'gender'              => '',
        'categories'          => array(),
        'brands'              => array(),
        'price_band'          => '',
        'price_min'           => '',
        'price_max'           => '',
        'colours'             => array(),
        'styles'              => array(),
        'occasions'           => array(),
        'shoe_size'           => '',
        'clothing_size_top'   => '',
        'clothing_size_bottom' => '',
        'clothing_size_dress' => '',
    );

    return wp_parse_args( $preferences, $defaults );
}

/**
 * Save user preferences from request
 *
 * @param int $user_id User ID.
 * @param array $request Request data (typically $_POST).
 * @return bool True on success, false on failure.
 */
function lyststyle_save_user_preferences_from_request( $user_id, $request ) {
    if ( ! $user_id ) {
        return false;
    }

    $preferences = array(
        'gender'              => isset( $request['gender'] ) ? sanitize_text_field( $request['gender'] ) : '',
        'categories'          => isset( $request['categories'] ) && is_array( $request['categories'] ) ? array_map( 'sanitize_text_field', $request['categories'] ) : array(),
        'brands'              => isset( $request['brands'] ) && is_array( $request['brands'] ) ? array_map( 'sanitize_text_field', $request['brands'] ) : array(),
        'price_band'          => isset( $request['price_band'] ) ? sanitize_text_field( $request['price_band'] ) : '',
        'price_min'           => isset( $request['price_min'] ) ? sanitize_text_field( $request['price_min'] ) : '',
        'price_max'           => isset( $request['price_max'] ) ? sanitize_text_field( $request['price_max'] ) : '',
        'colours'             => isset( $request['colours'] ) && is_array( $request['colours'] ) ? array_map( 'sanitize_text_field', $request['colours'] ) : array(),
        'styles'              => isset( $request['styles'] ) && is_array( $request['styles'] ) ? array_map( 'sanitize_text_field', $request['styles'] ) : array(),
        'occasions'           => isset( $request['occasions'] ) && is_array( $request['occasions'] ) ? array_map( 'sanitize_text_field', $request['occasions'] ) : array(),
        'shoe_size'           => isset( $request['shoe_size'] ) ? sanitize_text_field( $request['shoe_size'] ) : '',
        'clothing_size_top'   => isset( $request['clothing_size_top'] ) ? sanitize_text_field( $request['clothing_size_top'] ) : '',
        'clothing_size_bottom' => isset( $request['clothing_size_bottom'] ) ? sanitize_text_field( $request['clothing_size_bottom'] ) : '',
        'clothing_size_dress' => isset( $request['clothing_size_dress'] ) ? sanitize_text_field( $request['clothing_size_dress'] ) : '',
    );

    return update_user_meta( $user_id, 'lyststyle_preferences', $preferences );
}
