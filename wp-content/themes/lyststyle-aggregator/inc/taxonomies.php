<?php
/**
 * Custom Taxonomies
 *
 * @package Lyststyle_Aggregator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Product Category Taxonomy
 */
function lyststyle_register_product_category_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'lyststyle-aggregator' ),
        'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'lyststyle-aggregator' ),
        'menu_name'                  => __( 'Product Categories', 'lyststyle-aggregator' ),
        'all_items'                  => __( 'All Categories', 'lyststyle-aggregator' ),
        'parent_item'                => __( 'Parent Category', 'lyststyle-aggregator' ),
        'parent_item_colon'          => __( 'Parent Category:', 'lyststyle-aggregator' ),
        'new_item_name'              => __( 'New Category Name', 'lyststyle-aggregator' ),
        'add_new_item'               => __( 'Add New Category', 'lyststyle-aggregator' ),
        'edit_item'                  => __( 'Edit Category', 'lyststyle-aggregator' ),
        'update_item'                => __( 'Update Category', 'lyststyle-aggregator' ),
        'view_item'                  => __( 'View Category', 'lyststyle-aggregator' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'lyststyle-aggregator' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'lyststyle-aggregator' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'lyststyle-aggregator' ),
        'popular_items'              => __( 'Popular Categories', 'lyststyle-aggregator' ),
        'search_items'               => __( 'Search Categories', 'lyststyle-aggregator' ),
        'not_found'                  => __( 'Not Found', 'lyststyle-aggregator' ),
        'no_terms'                   => __( 'No categories', 'lyststyle-aggregator' ),
        'items_list'                 => __( 'Categories list', 'lyststyle-aggregator' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'lyststyle-aggregator' ),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => array( 'slug' => 'category' ),
    );

    register_taxonomy( 'product_category', array( 'product' ), $args );
}
add_action( 'init', 'lyststyle_register_product_category_taxonomy', 0 );

/**
 * Register Brand Taxonomy
 */
function lyststyle_register_brand_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Brands', 'Taxonomy General Name', 'lyststyle-aggregator' ),
        'singular_name'              => _x( 'Brand', 'Taxonomy Singular Name', 'lyststyle-aggregator' ),
        'menu_name'                  => __( 'Brands', 'lyststyle-aggregator' ),
        'all_items'                  => __( 'All Brands', 'lyststyle-aggregator' ),
        'parent_item'                => __( 'Parent Brand', 'lyststyle-aggregator' ),
        'parent_item_colon'          => __( 'Parent Brand:', 'lyststyle-aggregator' ),
        'new_item_name'              => __( 'New Brand Name', 'lyststyle-aggregator' ),
        'add_new_item'               => __( 'Add New Brand', 'lyststyle-aggregator' ),
        'edit_item'                  => __( 'Edit Brand', 'lyststyle-aggregator' ),
        'update_item'                => __( 'Update Brand', 'lyststyle-aggregator' ),
        'view_item'                  => __( 'View Brand', 'lyststyle-aggregator' ),
        'separate_items_with_commas' => __( 'Separate brands with commas', 'lyststyle-aggregator' ),
        'add_or_remove_items'        => __( 'Add or remove brands', 'lyststyle-aggregator' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'lyststyle-aggregator' ),
        'popular_items'              => __( 'Popular Brands', 'lyststyle-aggregator' ),
        'search_items'               => __( 'Search Brands', 'lyststyle-aggregator' ),
        'not_found'                  => __( 'Not Found', 'lyststyle-aggregator' ),
        'no_terms'                   => __( 'No brands', 'lyststyle-aggregator' ),
        'items_list'                 => __( 'Brands list', 'lyststyle-aggregator' ),
        'items_list_navigation'      => __( 'Brands list navigation', 'lyststyle-aggregator' ),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => array( 'slug' => 'brand' ),
    );

    register_taxonomy( 'brand', array( 'product' ), $args );
}
add_action( 'init', 'lyststyle_register_brand_taxonomy', 0 );

/**
 * Register Product Tag Taxonomy
 */
function lyststyle_register_product_tag_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Product Tags', 'Taxonomy General Name', 'lyststyle-aggregator' ),
        'singular_name'              => _x( 'Product Tag', 'Taxonomy Singular Name', 'lyststyle-aggregator' ),
        'menu_name'                  => __( 'Product Tags', 'lyststyle-aggregator' ),
        'all_items'                  => __( 'All Tags', 'lyststyle-aggregator' ),
        'parent_item'                => __( 'Parent Tag', 'lyststyle-aggregator' ),
        'parent_item_colon'          => __( 'Parent Tag:', 'lyststyle-aggregator' ),
        'new_item_name'              => __( 'New Tag Name', 'lyststyle-aggregator' ),
        'add_new_item'               => __( 'Add New Tag', 'lyststyle-aggregator' ),
        'edit_item'                  => __( 'Edit Tag', 'lyststyle-aggregator' ),
        'update_item'                => __( 'Update Tag', 'lyststyle-aggregator' ),
        'view_item'                  => __( 'View Tag', 'lyststyle-aggregator' ),
        'separate_items_with_commas' => __( 'Separate tags with commas', 'lyststyle-aggregator' ),
        'add_or_remove_items'        => __( 'Add or remove tags', 'lyststyle-aggregator' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'lyststyle-aggregator' ),
        'popular_items'              => __( 'Popular Tags', 'lyststyle-aggregator' ),
        'search_items'               => __( 'Search Tags', 'lyststyle-aggregator' ),
        'not_found'                  => __( 'Not Found', 'lyststyle-aggregator' ),
        'no_terms'                   => __( 'No tags', 'lyststyle-aggregator' ),
        'items_list'                 => __( 'Tags list', 'lyststyle-aggregator' ),
        'items_list_navigation'      => __( 'Tags list navigation', 'lyststyle-aggregator' ),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => array( 'slug' => 'product-tag' ),
    );

    register_taxonomy( 'product_tag', array( 'product' ), $args );
}
add_action( 'init', 'lyststyle_register_product_tag_taxonomy', 0 );
