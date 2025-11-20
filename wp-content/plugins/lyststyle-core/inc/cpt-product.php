<?php
/**
 * Product Custom Post Type
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Product custom post type
 */
function lyststyle_register_product_cpt() {
	$labels = array(
		'name'                  => _x( 'Products', 'Post Type General Name', 'lyststyle-core' ),
		'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'lyststyle-core' ),
		'menu_name'             => __( 'Products', 'lyststyle-core' ),
		'name_admin_bar'        => __( 'Product', 'lyststyle-core' ),
		'archives'              => __( 'Product Archives', 'lyststyle-core' ),
		'attributes'            => __( 'Product Attributes', 'lyststyle-core' ),
		'parent_item_colon'     => __( 'Parent Product:', 'lyststyle-core' ),
		'all_items'             => __( 'All Products', 'lyststyle-core' ),
		'add_new_item'          => __( 'Add New Product', 'lyststyle-core' ),
		'add_new'               => __( 'Add New', 'lyststyle-core' ),
		'new_item'              => __( 'New Product', 'lyststyle-core' ),
		'edit_item'             => __( 'Edit Product', 'lyststyle-core' ),
		'update_item'           => __( 'Update Product', 'lyststyle-core' ),
		'view_item'             => __( 'View Product', 'lyststyle-core' ),
		'view_items'            => __( 'View Products', 'lyststyle-core' ),
		'search_items'          => __( 'Search Product', 'lyststyle-core' ),
		'not_found'             => __( 'Not found', 'lyststyle-core' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'lyststyle-core' ),
		'featured_image'        => __( 'Product Image', 'lyststyle-core' ),
		'set_featured_image'    => __( 'Set product image', 'lyststyle-core' ),
		'remove_featured_image' => __( 'Remove product image', 'lyststyle-core' ),
		'use_featured_image'    => __( 'Use as product image', 'lyststyle-core' ),
		'insert_into_item'      => __( 'Insert into product', 'lyststyle-core' ),
		'uploaded_to_this_item' => __( 'Uploaded to this product', 'lyststyle-core' ),
		'items_list'            => __( 'Products list', 'lyststyle-core' ),
		'items_list_navigation' => __( 'Products list navigation', 'lyststyle-core' ),
		'filter_items_list'     => __( 'Filter products list', 'lyststyle-core' ),
	);

	$args = array(
		'label'               => __( 'Product', 'lyststyle-core' ),
		'description'         => __( 'Fashion products from various retailers', 'lyststyle-core' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ),
		'taxonomies'          => array( 'product_category', 'brand', 'product_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-tag',
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
add_action( 'init', 'lyststyle_register_product_cpt', 0 );
