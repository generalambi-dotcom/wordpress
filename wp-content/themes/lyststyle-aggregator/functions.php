<?php
/**
 * Lyststyle Aggregator Theme Functions
 *
 * @package Lyststyle_Aggregator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme constants
define( 'LYSTSTYLE_VERSION', '1.0.0' );
define( 'LYSTSTYLE_THEME_DIR', get_template_directory() );
define( 'LYSTSTYLE_THEME_URI', get_template_directory_uri() );

/**
 * Theme Setup
 */
function lyststyle_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Set post thumbnail size
    set_post_thumbnail_size( 600, 800, true );

    // Add additional image sizes
    add_image_size( 'product-thumbnail', 400, 533, true );
    add_image_size( 'product-large', 800, 1067, true );
    add_image_size( 'hero-banner', 1200, 600, true );
    add_image_size( 'article-card', 600, 400, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'lyststyle-aggregator' ),
        'footer'  => esc_html__( 'Footer Menu', 'lyststyle-aggregator' ),
    ) );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );

    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'lyststyle_theme_setup' );

/**
 * Set content width
 */
function lyststyle_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'lyststyle_content_width', 1200 );
}
add_action( 'after_setup_theme', 'lyststyle_content_width', 0 );

/**
 * Enqueue scripts and styles
 */
function lyststyle_scripts() {
    // Main stylesheet
    wp_enqueue_style( 'lyststyle-main', LYSTSTYLE_THEME_URI . '/assets/css/main.css', array(), LYSTSTYLE_VERSION );

    // Google Fonts
    wp_enqueue_style( 'lyststyle-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', array(), null );

    // Main JavaScript
    wp_enqueue_script( 'lyststyle-main', LYSTSTYLE_THEME_URI . '/assets/js/main.js', array( 'jquery' ), LYSTSTYLE_VERSION, true );

    // Filters JavaScript
    wp_enqueue_script( 'lyststyle-filters', LYSTSTYLE_THEME_URI . '/assets/js/filters.js', array( 'jquery' ), LYSTSTYLE_VERSION, true );

    // Wishlist JavaScript
    wp_enqueue_script( 'lyststyle-wishlist', LYSTSTYLE_THEME_URI . '/assets/js/wishlist.js', array( 'jquery' ), LYSTSTYLE_VERSION, true );

    // Localize script for AJAX
    wp_localize_script( 'lyststyle-main', 'lyststyleData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'lyststyle-nonce' ),
        'siteUrl' => home_url( '/' ),
    ) );

    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'lyststyle_scripts' );

/**
 * Include required files
 */
require_once LYSTSTYLE_THEME_DIR . '/inc/custom-post-types.php';
require_once LYSTSTYLE_THEME_DIR . '/inc/taxonomies.php';
require_once LYSTSTYLE_THEME_DIR . '/inc/meta-boxes.php';
require_once LYSTSTYLE_THEME_DIR . '/inc/theme-options.php';
require_once LYSTSTYLE_THEME_DIR . '/inc/helpers.php';

/**
 * Register widget areas
 */
function lyststyle_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'lyststyle-aggregator' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'lyststyle-aggregator' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'lyststyle_widgets_init' );

/**
 * Custom excerpt length
 */
function lyststyle_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'lyststyle_excerpt_length', 999 );

/**
 * Custom excerpt more
 */
function lyststyle_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'lyststyle_excerpt_more' );

/**
 * Add REST API support for custom fields
 */
function lyststyle_register_rest_fields() {
    // Register product fields
    register_rest_field( 'product', 'product_meta', array(
        'get_callback' => function( $object ) {
            $product_id = $object['id'];
            return array(
                'sku'             => get_post_meta( $product_id, '_product_sku', true ),
                'base_price'      => get_post_meta( $product_id, '_product_base_price', true ),
                'currency'        => get_post_meta( $product_id, '_product_currency', true ),
                'gender'          => get_post_meta( $product_id, '_product_gender', true ),
                'color'           => get_post_meta( $product_id, '_product_color', true ),
                'material'        => get_post_meta( $product_id, '_product_material', true ),
                'affiliate_links' => json_decode( get_post_meta( $product_id, '_product_affiliate_links', true ), true ),
            );
        },
        'schema' => null,
    ) );
}
add_action( 'rest_api_init', 'lyststyle_register_rest_fields' );

/**
 * AJAX handler for getting wishlist products
 */
function lyststyle_get_wishlist_products() {
    check_ajax_referer( 'lyststyle-nonce', 'nonce' );

    $product_ids = isset( $_POST['product_ids'] ) ? array_map( 'intval', $_POST['product_ids'] ) : array();

    if ( empty( $product_ids ) ) {
        wp_send_json_success( array( 'products' => array() ) );
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post__in'       => $product_ids,
        'orderby'        => 'post__in',
    );

    $query = new WP_Query( $args );
    $products = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $product_id = get_the_ID();

            $products[] = array(
                'id'        => $product_id,
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'image'     => get_the_post_thumbnail_url( $product_id, 'product-thumbnail' ),
                'price'     => lyststyle_get_product_price( $product_id ),
                'currency'  => get_post_meta( $product_id, '_product_currency', true ),
                'brand'     => lyststyle_get_product_brand( $product_id ),
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success( array( 'products' => $products ) );
}
add_action( 'wp_ajax_lyststyle_get_wishlist', 'lyststyle_get_wishlist_products' );
add_action( 'wp_ajax_nopriv_lyststyle_get_wishlist', 'lyststyle_get_wishlist_products' );
