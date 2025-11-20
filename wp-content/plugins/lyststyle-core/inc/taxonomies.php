<?php
/**
 * Custom Taxonomies
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Product Category taxonomy
 */
function lyststyle_register_product_category_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'lyststyle-core' ),
		'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'lyststyle-core' ),
		'menu_name'                  => __( 'Categories', 'lyststyle-core' ),
		'all_items'                  => __( 'All Categories', 'lyststyle-core' ),
		'parent_item'                => __( 'Parent Category', 'lyststyle-core' ),
		'parent_item_colon'          => __( 'Parent Category:', 'lyststyle-core' ),
		'new_item_name'              => __( 'New Category Name', 'lyststyle-core' ),
		'add_new_item'               => __( 'Add New Category', 'lyststyle-core' ),
		'edit_item'                  => __( 'Edit Category', 'lyststyle-core' ),
		'update_item'                => __( 'Update Category', 'lyststyle-core' ),
		'view_item'                  => __( 'View Category', 'lyststyle-core' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'lyststyle-core' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'lyststyle-core' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'lyststyle-core' ),
		'popular_items'              => __( 'Popular Categories', 'lyststyle-core' ),
		'search_items'               => __( 'Search Categories', 'lyststyle-core' ),
		'not_found'                  => __( 'Not Found', 'lyststyle-core' ),
		'no_terms'                   => __( 'No categories', 'lyststyle-core' ),
		'items_list'                 => __( 'Categories list', 'lyststyle-core' ),
		'items_list_navigation'      => __( 'Categories list navigation', 'lyststyle-core' ),
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
 * Register Brand taxonomy
 */
function lyststyle_register_brand_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Brands', 'Taxonomy General Name', 'lyststyle-core' ),
		'singular_name'              => _x( 'Brand', 'Taxonomy Singular Name', 'lyststyle-core' ),
		'menu_name'                  => __( 'Brands', 'lyststyle-core' ),
		'all_items'                  => __( 'All Brands', 'lyststyle-core' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'new_item_name'              => __( 'New Brand Name', 'lyststyle-core' ),
		'add_new_item'               => __( 'Add New Brand', 'lyststyle-core' ),
		'edit_item'                  => __( 'Edit Brand', 'lyststyle-core' ),
		'update_item'                => __( 'Update Brand', 'lyststyle-core' ),
		'view_item'                  => __( 'View Brand', 'lyststyle-core' ),
		'separate_items_with_commas' => __( 'Separate brands with commas', 'lyststyle-core' ),
		'add_or_remove_items'        => __( 'Add or remove brands', 'lyststyle-core' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'lyststyle-core' ),
		'popular_items'              => __( 'Popular Brands', 'lyststyle-core' ),
		'search_items'               => __( 'Search Brands', 'lyststyle-core' ),
		'not_found'                  => __( 'Not Found', 'lyststyle-core' ),
		'no_terms'                   => __( 'No brands', 'lyststyle-core' ),
		'items_list'                 => __( 'Brands list', 'lyststyle-core' ),
		'items_list_navigation'      => __( 'Brands list navigation', 'lyststyle-core' ),
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
 * Register Product Tag taxonomy
 */
function lyststyle_register_product_tag_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Product Tags', 'Taxonomy General Name', 'lyststyle-core' ),
		'singular_name'              => _x( 'Product Tag', 'Taxonomy Singular Name', 'lyststyle-core' ),
		'menu_name'                  => __( 'Product Tags', 'lyststyle-core' ),
		'all_items'                  => __( 'All Tags', 'lyststyle-core' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'new_item_name'              => __( 'New Tag Name', 'lyststyle-core' ),
		'add_new_item'               => __( 'Add New Tag', 'lyststyle-core' ),
		'edit_item'                  => __( 'Edit Tag', 'lyststyle-core' ),
		'update_item'                => __( 'Update Tag', 'lyststyle-core' ),
		'view_item'                  => __( 'View Tag', 'lyststyle-core' ),
		'separate_items_with_commas' => __( 'Separate tags with commas', 'lyststyle-core' ),
		'add_or_remove_items'        => __( 'Add or remove tags', 'lyststyle-core' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'lyststyle-core' ),
		'popular_items'              => __( 'Popular Tags', 'lyststyle-core' ),
		'search_items'               => __( 'Search Tags', 'lyststyle-core' ),
		'not_found'                  => __( 'Not Found', 'lyststyle-core' ),
		'no_terms'                   => __( 'No tags', 'lyststyle-core' ),
		'items_list'                 => __( 'Tags list', 'lyststyle-core' ),
		'items_list_navigation'      => __( 'Tags list navigation', 'lyststyle-core' ),
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
		'rewrite'           => array( 'slug' => 'tag' ),
	);

	register_taxonomy( 'product_tag', array( 'product' ), $args );
}
add_action( 'init', 'lyststyle_register_product_tag_taxonomy', 0 );
