<?php
/**
 * Article Custom Post Type
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Article custom post type
 */
function lyststyle_register_article_cpt() {
	$labels = array(
		'name'                  => _x( 'Articles', 'Post Type General Name', 'lyststyle-core' ),
		'singular_name'         => _x( 'Article', 'Post Type Singular Name', 'lyststyle-core' ),
		'menu_name'             => __( 'Articles', 'lyststyle-core' ),
		'name_admin_bar'        => __( 'Article', 'lyststyle-core' ),
		'archives'              => __( 'Article Archives', 'lyststyle-core' ),
		'attributes'            => __( 'Article Attributes', 'lyststyle-core' ),
		'parent_item_colon'     => __( 'Parent Article:', 'lyststyle-core' ),
		'all_items'             => __( 'All Articles', 'lyststyle-core' ),
		'add_new_item'          => __( 'Add New Article', 'lyststyle-core' ),
		'add_new'               => __( 'Add New', 'lyststyle-core' ),
		'new_item'              => __( 'New Article', 'lyststyle-core' ),
		'edit_item'             => __( 'Edit Article', 'lyststyle-core' ),
		'update_item'           => __( 'Update Article', 'lyststyle-core' ),
		'view_item'             => __( 'View Article', 'lyststyle-core' ),
		'view_items'            => __( 'View Articles', 'lyststyle-core' ),
		'search_items'          => __( 'Search Article', 'lyststyle-core' ),
		'not_found'             => __( 'Not found', 'lyststyle-core' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'lyststyle-core' ),
		'featured_image'        => __( 'Article Image', 'lyststyle-core' ),
		'set_featured_image'    => __( 'Set article image', 'lyststyle-core' ),
		'remove_featured_image' => __( 'Remove article image', 'lyststyle-core' ),
		'use_featured_image'    => __( 'Use as article image', 'lyststyle-core' ),
		'insert_into_item'      => __( 'Insert into article', 'lyststyle-core' ),
		'uploaded_to_this_item' => __( 'Uploaded to this article', 'lyststyle-core' ),
		'items_list'            => __( 'Articles list', 'lyststyle-core' ),
		'items_list_navigation' => __( 'Articles list navigation', 'lyststyle-core' ),
		'filter_items_list'     => __( 'Filter articles list', 'lyststyle-core' ),
	);

	$args = array(
		'label'               => __( 'Article', 'lyststyle-core' ),
		'description'         => __( 'Editorial content, guides, and fashion stories', 'lyststyle-core' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments', 'custom-fields' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-media-document',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
		'rewrite'             => array( 'slug' => 'articles' ),
	);

	register_post_type( 'article', $args );
}
add_action( 'init', 'lyststyle_register_article_cpt', 0 );
