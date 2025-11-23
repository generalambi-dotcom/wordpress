<?php
/**
 * AI Outils Theme Functions
 *
 * @package AI_Outils
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ============================================================================
 * CONFIGURATION CONSTANTS
 * ============================================================================
 *
 * Adjust these constants if the plugin uses different slugs
 */

// Custom Post Type slug (from AI Tools plugin)
define( 'AI_OUTILS_CPT_SLUG', 'ai_tool' );

// Taxonomy slug (from AI Tools plugin)
define( 'AI_OUTILS_TAXONOMY_SLUG', 'ai_category' );

// Theme version for cache busting
define( 'AI_OUTILS_VERSION', '2.0.0' );

/**
 * ============================================================================
 * INCLUDE MODULAR FILES
 * ============================================================================
 *
 * Load helper functions and AJAX handlers from inc/ directory
 */

// AI Tools helper functions (meta getters, display helpers, user functions)
require_once get_template_directory() . '/inc/ai-tools/helper-functions.php';

// AI Tools AJAX handlers (filter, load more, save, click tracking)
require_once get_template_directory() . '/inc/ai-tools/ajax-handlers.php';

/**
 * ============================================================================
 * THEME SETUP
 * ============================================================================
 */

if ( ! function_exists( 'ai_outils_setup' ) ) {
    /**
     * Theme setup
     */
    function ai_outils_setup() {
        // Add theme support
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style',
        ) );
        add_theme_support( 'custom-logo', array(
            'height'      => 50,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
        ) );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'align-wide' );

        // Register navigation menus
        register_nav_menus( array(
            'primary'  => __( 'Primary Menu', 'ai-outils' ),
            'footer-1' => __( 'Footer Navigation', 'ai-outils' ),
            'footer-2' => __( 'Footer Pages', 'ai-outils' ),
            'footer-3' => __( 'Footer Resources', 'ai-outils' ),
        ) );

        // Add image sizes
        add_image_size( 'tool-thumbnail', 300, 300, true );
        add_image_size( 'tool-logo', 100, 100, true );
        add_image_size( 'blog-featured', 800, 450, true );
    }
}
add_action( 'after_setup_theme', 'ai_outils_setup' );

/**
 * ============================================================================
 * ENQUEUE STYLES & SCRIPTS
 * ============================================================================
 */

if ( ! function_exists( 'ai_outils_enqueue_assets' ) ) {
    /**
     * Enqueue theme styles and scripts
     */
    function ai_outils_enqueue_assets() {
        // Main stylesheet - using direct path to ensure it loads
        wp_enqueue_style(
            'ai-outils-style',
            get_stylesheet_uri(),
            array(),
            AI_OUTILS_VERSION,
            'all'
        );

        // Add critical inline CSS as fallback to ensure basic styling
        $critical_css = '
            :root{--color-primary:#8B5CF6;--color-text:#1F2937;--color-bg:#FFFFFF;--color-border:#E5E7EB}
            *{margin:0;padding:0;box-sizing:border-box}
            body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;line-height:1.6;color:var(--color-text)}
            .container{max-width:1280px;margin:0 auto;padding:0 1rem}
            .site-header{background:var(--color-bg);border-bottom:1px solid var(--color-border);padding:1rem 0}
            .btn{display:inline-flex;padding:0.75rem 1.5rem;border-radius:0.5rem;text-decoration:none;font-weight:500}
            .btn-primary{background:var(--color-primary);color:#fff}
        ';
        wp_add_inline_style( 'ai-outils-style', $critical_css );

        // Main JavaScript - only load if file exists
        $js_file = get_template_directory() . '/assets/js/main.js';
        if ( file_exists( $js_file ) ) {
            wp_enqueue_script(
                'ai-outils-main',
                get_template_directory_uri() . '/assets/js/main.js',
                array(),
                AI_OUTILS_VERSION,
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'ai_outils_enqueue_assets', 10 );

/**
 * ============================================================================
 * CONTENT WIDTH
 * ============================================================================
 */

if ( ! isset( $content_width ) ) {
    $content_width = 1280;
}

/**
 * ============================================================================
 * CUSTOM FUNCTIONS
 * ============================================================================
 */

/**
 * Get reading time estimate
 *
 * @param int $post_id Post ID
 * @return string Reading time
 */
function ai_outils_reading_time( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $content = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 ); // Average reading speed: 200 words/min

    return sprintf( _n( '%d min read', '%d min read', $reading_time, 'ai-outils' ), $reading_time );
}

/**
 * Get tool meta data
 *
 * @param int $post_id Post ID
 * @param string $key Meta key
 * @return mixed Meta value
 */
function ai_outils_get_tool_meta( $post_id = null, $key = '' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    // These meta keys should match what the AI Tools plugin uses
    // Adjust as needed based on actual plugin implementation
    $meta_keys = array(
        'pricing_model' => '_ai_tool_pricing_model',
        'website_url'   => '_ai_tool_website_url',
        'video_url'     => '_ai_tool_video_url',
        'verified'      => '_ai_tool_verified',
        'rating'        => '_ai_tool_rating',
        'social_links'  => '_ai_tool_social_links',
    );

    if ( $key && isset( $meta_keys[ $key ] ) ) {
        return get_post_meta( $post_id, $meta_keys[ $key ], true );
    }

    return false;
}

/**
 * Get tool categories as chips
 *
 * @param int $post_id Post ID
 * @param string $class Additional CSS class
 * @return string HTML output
 */
function ai_outils_get_tool_categories( $post_id = null, $class = '' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $terms = get_the_terms( $post_id, AI_OUTILS_TAXONOMY_SLUG );

    if ( ! $terms || is_wp_error( $terms ) ) {
        return '';
    }

    $output = '<div class="tool-categories">';
    foreach ( $terms as $term ) {
        $output .= sprintf(
            '<a href="%s" class="chip chip-primary %s">%s</a>',
            esc_url( get_term_link( $term ) ),
            esc_attr( $class ),
            esc_html( $term->name )
        );
    }
    $output .= '</div>';

    return $output;
}

/**
 * Get similar tools by category
 *
 * @param int $post_id Post ID
 * @param int $limit Number of tools to return
 * @return WP_Query
 */
function ai_outils_get_similar_tools( $post_id = null, $limit = 3 ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $terms = get_the_terms( $post_id, AI_OUTILS_TAXONOMY_SLUG );

    if ( ! $terms || is_wp_error( $terms ) ) {
        return new WP_Query();
    }

    $term_ids = wp_list_pluck( $terms, 'term_id' );

    $args = array(
        'post_type'      => AI_OUTILS_CPT_SLUG,
        'posts_per_page' => $limit,
        'post__not_in'   => array( $post_id ),
        'tax_query'      => array(
            array(
                'taxonomy' => AI_OUTILS_TAXONOMY_SLUG,
                'field'    => 'term_id',
                'terms'    => $term_ids,
            ),
        ),
    );

    return new WP_Query( $args );
}

/**
 * Get featured tools
 *
 * @param int $limit Number of tools to return
 * @return WP_Query
 */
function ai_outils_get_featured_tools( $limit = 6 ) {
    // You can modify this to use a custom meta query for featured tools
    // or query by a specific category
    $args = array(
        'post_type'      => AI_OUTILS_CPT_SLUG,
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    return new WP_Query( $args );
}

/**
 * Get tools count
 *
 * @return int Total number of published tools
 */
function ai_outils_get_tools_count() {
    $count = wp_count_posts( AI_OUTILS_CPT_SLUG );
    return $count->publish ?? 0;
}

/**
 * Get category count with tools
 *
 * @return int Number of categories
 */
function ai_outils_get_categories_count() {
    $terms = get_terms( array(
        'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
        'hide_empty' => true,
    ) );

    return is_array( $terms ) ? count( $terms ) : 0;
}

/**
 * Output star rating
 *
 * @param float $rating Rating value (0-5)
 * @return string HTML output
 */
function ai_outils_star_rating( $rating = 0 ) {
    $rating = floatval( $rating );
    $rating = max( 0, min( 5, $rating ) ); // Clamp between 0 and 5

    $full_stars = floor( $rating );
    $half_star = ( $rating - $full_stars ) >= 0.5 ? 1 : 0;
    $empty_stars = 5 - $full_stars - $half_star;

    $output = '<div class="star-rating" aria-label="' . sprintf( __( 'Rating: %.1f out of 5', 'ai-outils' ), $rating ) . '">';

    // Full stars
    for ( $i = 0; $i < $full_stars; $i++ ) {
        $output .= '<span class="star star-full">★</span>';
    }

    // Half star
    if ( $half_star ) {
        $output .= '<span class="star star-half">★</span>';
    }

    // Empty stars
    for ( $i = 0; $i < $empty_stars; $i++ ) {
        $output .= '<span class="star star-empty">☆</span>';
    }

    $output .= '</div>';

    return $output;
}

/**
 * Get excerpt with custom length
 *
 * @param int $post_id Post ID
 * @param int $length Excerpt length in words
 * @return string Excerpt
 */
function ai_outils_get_excerpt( $post_id = null, $length = 30 ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $excerpt = get_the_excerpt( $post_id );

    if ( ! $excerpt ) {
        $content = get_post_field( 'post_content', $post_id );
        $excerpt = wp_trim_words( strip_shortcodes( $content ), $length );
    }

    return $excerpt;
}

/**
 * Check if user is logged in (for membership features)
 *
 * @return bool
 */
function ai_outils_is_member() {
    // This function can be extended to check membership plugin status
    // For now, it just checks if user is logged in
    return is_user_logged_in();
}

/**
 * Get current user's first name or display name
 *
 * @return string User name
 */
function ai_outils_get_user_name() {
    if ( ! is_user_logged_in() ) {
        return '';
    }

    $current_user = wp_get_current_user();
    $name = $current_user->user_firstname ? $current_user->user_firstname : $current_user->display_name;

    return $name;
}

/**
 * Get user avatar letter (first letter of name)
 *
 * @return string First letter
 */
function ai_outils_get_user_avatar_letter() {
    $name = ai_outils_get_user_name();
    return $name ? strtoupper( substr( $name, 0, 1 ) ) : 'U';
}

/**
 * ============================================================================
 * SCHEMA / JSON-LD FUNCTIONS
 * ============================================================================
 */

/**
 * Output JSON-LD schema for single tool
 *
 * @param int $post_id Post ID
 */
function ai_outils_tool_schema( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    if ( get_post_type( $post_id ) !== AI_OUTILS_CPT_SLUG ) {
        return;
    }

    $title = get_the_title( $post_id );
    $description = ai_outils_get_excerpt( $post_id, 50 );
    $url = ai_outils_get_tool_meta( $post_id, 'website_url' );
    $rating = ai_outils_get_tool_meta( $post_id, 'rating' );

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'SoftwareApplication',
        'name'        => $title,
        'description' => $description,
    );

    if ( $url ) {
        $schema['url'] = $url;
    }

    if ( $rating ) {
        $schema['aggregateRating'] = array(
            '@type'       => 'AggregateRating',
            'ratingValue' => $rating,
            'bestRating'  => '5',
            'worstRating' => '1',
        );
    }

    if ( has_post_thumbnail( $post_id ) ) {
        $schema['image'] = get_the_post_thumbnail_url( $post_id, 'full' );
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
}

/**
 * ============================================================================
 * PAGINATION
 * ============================================================================
 */

/**
 * Custom pagination
 *
 * @param WP_Query $query Optional custom query
 */
function ai_outils_pagination( $query = null ) {
    global $wp_query;

    if ( ! $query ) {
        $query = $wp_query;
    }

    $big = 999999999;

    $paginate_links = paginate_links( array(
        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format'    => '?paged=%#%',
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'total'     => $query->max_num_pages,
        'prev_text' => __( '← Previous', 'ai-outils' ),
        'next_text' => __( 'Next →', 'ai-outils' ),
        'type'      => 'list',
    ) );

    if ( $paginate_links ) {
        echo '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', 'ai-outils' ) . '">';
        echo $paginate_links;
        echo '</nav>';
    }
}

/**
 * ============================================================================
 * SHORTCODES (for embedding in pages)
 * ============================================================================
 */

/**
 * Tools grid shortcode
 * Usage: [tools_grid limit="6" category="ai-agents"]
 */
function ai_outils_tools_grid_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'limit'    => 6,
        'category' => '',
        'orderby'  => 'date',
        'order'    => 'DESC',
    ), $atts );

    $args = array(
        'post_type'      => AI_OUTILS_CPT_SLUG,
        'posts_per_page' => intval( $atts['limit'] ),
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => AI_OUTILS_TAXONOMY_SLUG,
                'field'    => 'slug',
                'terms'    => $atts['category'],
            ),
        );
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        echo '<div class="card-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/tool-card' );
        }
        echo '</div>';
        wp_reset_postdata();
    }

    return ob_get_clean();
}
add_shortcode( 'tools_grid', 'ai_outils_tools_grid_shortcode' );

/**
 * Categories grid shortcode
 * Usage: [categories_grid]
 */
function ai_outils_categories_grid_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'hide_empty' => true,
    ), $atts );

    $terms = get_terms( array(
        'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
        'hide_empty' => (bool) $atts['hide_empty'],
    ) );

    ob_start();

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        echo '<div class="card-grid">';
        foreach ( $terms as $term ) {
            ?>
            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="card category-card">
                <div class="category-icon">📊</div>
                <h3 class="category-name"><?php echo esc_html( $term->name ); ?></h3>
                <p class="category-count"><?php echo esc_html( $term->count ); ?> tools</p>
            </a>
            <?php
        }
        echo '</div>';
    }

    return ob_get_clean();
}
add_shortcode( 'categories_grid', 'ai_outils_categories_grid_shortcode' );

/**
 * ============================================================================
 * PLUGIN INTEGRATION HELPERS
 * ============================================================================
 */

/**
 * Check if AI Tools plugin is active
 *
 * @return bool
 */
function ai_outils_has_tools_plugin() {
    // Adjust this check based on actual plugin
    return post_type_exists( AI_OUTILS_CPT_SLUG );
}

/**
 * Check if AI Membership plugin is active
 *
 * @return bool
 */
function ai_outils_has_membership_plugin() {
    // Adjust this check based on actual plugin
    // This is a placeholder - check for actual plugin function/class
    return function_exists( 'ai_membership_init' ) || class_exists( 'AI_Membership' );
}

/**
 * Display membership plugin dashboard (if available)
 * This function should be called in the members dashboard template
 */
function ai_outils_membership_dashboard() {
    if ( ai_outils_has_membership_plugin() ) {
        // Replace with actual plugin shortcode or function
        // Example: echo do_shortcode( '[ai_membership_dashboard]' );
        echo '<!-- AI Membership plugin dashboard shortcode goes here -->';
    }
}

/**
 * Display membership plugin recommendations (if available)
 */
function ai_outils_membership_recommendations() {
    if ( ai_outils_has_membership_plugin() ) {
        // Replace with actual plugin shortcode or function
        // Example: echo do_shortcode( '[ai_membership_recommendations]' );
        echo '<!-- AI Membership plugin recommendations shortcode goes here -->';
    }
}

/**
 * Display membership plugin saved tools (if available)
 */
function ai_outils_membership_saved_tools() {
    if ( ai_outils_has_membership_plugin() ) {
        // Replace with actual plugin shortcode or function
        // Example: echo do_shortcode( '[ai_membership_saved_tools]' );
        echo '<!-- AI Membership plugin saved tools shortcode goes here -->';
    }
}

/**
 * Display membership plugin profile form (if available)
 */
function ai_outils_membership_profile_form() {
    if ( ai_outils_has_membership_plugin() ) {
        // Replace with actual plugin shortcode or function
        // Example: echo do_shortcode( '[ai_membership_profile]' );
        echo '<!-- AI Membership plugin profile form shortcode goes here -->';
    }
}

/**
 * ============================================================================
 * REGISTER CUSTOM POST TYPE & TAXONOMY
 * ============================================================================
 *
 * Built-in registration for AI Tools CPT and AI Category taxonomy.
 * Only registers if not already registered by a plugin.
 */

/**
 * Register AI Tool Custom Post Type
 */
function ai_outils_register_post_type() {
    // Skip if already registered by plugin
    if ( post_type_exists( AI_OUTILS_CPT_SLUG ) ) {
        return;
    }

    $labels = array(
        'name'                  => _x( 'AI Tools', 'Post Type General Name', 'ai-outils' ),
        'singular_name'         => _x( 'AI Tool', 'Post Type Singular Name', 'ai-outils' ),
        'menu_name'             => __( 'AI Tools', 'ai-outils' ),
        'name_admin_bar'        => __( 'AI Tool', 'ai-outils' ),
        'archives'              => __( 'Tool Archives', 'ai-outils' ),
        'attributes'            => __( 'Tool Attributes', 'ai-outils' ),
        'parent_item_colon'     => __( 'Parent Tool:', 'ai-outils' ),
        'all_items'             => __( 'All Tools', 'ai-outils' ),
        'add_new_item'          => __( 'Add New Tool', 'ai-outils' ),
        'add_new'               => __( 'Add New', 'ai-outils' ),
        'new_item'              => __( 'New Tool', 'ai-outils' ),
        'edit_item'             => __( 'Edit Tool', 'ai-outils' ),
        'update_item'           => __( 'Update Tool', 'ai-outils' ),
        'view_item'             => __( 'View Tool', 'ai-outils' ),
        'view_items'            => __( 'View Tools', 'ai-outils' ),
        'search_items'          => __( 'Search Tool', 'ai-outils' ),
        'not_found'             => __( 'Not found', 'ai-outils' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'ai-outils' ),
        'featured_image'        => __( 'Tool Image', 'ai-outils' ),
        'set_featured_image'    => __( 'Set tool image', 'ai-outils' ),
        'remove_featured_image' => __( 'Remove tool image', 'ai-outils' ),
        'use_featured_image'    => __( 'Use as tool image', 'ai-outils' ),
        'insert_into_item'      => __( 'Insert into tool', 'ai-outils' ),
        'uploaded_to_this_item' => __( 'Uploaded to this tool', 'ai-outils' ),
        'items_list'            => __( 'Tools list', 'ai-outils' ),
        'items_list_navigation' => __( 'Tools list navigation', 'ai-outils' ),
        'filter_items_list'     => __( 'Filter tools list', 'ai-outils' ),
    );

    $args = array(
        'label'               => __( 'AI Tool', 'ai-outils' ),
        'description'         => __( 'AI Tools directory entries', 'ai-outils' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
        'taxonomies'          => array( AI_OUTILS_TAXONOMY_SLUG ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-admin-tools',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => 'tools',
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
        'rewrite'             => array(
            'slug'       => 'tool',
            'with_front' => false,
        ),
    );

    register_post_type( AI_OUTILS_CPT_SLUG, $args );
}
add_action( 'init', 'ai_outils_register_post_type', 0 );

/**
 * Register AI Category Taxonomy
 */
function ai_outils_register_taxonomy() {
    // Skip if already registered by plugin
    if ( taxonomy_exists( AI_OUTILS_TAXONOMY_SLUG ) ) {
        return;
    }

    $labels = array(
        'name'                       => _x( 'AI Categories', 'Taxonomy General Name', 'ai-outils' ),
        'singular_name'              => _x( 'AI Category', 'Taxonomy Singular Name', 'ai-outils' ),
        'menu_name'                  => __( 'Categories', 'ai-outils' ),
        'all_items'                  => __( 'All Categories', 'ai-outils' ),
        'parent_item'                => __( 'Parent Category', 'ai-outils' ),
        'parent_item_colon'          => __( 'Parent Category:', 'ai-outils' ),
        'new_item_name'              => __( 'New Category Name', 'ai-outils' ),
        'add_new_item'               => __( 'Add New Category', 'ai-outils' ),
        'edit_item'                  => __( 'Edit Category', 'ai-outils' ),
        'update_item'                => __( 'Update Category', 'ai-outils' ),
        'view_item'                  => __( 'View Category', 'ai-outils' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'ai-outils' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'ai-outils' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'ai-outils' ),
        'popular_items'              => __( 'Popular Categories', 'ai-outils' ),
        'search_items'               => __( 'Search Categories', 'ai-outils' ),
        'not_found'                  => __( 'Not Found', 'ai-outils' ),
        'no_terms'                   => __( 'No categories', 'ai-outils' ),
        'items_list'                 => __( 'Categories list', 'ai-outils' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'ai-outils' ),
    );

    $args = array(
        'labels'             => $labels,
        'hierarchical'       => true,
        'public'             => true,
        'show_ui'            => true,
        'show_admin_column'  => true,
        'show_in_nav_menus'  => true,
        'show_tagcloud'      => true,
        'show_in_rest'       => true,
        'rewrite'            => array(
            'slug'         => 'ai-category',
            'with_front'   => false,
            'hierarchical' => true,
        ),
    );

    register_taxonomy( AI_OUTILS_TAXONOMY_SLUG, array( AI_OUTILS_CPT_SLUG ), $args );
}
add_action( 'init', 'ai_outils_register_taxonomy', 0 );

/**
 * ============================================================================
 * AI TOOL META BOX
 * ============================================================================
 */

/**
 * Add meta box for AI Tool custom fields
 */
function ai_outils_add_meta_boxes() {
    add_meta_box(
        'ai_tool_details',
        __( 'Tool Details', 'ai-outils' ),
        'ai_outils_meta_box_callback',
        AI_OUTILS_CPT_SLUG,
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ai_outils_add_meta_boxes' );

/**
 * Meta box callback function
 *
 * @param WP_Post $post Current post object.
 */
function ai_outils_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'ai_outils_save_meta', 'ai_outils_meta_nonce' );

    // Get existing values
    $pricing        = get_post_meta( $post->ID, '_ai_tool_pricing', true );
    $affiliate_link = get_post_meta( $post->ID, '_ai_tool_affiliate_link', true );
    $rating         = get_post_meta( $post->ID, '_ai_tool_rating', true );
    $verified       = get_post_meta( $post->ID, '_ai_tool_verified', true );
    $video_url      = get_post_meta( $post->ID, '_ai_tool_video_url', true );
    $social_links   = get_post_meta( $post->ID, '_ai_tool_social_links', true );
    ?>
    <style>
        .ai-tool-meta-row { margin-bottom: 15px; }
        .ai-tool-meta-row label { display: block; font-weight: 600; margin-bottom: 5px; }
        .ai-tool-meta-row input[type="text"],
        .ai-tool-meta-row input[type="url"],
        .ai-tool-meta-row input[type="number"],
        .ai-tool-meta-row textarea,
        .ai-tool-meta-row select { width: 100%; max-width: 500px; }
        .ai-tool-meta-row .description { color: #666; font-style: italic; margin-top: 5px; }
    </style>
    <div class="ai-tool-meta-fields">
        <div class="ai-tool-meta-row">
            <label for="ai_tool_pricing"><?php _e( 'Pricing Model', 'ai-outils' ); ?></label>
            <select name="ai_tool_pricing" id="ai_tool_pricing">
                <option value=""><?php _e( '-- Select --', 'ai-outils' ); ?></option>
                <option value="Free" <?php selected( $pricing, 'Free' ); ?>><?php _e( 'Free', 'ai-outils' ); ?></option>
                <option value="Freemium" <?php selected( $pricing, 'Freemium' ); ?>><?php _e( 'Freemium', 'ai-outils' ); ?></option>
                <option value="Free Trial" <?php selected( $pricing, 'Free Trial' ); ?>><?php _e( 'Free Trial', 'ai-outils' ); ?></option>
                <option value="Paid" <?php selected( $pricing, 'Paid' ); ?>><?php _e( 'Paid', 'ai-outils' ); ?></option>
                <option value="Contact for Pricing" <?php selected( $pricing, 'Contact for Pricing' ); ?>><?php _e( 'Contact for Pricing', 'ai-outils' ); ?></option>
            </select>
        </div>

        <div class="ai-tool-meta-row">
            <label for="ai_tool_affiliate_link"><?php _e( 'Website / Affiliate Link', 'ai-outils' ); ?></label>
            <input type="url" name="ai_tool_affiliate_link" id="ai_tool_affiliate_link" value="<?php echo esc_url( $affiliate_link ); ?>" placeholder="https://example.com">
            <p class="description"><?php _e( 'The main website URL or affiliate link for this tool.', 'ai-outils' ); ?></p>
        </div>

        <div class="ai-tool-meta-row">
            <label for="ai_tool_rating"><?php _e( 'Rating (0-5)', 'ai-outils' ); ?></label>
            <input type="number" name="ai_tool_rating" id="ai_tool_rating" value="<?php echo esc_attr( $rating ); ?>" min="0" max="5" step="0.1" placeholder="4.5">
        </div>

        <div class="ai-tool-meta-row">
            <label for="ai_tool_verified">
                <input type="checkbox" name="ai_tool_verified" id="ai_tool_verified" value="1" <?php checked( $verified, '1' ); ?>>
                <?php _e( 'Verified Tool', 'ai-outils' ); ?>
            </label>
            <p class="description"><?php _e( 'Mark this tool as verified/reviewed.', 'ai-outils' ); ?></p>
        </div>

        <div class="ai-tool-meta-row">
            <label for="ai_tool_video_url"><?php _e( 'Video URL', 'ai-outils' ); ?></label>
            <input type="url" name="ai_tool_video_url" id="ai_tool_video_url" value="<?php echo esc_url( $video_url ); ?>" placeholder="https://youtube.com/watch?v=...">
            <p class="description"><?php _e( 'YouTube or Vimeo video URL for demo/overview.', 'ai-outils' ); ?></p>
        </div>

        <div class="ai-tool-meta-row">
            <label for="ai_tool_social_links"><?php _e( 'Social Links', 'ai-outils' ); ?></label>
            <textarea name="ai_tool_social_links" id="ai_tool_social_links" rows="4" placeholder="Twitter: https://twitter.com/example&#10;LinkedIn: https://linkedin.com/company/example"><?php echo esc_textarea( $social_links ); ?></textarea>
            <p class="description"><?php _e( 'One per line: Platform: URL', 'ai-outils' ); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Save meta box data
 *
 * @param int $post_id Post ID.
 */
function ai_outils_save_meta( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['ai_outils_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ai_outils_meta_nonce'], 'ai_outils_save_meta' ) ) {
        return;
    }

    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save fields
    $fields = array(
        'ai_tool_pricing'        => '_ai_tool_pricing',
        'ai_tool_affiliate_link' => '_ai_tool_affiliate_link',
        'ai_tool_rating'         => '_ai_tool_rating',
        'ai_tool_video_url'      => '_ai_tool_video_url',
        'ai_tool_social_links'   => '_ai_tool_social_links',
    );

    foreach ( $fields as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = sanitize_text_field( $_POST[ $field ] );
            if ( strpos( $meta_key, 'link' ) !== false || strpos( $meta_key, 'url' ) !== false ) {
                $value = esc_url_raw( $_POST[ $field ] );
            }
            update_post_meta( $post_id, $meta_key, $value );
        }
    }

    // Handle checkbox
    $verified = isset( $_POST['ai_tool_verified'] ) ? '1' : '';
    update_post_meta( $post_id, '_ai_tool_verified', $verified );
}
add_action( 'save_post_' . AI_OUTILS_CPT_SLUG, 'ai_outils_save_meta' );

/**
 * Flush rewrite rules on theme activation
 */
function ai_outils_activation() {
    ai_outils_register_post_type();
    ai_outils_register_taxonomy();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'ai_outils_activation' );

/**
 * ============================================================================
 * ADMIN NOTICES
 * ============================================================================
 */

/**
 * Admin notice for theme setup
 */
function ai_outils_admin_notices() {
    // Only show on theme pages
    $screen = get_current_screen();
    if ( ! $screen || strpos( $screen->id, 'theme' ) === false ) {
        return;
    }

    // Check if we have any AI tools
    $tools_count = wp_count_posts( AI_OUTILS_CPT_SLUG );
    if ( isset( $tools_count->publish ) && $tools_count->publish > 0 ) {
        return;
    }
    ?>
    <div class="notice notice-info is-dismissible">
        <p><strong><?php _e( 'AI Outils Theme:', 'ai-outils' ); ?></strong> <?php _e( 'Welcome! To get started, add some AI Tools from the AI Tools menu in the sidebar.', 'ai-outils' ); ?></p>
    </div>
    <?php
}
add_action( 'admin_notices', 'ai_outils_admin_notices' );
