<?php
/**
 * Custom Post Types
 *
 * @package Lyststyle_Aggregator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Product Post Type
 */
function lyststyle_register_product_post_type() {
    $labels = array(
        'name'                  => _x( 'Products', 'Post Type General Name', 'lyststyle-aggregator' ),
        'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'lyststyle-aggregator' ),
        'menu_name'             => __( 'Products', 'lyststyle-aggregator' ),
        'name_admin_bar'        => __( 'Product', 'lyststyle-aggregator' ),
        'archives'              => __( 'Product Archives', 'lyststyle-aggregator' ),
        'attributes'            => __( 'Product Attributes', 'lyststyle-aggregator' ),
        'parent_item_colon'     => __( 'Parent Product:', 'lyststyle-aggregator' ),
        'all_items'             => __( 'All Products', 'lyststyle-aggregator' ),
        'add_new_item'          => __( 'Add New Product', 'lyststyle-aggregator' ),
        'add_new'               => __( 'Add New', 'lyststyle-aggregator' ),
        'new_item'              => __( 'New Product', 'lyststyle-aggregator' ),
        'edit_item'             => __( 'Edit Product', 'lyststyle-aggregator' ),
        'update_item'           => __( 'Update Product', 'lyststyle-aggregator' ),
        'view_item'             => __( 'View Product', 'lyststyle-aggregator' ),
        'view_items'            => __( 'View Products', 'lyststyle-aggregator' ),
        'search_items'          => __( 'Search Product', 'lyststyle-aggregator' ),
        'not_found'             => __( 'Not found', 'lyststyle-aggregator' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'lyststyle-aggregator' ),
        'featured_image'        => __( 'Product Image', 'lyststyle-aggregator' ),
        'set_featured_image'    => __( 'Set product image', 'lyststyle-aggregator' ),
        'remove_featured_image' => __( 'Remove product image', 'lyststyle-aggregator' ),
        'use_featured_image'    => __( 'Use as product image', 'lyststyle-aggregator' ),
        'insert_into_item'      => __( 'Insert into product', 'lyststyle-aggregator' ),
        'uploaded_to_this_item' => __( 'Uploaded to this product', 'lyststyle-aggregator' ),
        'items_list'            => __( 'Products list', 'lyststyle-aggregator' ),
        'items_list_navigation' => __( 'Products list navigation', 'lyststyle-aggregator' ),
        'filter_items_list'     => __( 'Filter products list', 'lyststyle-aggregator' ),
    );

    $args = array(
        'label'               => __( 'Product', 'lyststyle-aggregator' ),
        'description'         => __( 'Fashion Products', 'lyststyle-aggregator' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
        'taxonomies'          => array( 'product_category', 'brand', 'product_tag' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-products',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
        'rewrite'             => array( 'slug' => 'products' ),
    );

    register_post_type( 'product', $args );
}
add_action( 'init', 'lyststyle_register_product_post_type', 0 );

/**
 * Register Article Post Type
 */
function lyststyle_register_article_post_type() {
    $labels = array(
        'name'                  => _x( 'Articles', 'Post Type General Name', 'lyststyle-aggregator' ),
        'singular_name'         => _x( 'Article', 'Post Type Singular Name', 'lyststyle-aggregator' ),
        'menu_name'             => __( 'Articles', 'lyststyle-aggregator' ),
        'name_admin_bar'        => __( 'Article', 'lyststyle-aggregator' ),
        'archives'              => __( 'Article Archives', 'lyststyle-aggregator' ),
        'attributes'            => __( 'Article Attributes', 'lyststyle-aggregator' ),
        'parent_item_colon'     => __( 'Parent Article:', 'lyststyle-aggregator' ),
        'all_items'             => __( 'All Articles', 'lyststyle-aggregator' ),
        'add_new_item'          => __( 'Add New Article', 'lyststyle-aggregator' ),
        'add_new'               => __( 'Add New', 'lyststyle-aggregator' ),
        'new_item'              => __( 'New Article', 'lyststyle-aggregator' ),
        'edit_item'             => __( 'Edit Article', 'lyststyle-aggregator' ),
        'update_item'           => __( 'Update Article', 'lyststyle-aggregator' ),
        'view_item'             => __( 'View Article', 'lyststyle-aggregator' ),
        'view_items'            => __( 'View Articles', 'lyststyle-aggregator' ),
        'search_items'          => __( 'Search Article', 'lyststyle-aggregator' ),
        'not_found'             => __( 'Not found', 'lyststyle-aggregator' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'lyststyle-aggregator' ),
        'featured_image'        => __( 'Article Image', 'lyststyle-aggregator' ),
        'set_featured_image'    => __( 'Set article image', 'lyststyle-aggregator' ),
        'remove_featured_image' => __( 'Remove article image', 'lyststyle-aggregator' ),
        'use_featured_image'    => __( 'Use as article image', 'lyststyle-aggregator' ),
        'insert_into_item'      => __( 'Insert into article', 'lyststyle-aggregator' ),
        'uploaded_to_this_item' => __( 'Uploaded to this article', 'lyststyle-aggregator' ),
        'items_list'            => __( 'Articles list', 'lyststyle-aggregator' ),
        'items_list_navigation' => __( 'Articles list navigation', 'lyststyle-aggregator' ),
        'filter_items_list'     => __( 'Filter articles list', 'lyststyle-aggregator' ),
    );

    $args = array(
        'label'               => __( 'Article', 'lyststyle-aggregator' ),
        'description'         => __( 'Editorial Articles and Guides', 'lyststyle-aggregator' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'author' ),
        'taxonomies'          => array( 'category', 'post_tag' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-admin-post',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
        'rewrite'             => array( 'slug' => 'guides' ),
    );

    register_post_type( 'article', $args );
}
add_action( 'init', 'lyststyle_register_article_post_type', 0 );

/**
 * Register Retailer Post Type
 */
function lyststyle_register_retailer_post_type() {
    $labels = array(
        'name'                  => _x( 'Retailers', 'Post Type General Name', 'lyststyle-aggregator' ),
        'singular_name'         => _x( 'Retailer', 'Post Type Singular Name', 'lyststyle-aggregator' ),
        'menu_name'             => __( 'Retailers', 'lyststyle-aggregator' ),
        'name_admin_bar'        => __( 'Retailer', 'lyststyle-aggregator' ),
        'archives'              => __( 'Retailer Archives', 'lyststyle-aggregator' ),
        'attributes'            => __( 'Retailer Attributes', 'lyststyle-aggregator' ),
        'all_items'             => __( 'All Retailers', 'lyststyle-aggregator' ),
        'add_new_item'          => __( 'Add New Retailer', 'lyststyle-aggregator' ),
        'add_new'               => __( 'Add New', 'lyststyle-aggregator' ),
        'new_item'              => __( 'New Retailer', 'lyststyle-aggregator' ),
        'edit_item'             => __( 'Edit Retailer', 'lyststyle-aggregator' ),
        'update_item'           => __( 'Update Retailer', 'lyststyle-aggregator' ),
        'view_item'             => __( 'View Retailer', 'lyststyle-aggregator' ),
        'view_items'            => __( 'View Retailers', 'lyststyle-aggregator' ),
        'search_items'          => __( 'Search Retailer', 'lyststyle-aggregator' ),
        'not_found'             => __( 'Not found', 'lyststyle-aggregator' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'lyststyle-aggregator' ),
    );

    $args = array(
        'label'               => __( 'Retailer', 'lyststyle-aggregator' ),
        'description'         => __( 'Retailers and Stores', 'lyststyle-aggregator' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'thumbnail' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 7,
        'menu_icon'           => 'dashicons-store',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => false,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
    );

    register_post_type( 'retailer', $args );
}
add_action( 'init', 'lyststyle_register_retailer_post_type', 0 );
