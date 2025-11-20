<?php
/**
 * Lyststyle Aggregator Theme Functions
 *
 * @package Lyststyle_Aggregator
 * @version 1.0.0
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
 *
 * Sets up theme defaults and registers support for various WordPress features.
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

	// Add additional image sizes for products and content
	add_image_size( 'product-thumbnail', 400, 533, true );
	add_image_size( 'product-large', 800, 1067, true );
	add_image_size( 'product-grid', 350, 467, true );
	add_image_size( 'hero-banner', 1920, 600, true );
	add_image_size( 'article-card', 600, 400, true );
	add_image_size( 'brand-logo', 200, 100, false );

	// Register navigation menus
	register_nav_menus(
		array(
			'primary_menu'    => esc_html__( 'Primary Menu', 'lyststyle-aggregator' ),
			'help_info_menu'  => esc_html__( 'Help & Info Menu', 'lyststyle-aggregator' ),
			'footer'          => esc_html__( 'Footer Menu', 'lyststyle-aggregator' ),
		)
	);

	// Switch default core markup to output valid HTML5
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add support for custom logo
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 250,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Add support for custom background
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)
	);

	// Add theme support for selective refresh for widgets
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for editor styles
	add_theme_support( 'editor-styles' );

	// Add support for responsive embeds
	add_theme_support( 'responsive-embeds' );

	// Add support for wide and full alignment
	add_theme_support( 'align-wide' );
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

	// Wishlist JavaScript (vanilla JS - no jQuery dependency)
	wp_enqueue_script( 'lyststyle-wishlist', LYSTSTYLE_THEME_URI . '/assets/js/wishlist.js', array(), LYSTSTYLE_VERSION, true );

	// Filters JavaScript (vanilla JS - no jQuery dependency)
	wp_enqueue_script( 'lyststyle-filters', LYSTSTYLE_THEME_URI . '/assets/js/filters.js', array(), LYSTSTYLE_VERSION, true );

	// Events JavaScript (vanilla JS - no jQuery dependency)
	wp_enqueue_script( 'lyststyle-events', LYSTSTYLE_THEME_URI . '/assets/js/events.js', array(), LYSTSTYLE_VERSION, true );

	// Localize script for AJAX and REST API
	wp_localize_script(
		'lyststyle-main',
		'lyststyleData',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'restUrl'   => esc_url_raw( rest_url() ),
			'nonce'     => wp_create_nonce( 'lyststyle-nonce' ),
			'restNonce' => wp_create_nonce( 'wp_rest' ),
			'siteUrl'   => home_url( '/' ),
			'isUserLoggedIn' => is_user_logged_in(),
		)
	);

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
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'lyststyle-aggregator' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'lyststyle-aggregator' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'lyststyle_widgets_init' );

/**
 * Customizer Settings
 *
 * Register all theme customizer settings and controls
 */
function lyststyle_customize_register( $wp_customize ) {

	// =============================
	// Brand Settings Section
	// =============================
	$wp_customize->add_section(
		'lyststyle_brand_settings',
		array(
			'title'    => __( 'Brand Settings', 'lyststyle-aggregator' ),
			'priority' => 30,
		)
	);

	// Logo Upload
	$wp_customize->add_setting(
		'lyststyle_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'lyststyle_logo',
			array(
				'label'    => __( 'Logo', 'lyststyle-aggregator' ),
				'section'  => 'lyststyle_brand_settings',
				'settings' => 'lyststyle_logo',
			)
		)
	);

	// Primary Brand Color
	$wp_customize->add_setting(
		'lyststyle_primary_color',
		array(
			'default'           => '#000000',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lyststyle_primary_color',
			array(
				'label'    => __( 'Primary Brand Color', 'lyststyle-aggregator' ),
				'section'  => 'lyststyle_brand_settings',
				'settings' => 'lyststyle_primary_color',
			)
		)
	);

	// Accent Color
	$wp_customize->add_setting(
		'lyststyle_accent_color',
		array(
			'default'           => '#FF6B6B',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lyststyle_accent_color',
			array(
				'label'    => __( 'Accent Color', 'lyststyle-aggregator' ),
				'section'  => 'lyststyle_brand_settings',
				'settings' => 'lyststyle_accent_color',
			)
		)
	);

	// =============================
	// Homepage Settings Section
	// =============================
	$wp_customize->add_section(
		'lyststyle_homepage_settings',
		array(
			'title'    => __( 'Homepage Settings', 'lyststyle-aggregator' ),
			'priority' => 31,
		)
	);

	// --- Hero Banner Settings ---
	$wp_customize->add_setting(
		'lyststyle_hero_title',
		array(
			'default'           => 'The Intelligent Gift Guide',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_hero_title',
		array(
			'label'    => __( 'Hero Banner Title', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_hero_subtitle',
		array(
			'default'           => 'Discover the ultimate wishlist for discerning fashion fans.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_hero_subtitle',
		array(
			'label'    => __( 'Hero Banner Subtitle', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_hero_sponsored_by',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_hero_sponsored_by',
		array(
			'label'       => __( 'Hero Sponsored By Text', 'lyststyle-aggregator' ),
			'section'     => 'lyststyle_homepage_settings',
			'type'        => 'text',
			'description' => __( 'E.g., "Sponsored by Brand Name"', 'lyststyle-aggregator' ),
		)
	);

	$wp_customize->add_setting(
		'lyststyle_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'lyststyle_hero_image',
			array(
				'label'    => __( 'Hero Banner Background Image', 'lyststyle-aggregator' ),
				'section'  => 'lyststyle_homepage_settings',
				'settings' => 'lyststyle_hero_image',
			)
		)
	);

	$wp_customize->add_setting(
		'lyststyle_hero_cta_label',
		array(
			'default'           => 'Explore Products',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_hero_cta_label',
		array(
			'label'    => __( 'Hero CTA Button Label', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_hero_cta_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'lyststyle_hero_cta_url',
		array(
			'label'    => __( 'Hero CTA Button URL', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'url',
		)
	);

	// --- Editor's Picks Settings ---
	$wp_customize->add_setting(
		'lyststyle_editors_picks_show',
		array(
			'default'           => true,
			'sanitize_callback' => 'lyststyle_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'lyststyle_editors_picks_show',
		array(
			'label'    => __( 'Show Editor\'s Picks Section', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_editors_picks_title',
		array(
			'default'           => 'Editor\'s Picks',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_editors_picks_title',
		array(
			'label'    => __( 'Editor\'s Picks Title', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_editors_picks_subtitle',
		array(
			'default'           => 'Hand-picked luxury items curated by our experts',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_editors_picks_subtitle',
		array(
			'label'    => __( 'Editor\'s Picks Subtitle', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_editors_picks_tag',
		array(
			'default'           => 'editors-pick',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_editors_picks_tag',
		array(
			'label'       => __( 'Editor\'s Picks Product Tag Slug', 'lyststyle-aggregator' ),
			'description' => __( 'Enter the slug of the product tag (e.g., "editors-pick")', 'lyststyle-aggregator' ),
			'section'     => 'lyststyle_homepage_settings',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_editors_picks_ids',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_editors_picks_ids',
		array(
			'label'       => __( 'Editor\'s Picks Product IDs (Optional)', 'lyststyle-aggregator' ),
			'description' => __( 'Comma-separated product IDs (e.g., "123,456,789"). Leave blank to use tag.', 'lyststyle-aggregator' ),
			'section'     => 'lyststyle_homepage_settings',
			'type'        => 'text',
		)
	);

	// --- Trending Now Settings ---
	$wp_customize->add_setting(
		'lyststyle_trending_show',
		array(
			'default'           => true,
			'sanitize_callback' => 'lyststyle_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'lyststyle_trending_show',
		array(
			'label'    => __( 'Show Trending Now Section', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_trending_title',
		array(
			'default'           => 'Trending Now',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_trending_title',
		array(
			'label'    => __( 'Trending Now Title', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'text',
		)
	);

	// --- Trending Brands Settings ---
	$wp_customize->add_setting(
		'lyststyle_trending_brands_show',
		array(
			'default'           => true,
			'sanitize_callback' => 'lyststyle_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'lyststyle_trending_brands_show',
		array(
			'label'    => __( 'Show Trending Brands Section', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_trending_brands_title',
		array(
			'default'           => 'Trending Brands',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_trending_brands_title',
		array(
			'label'    => __( 'Trending Brands Title', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_trending_brands_number',
		array(
			'default'           => 8,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'lyststyle_trending_brands_number',
		array(
			'label'    => __( 'Number of Brands to Show', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 20,
				'step' => 1,
			),
		)
	);

	// --- Luxury Deals Settings ---
	$wp_customize->add_setting(
		'lyststyle_luxury_deals_show',
		array(
			'default'           => true,
			'sanitize_callback' => 'lyststyle_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'lyststyle_luxury_deals_show',
		array(
			'label'    => __( 'Show Luxury Deals Section', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_luxury_deals_title',
		array(
			'default'           => 'Luxury Deals',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_luxury_deals_title',
		array(
			'label'    => __( 'Luxury Deals Title', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_luxury_deals_tag',
		array(
			'default'           => 'luxury-deal',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_luxury_deals_tag',
		array(
			'label'       => __( 'Luxury Deals Tag Slug', 'lyststyle-aggregator' ),
			'description' => __( 'Enter the slug of the product tag (e.g., "luxury-deal")', 'lyststyle-aggregator' ),
			'section'     => 'lyststyle_homepage_settings',
			'type'        => 'text',
		)
	);

	// --- Articles & Guides Settings ---
	$wp_customize->add_setting(
		'lyststyle_articles_show',
		array(
			'default'           => true,
			'sanitize_callback' => 'lyststyle_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'lyststyle_articles_show',
		array(
			'label'    => __( 'Show Articles & Guides Section', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_articles_title',
		array(
			'default'           => 'Latest Articles & Guides',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lyststyle_articles_title',
		array(
			'label'    => __( 'Articles & Guides Title', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'lyststyle_articles_number',
		array(
			'default'           => 3,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'lyststyle_articles_number',
		array(
			'label'    => __( 'Number of Articles to Show', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_homepage_settings',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
		)
	);

	// =============================
	// Account & System Pages Section
	// =============================
	$wp_customize->add_section(
		'lyststyle_account_pages',
		array(
			'title'    => __( 'Account & System Pages', 'lyststyle-aggregator' ),
			'priority' => 32,
		)
	);

	// Get all pages for dropdown
	$pages = get_pages();
	$page_choices = array( '' => __( '-- Select Page --', 'lyststyle-aggregator' ) );
	foreach ( $pages as $page ) {
		$page_choices[ $page->ID ] = $page->post_title;
	}

	// Login Page
	$wp_customize->add_setting(
		'lyststyle_login_page',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'lyststyle_login_page',
		array(
			'label'    => __( 'Login Page', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_account_pages',
			'type'     => 'select',
			'choices'  => $page_choices,
		)
	);

	// Register Page
	$wp_customize->add_setting(
		'lyststyle_register_page',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'lyststyle_register_page',
		array(
			'label'    => __( 'Register Page', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_account_pages',
			'type'     => 'select',
			'choices'  => $page_choices,
		)
	);

	// My Account Page
	$wp_customize->add_setting(
		'lyststyle_account_page',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'lyststyle_account_page',
		array(
			'label'    => __( 'My Account Page', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_account_pages',
			'type'     => 'select',
			'choices'  => $page_choices,
		)
	);

	// Wishlist Page
	$wp_customize->add_setting(
		'lyststyle_wishlist_page',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'lyststyle_wishlist_page',
		array(
			'label'    => __( 'Wishlist Page', 'lyststyle-aggregator' ),
			'section'  => 'lyststyle_account_pages',
			'type'     => 'select',
			'choices'  => $page_choices,
		)
	);
}
add_action( 'customize_register', 'lyststyle_customize_register' );

/**
 * Sanitize checkbox values
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function lyststyle_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Output custom CSS for customizer options
 */
function lyststyle_customizer_css() {
	$primary_color = get_theme_mod( 'lyststyle_primary_color', '#000000' );
	$accent_color  = get_theme_mod( 'lyststyle_accent_color', '#FF6B6B' );
	?>
	<style type="text/css">
		:root {
			--primary-color: <?php echo esc_attr( $primary_color ); ?>;
			--accent-color: <?php echo esc_attr( $accent_color ); ?>;
		}
		.btn-primary,
		.button-primary {
			background-color: var(--primary-color);
		}
		.btn-primary:hover,
		.button-primary:hover {
			background-color: var(--accent-color);
		}
		a:hover,
		.product-card .brand {
			color: var(--accent-color);
		}
		.wishlist-btn.active,
		.wishlist-btn:hover {
			color: var(--accent-color);
		}
	</style>
	<?php
}
add_action( 'wp_head', 'lyststyle_customizer_css' );

/**
 * Product Archive Filtering - pre_get_posts
 *
 * Handles filtering on product archives by category, brand, color, gender,
 * price range, and sorting options.
 */
function lyststyle_product_archive_filters( $query ) {
	// Only run on product archives and not in admin
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// Only run on product post type archives
	if ( ! is_post_type_archive( 'product' ) && ! is_tax( 'product_category' ) && ! is_tax( 'brand' ) ) {
		return;
	}

	// Tax Query Array
	$tax_query = array( 'relation' => 'AND' );

	// Filter by Product Category
	if ( ! empty( $_GET['product_category'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_category',
			'field'    => 'slug',
			'terms'    => sanitize_text_field( wp_unslash( $_GET['product_category'] ) ),
		);
	}

	// Filter by Brand
	if ( ! empty( $_GET['brand'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'brand',
			'field'    => 'slug',
			'terms'    => sanitize_text_field( wp_unslash( $_GET['brand'] ) ),
		);
	}

	// Apply tax query if filters exist
	if ( count( $tax_query ) > 1 ) {
		$query->set( 'tax_query', $tax_query );
	}

	// Meta Query Array
	$meta_query = array( 'relation' => 'AND' );

	// Filter by Color
	if ( ! empty( $_GET['colour'] ) ) {
		$meta_query[] = array(
			'key'     => '_product_color',
			'value'   => sanitize_text_field( wp_unslash( $_GET['colour'] ) ),
			'compare' => 'LIKE',
		);
	}

	// Filter by Gender
	if ( ! empty( $_GET['gender'] ) ) {
		$meta_query[] = array(
			'key'     => '_product_gender',
			'value'   => sanitize_text_field( wp_unslash( $_GET['gender'] ) ),
			'compare' => '=',
		);
	}

	// Filter by Price Range
	if ( ! empty( $_GET['price_min'] ) || ! empty( $_GET['price_max'] ) ) {
		$price_query = array(
			'key'     => '_product_base_price',
			'type'    => 'NUMERIC',
		);

		if ( ! empty( $_GET['price_min'] ) && ! empty( $_GET['price_max'] ) ) {
			$price_query['value']   = array(
				floatval( $_GET['price_min'] ),
				floatval( $_GET['price_max'] ),
			);
			$price_query['compare'] = 'BETWEEN';
		} elseif ( ! empty( $_GET['price_min'] ) ) {
			$price_query['value']   = floatval( $_GET['price_min'] );
			$price_query['compare'] = '>=';
		} elseif ( ! empty( $_GET['price_max'] ) ) {
			$price_query['value']   = floatval( $_GET['price_max'] );
			$price_query['compare'] = '<=';
		}

		$meta_query[] = $price_query;
	}

	// Apply meta query if filters exist
	if ( count( $meta_query ) > 1 ) {
		$query->set( 'meta_query', $meta_query );
	}

	// Sorting
	if ( ! empty( $_GET['orderby'] ) ) {
		$orderby = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );

		switch ( $orderby ) {
			case 'price_low_high':
				$query->set( 'meta_key', '_product_base_price' );
				$query->set( 'orderby', 'meta_value_num' );
				$query->set( 'order', 'ASC' );
				break;

			case 'price_high_low':
				$query->set( 'meta_key', '_product_base_price' );
				$query->set( 'orderby', 'meta_value_num' );
				$query->set( 'order', 'DESC' );
				break;

			case 'new_in':
				$query->set( 'orderby', 'date' );
				$query->set( 'order', 'DESC' );
				break;

			case 'recommended':
			default:
				// Default sorting - can be customized
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'ASC' );
				break;
		}
	}
}
add_action( 'pre_get_posts', 'lyststyle_product_archive_filters' );

/**
 * Helper Function: Get Customizer Option
 *
 * Retrieves a theme customizer option with a default fallback.
 *
 * @param string $option  The option name.
 * @param mixed  $default Default value if option doesn't exist.
 * @return mixed The option value.
 */
function lyststyle_get_option( $option, $default = '' ) {
	return get_theme_mod( $option, $default );
}

/**
 * Helper Function: Check if Product is in Wishlist
 *
 * For logged-in users, checks if a product is in their wishlist stored in user meta.
 * For non-logged-in users, this should be handled via JavaScript/localStorage.
 *
 * @param int $product_id Product ID to check.
 * @return bool True if in wishlist, false otherwise.
 */
function lyststyle_is_product_in_wishlist( $product_id ) {
	if ( ! is_user_logged_in() ) {
		// For non-logged-in users, wishlist is handled in JavaScript
		return false;
	}

	$user_id  = get_current_user_id();
	$wishlist = get_user_meta( $user_id, 'lyststyle_wishlist', true );

	if ( ! is_array( $wishlist ) ) {
		$wishlist = array();
	}

	return in_array( absint( $product_id ), $wishlist, true );
}

/**
 * AJAX Handler: Add Product to Wishlist
 */
function lyststyle_ajax_add_to_wishlist() {
	check_ajax_referer( 'lyststyle-nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'You must be logged in to save to wishlist.', 'lyststyle-aggregator' ) ) );
	}

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID.', 'lyststyle-aggregator' ) ) );
	}

	$user_id  = get_current_user_id();
	$wishlist = get_user_meta( $user_id, 'lyststyle_wishlist', true );

	if ( ! is_array( $wishlist ) ) {
		$wishlist = array();
	}

	if ( ! in_array( $product_id, $wishlist, true ) ) {
		$wishlist[] = $product_id;
		update_user_meta( $user_id, 'lyststyle_wishlist', $wishlist );
		wp_send_json_success( array( 'message' => __( 'Product added to wishlist.', 'lyststyle-aggregator' ) ) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Product already in wishlist.', 'lyststyle-aggregator' ) ) );
	}
}
add_action( 'wp_ajax_lyststyle_add_to_wishlist', 'lyststyle_ajax_add_to_wishlist' );

/**
 * AJAX Handler: Remove Product from Wishlist
 */
function lyststyle_ajax_remove_from_wishlist() {
	check_ajax_referer( 'lyststyle-nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'lyststyle-aggregator' ) ) );
	}

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID.', 'lyststyle-aggregator' ) ) );
	}

	$user_id  = get_current_user_id();
	$wishlist = get_user_meta( $user_id, 'lyststyle_wishlist', true );

	if ( ! is_array( $wishlist ) ) {
		$wishlist = array();
	}

	$key = array_search( $product_id, $wishlist, true );
	if ( false !== $key ) {
		unset( $wishlist[ $key ] );
		$wishlist = array_values( $wishlist ); // Reindex array
		update_user_meta( $user_id, 'lyststyle_wishlist', $wishlist );
		wp_send_json_success( array( 'message' => __( 'Product removed from wishlist.', 'lyststyle-aggregator' ) ) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Product not in wishlist.', 'lyststyle-aggregator' ) ) );
	}
}
add_action( 'wp_ajax_lyststyle_remove_from_wishlist', 'lyststyle_ajax_remove_from_wishlist' );

/**
 * AJAX Handler: Get Wishlist Products
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

	$query    = new WP_Query( $args );
	$products = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$product_id = get_the_ID();

			// Get product price using helper function
			$price    = lyststyle_get_product_price( $product_id );
			$currency = get_post_meta( $product_id, '_product_currency', true );
			if ( ! $currency ) {
				$currency = 'GBP';
			}

			$products[] = array(
				'id'       => $product_id,
				'title'    => get_the_title(),
				'url'      => get_permalink(),
				'image'    => get_the_post_thumbnail_url( $product_id, 'product-thumbnail' ),
				'price'    => $price,
				'currency' => $currency,
				'brand'    => lyststyle_get_product_brand( $product_id ),
			);
		}
		wp_reset_postdata();
	}

	wp_send_json_success( array( 'products' => $products ) );
}
add_action( 'wp_ajax_lyststyle_get_wishlist', 'lyststyle_get_wishlist_products' );
add_action( 'wp_ajax_nopriv_lyststyle_get_wishlist', 'lyststyle_get_wishlist_products' );

/**
 * Custom excerpt length
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function lyststyle_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'lyststyle_excerpt_length', 999 );

/**
 * Custom excerpt more string
 *
 * @param string $more The excerpt more string.
 * @return string Modified excerpt more string.
 */
function lyststyle_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'lyststyle_excerpt_more' );

/**
 * Add REST API support for custom fields
 */
function lyststyle_register_rest_fields() {
	// Register product fields for REST API
	register_rest_field(
		'product',
		'product_meta',
		array(
			'get_callback' => function ( $object ) {
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
			'schema'       => null,
		)
	);

	// Register article fields for REST API
	register_rest_field(
		'article',
		'article_meta',
		array(
			'get_callback' => function ( $object ) {
				$article_id = $object['id'];
				return array(
					'author_name'   => get_post_meta( $article_id, '_article_author', true ),
					'reading_time'  => get_post_meta( $article_id, '_article_reading_time', true ),
					'featured'      => get_post_meta( $article_id, '_article_featured', true ),
				);
			},
			'schema'       => null,
		)
	);
}
add_action( 'rest_api_init', 'lyststyle_register_rest_fields' );

/**
 * Get user's wishlist
 *
 * @param int $user_id User ID (defaults to current user).
 * @return array Array of product IDs in wishlist.
 */
function lyststyle_get_user_wishlist( $user_id = 0 ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( ! $user_id ) {
		return array();
	}

	$wishlist = get_user_meta( $user_id, 'lyststyle_wishlist', true );

	if ( ! is_array( $wishlist ) ) {
		return array();
	}

	return $wishlist;
}

/**
 * Get page URL by customizer setting
 *
 * @param string $setting Customizer setting key.
 * @return string Page URL or empty string.
 */
function lyststyle_get_page_url( $setting ) {
	$page_id = get_theme_mod( $setting );
	if ( $page_id ) {
		return get_permalink( $page_id );
	}
	return '';
}
