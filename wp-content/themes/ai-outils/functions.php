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
define( 'AI_OUTILS_VERSION', '1.0.0' );

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
        // Main stylesheet
        wp_enqueue_style(
            'ai-outils-style',
            get_stylesheet_uri(),
            array(),
            AI_OUTILS_VERSION
        );

        // Main JavaScript (if needed)
        wp_enqueue_script(
            'ai-outils-main',
            get_template_directory_uri() . '/assets/js/main.js',
            array(),
            AI_OUTILS_VERSION,
            true
        );

        // Add inline script for mobile menu toggle
        $inline_js = "
            document.addEventListener('DOMContentLoaded', function() {
                const toggle = document.querySelector('.mobile-menu-toggle');
                const nav = document.querySelector('.main-nav');
                if (toggle && nav) {
                    toggle.addEventListener('click', function() {
                        nav.classList.toggle('active');
                    });
                }
            });
        ";
        wp_add_inline_script( 'ai-outils-main', $inline_js );
    }
}
add_action( 'wp_enqueue_scripts', 'ai_outils_enqueue_assets' );

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
 * ADMIN NOTICES
 * ============================================================================
 */

/**
 * Admin notice if required plugins are not active
 */
function ai_outils_admin_notices() {
    if ( ! ai_outils_has_tools_plugin() ) {
        ?>
        <div class="notice notice-warning">
            <p><strong>AI Outils Theme:</strong> The AI Tools plugin is required for this theme to function properly. Please install and activate it.</p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'ai_outils_admin_notices' );
