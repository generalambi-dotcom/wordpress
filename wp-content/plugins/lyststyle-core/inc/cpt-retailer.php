<?php
/**
 * Retailer Custom Post Type
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Retailer custom post type
 */
function lyststyle_register_retailer_cpt() {
	$labels = array(
		'name'                  => _x( 'Retailers', 'Post Type General Name', 'lyststyle-core' ),
		'singular_name'         => _x( 'Retailer', 'Post Type Singular Name', 'lyststyle-core' ),
		'menu_name'             => __( 'Retailers', 'lyststyle-core' ),
		'name_admin_bar'        => __( 'Retailer', 'lyststyle-core' ),
		'archives'              => __( 'Retailer Archives', 'lyststyle-core' ),
		'attributes'            => __( 'Retailer Attributes', 'lyststyle-core' ),
		'parent_item_colon'     => __( 'Parent Retailer:', 'lyststyle-core' ),
		'all_items'             => __( 'All Retailers', 'lyststyle-core' ),
		'add_new_item'          => __( 'Add New Retailer', 'lyststyle-core' ),
		'add_new'               => __( 'Add New', 'lyststyle-core' ),
		'new_item'              => __( 'New Retailer', 'lyststyle-core' ),
		'edit_item'             => __( 'Edit Retailer', 'lyststyle-core' ),
		'update_item'           => __( 'Update Retailer', 'lyststyle-core' ),
		'view_item'             => __( 'View Retailer', 'lyststyle-core' ),
		'view_items'            => __( 'View Retailers', 'lyststyle-core' ),
		'search_items'          => __( 'Search Retailer', 'lyststyle-core' ),
		'not_found'             => __( 'Not found', 'lyststyle-core' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'lyststyle-core' ),
		'featured_image'        => __( 'Retailer Logo', 'lyststyle-core' ),
		'set_featured_image'    => __( 'Set retailer logo', 'lyststyle-core' ),
		'remove_featured_image' => __( 'Remove retailer logo', 'lyststyle-core' ),
		'use_featured_image'    => __( 'Use as retailer logo', 'lyststyle-core' ),
		'insert_into_item'      => __( 'Insert into retailer', 'lyststyle-core' ),
		'uploaded_to_this_item' => __( 'Uploaded to this retailer', 'lyststyle-core' ),
		'items_list'            => __( 'Retailers list', 'lyststyle-core' ),
		'items_list_navigation' => __( 'Retailers list navigation', 'lyststyle-core' ),
		'filter_items_list'     => __( 'Filter retailers list', 'lyststyle-core' ),
	);

	$args = array(
		'label'               => __( 'Retailer', 'lyststyle-core' ),
		'description'         => __( 'Partner retail stores', 'lyststyle-core' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', 'custom-fields' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-store',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
		'rewrite'             => array( 'slug' => 'retailers' ),
	);

	register_post_type( 'retailer', $args );
}
add_action( 'init', 'lyststyle_register_retailer_cpt', 0 );

/**
 * Register retailer meta fields
 */
function lyststyle_register_retailer_meta() {
	register_post_meta( 'retailer', '_retailer_website_url', array(
		'type'         => 'string',
		'single'       => true,
		'show_in_rest' => true,
		'default'      => '',
	) );

	register_post_meta( 'retailer', '_retailer_base_country', array(
		'type'         => 'string',
		'single'       => true,
		'show_in_rest' => true,
		'default'      => 'GB',
	) );
}
add_action( 'init', 'lyststyle_register_retailer_meta' );

/**
 * Add meta box for retailer details
 */
function lyststyle_add_retailer_meta_box() {
	add_meta_box(
		'lyststyle_retailer_details',
		__( 'Retailer Details', 'lyststyle-core' ),
		'lyststyle_render_retailer_meta_box',
		'retailer',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'lyststyle_add_retailer_meta_box' );

/**
 * Render retailer meta box
 */
function lyststyle_render_retailer_meta_box( $post ) {
	wp_nonce_field( 'lyststyle_retailer_meta', 'lyststyle_retailer_meta_nonce' );

	$website_url   = get_post_meta( $post->ID, '_retailer_website_url', true );
	$base_country  = get_post_meta( $post->ID, '_retailer_base_country', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="retailer_website_url"><?php esc_html_e( 'Website URL', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="url" id="retailer_website_url" name="retailer_website_url" value="<?php echo esc_url( $website_url ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="retailer_base_country"><?php esc_html_e( 'Base Country', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="text" id="retailer_base_country" name="retailer_base_country" value="<?php echo esc_attr( $base_country ); ?>" class="regular-text" placeholder="GB" />
				<p class="description"><?php esc_html_e( 'Two-letter country code (e.g., GB, US, FR)', 'lyststyle-core' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save retailer meta
 */
function lyststyle_save_retailer_meta( $post_id ) {
	if ( ! isset( $_POST['lyststyle_retailer_meta_nonce'] ) || ! wp_verify_nonce( $_POST['lyststyle_retailer_meta_nonce'], 'lyststyle_retailer_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['retailer_website_url'] ) ) {
		update_post_meta( $post_id, '_retailer_website_url', esc_url_raw( $_POST['retailer_website_url'] ) );
	}

	if ( isset( $_POST['retailer_base_country'] ) ) {
		update_post_meta( $post_id, '_retailer_base_country', sanitize_text_field( $_POST['retailer_base_country'] ) );
	}
}
add_action( 'save_post_retailer', 'lyststyle_save_retailer_meta' );
