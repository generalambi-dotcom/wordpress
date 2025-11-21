<?php
/**
 * AI Tools Helper Functions
 *
 * Helper functions for AI Tool custom post type and meta data.
 * These functions are used by templates and AJAX handlers.
 *
 * @package AI_Outils
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ============================================================================
 * META KEY DEFINITIONS
 * ============================================================================
 *
 * These meta keys must match what the AI Tools plugin uses.
 * DO NOT change these keys as they map to existing database data.
 */

// AI Tool meta keys
define( 'AI_TOOL_ICON_ID_KEY', '_ai_tool_icon_id' );
define( 'AI_TOOL_PRICING_KEY', '_ai_tool_pricing' );
define( 'AI_TOOL_AFFILIATE_LINK_KEY', '_ai_tool_affiliate_link' );
define( 'AI_TOOL_RATING_KEY', '_ai_tool_rating' );
define( 'AI_TOOL_REVIEWS_KEY', '_ai_tool_reviews' );
define( 'AI_TOOL_VERIFIED_KEY', '_ai_tool_verified' );
define( 'AI_TOOL_SOCIAL_LINKS_KEY', '_ai_tool_social_links' );
define( 'AI_TOOL_VIDEO_URL_KEY', '_ai_tool_video_url' );
define( 'AI_TOOL_CLICK_COUNT_KEY', '_affiliate_click_count' );
define( 'AI_TOOL_SAVE_COUNT_KEY', '_save_count' );

// Regular post meta keys
define( 'POST_SECOND_FEATURED_IMAGE_KEY', '_second_featured_image' );
define( 'POST_AFFILIATE_LINK_KEY', '_affiliate_link' );
define( 'POST_IS_PREMIUM_KEY', '_is_premium' );

// User meta keys
define( 'USER_SAVED_TOOLS_KEY', 'saved_ai_tools' );

/**
 * ============================================================================
 * AI TOOL META GETTERS
 * ============================================================================
 */

/**
 * Get AI tool icon (logo) URL
 *
 * @param int $post_id Post ID (optional, defaults to current post)
 * @param string $size Image size (default: 'tool-logo')
 * @return string|false Icon URL or false if not set
 */
function get_ai_tool_icon( $post_id = null, $size = 'tool-logo' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    // First try custom icon
    $icon_id = get_post_meta( $post_id, AI_TOOL_ICON_ID_KEY, true );

    if ( $icon_id ) {
        $icon_url = wp_get_attachment_image_url( $icon_id, $size );
        if ( $icon_url ) {
            return $icon_url;
        }
    }

    // Fall back to featured image
    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail_url( $post_id, $size );
    }

    return false;
}

/**
 * Get AI tool pricing model
 *
 * @param int $post_id Post ID (optional)
 * @return string Pricing model or empty string
 */
function get_ai_tool_pricing( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $pricing = get_post_meta( $post_id, AI_TOOL_PRICING_KEY, true );
    return $pricing ? sanitize_text_field( $pricing ) : '';
}

/**
 * Get AI tool affiliate/website link
 *
 * @param int $post_id Post ID (optional)
 * @return string URL or empty string
 */
function get_ai_tool_affiliate_link( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $link = get_post_meta( $post_id, AI_TOOL_AFFILIATE_LINK_KEY, true );
    return $link ? esc_url( $link ) : '';
}

/**
 * Get AI tool rating
 *
 * @param int $post_id Post ID (optional)
 * @return float|false Rating (1-5) or false if not set
 */
function get_ai_tool_rating( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $rating = get_post_meta( $post_id, AI_TOOL_RATING_KEY, true );

    if ( $rating !== '' && $rating !== false ) {
        $rating = floatval( $rating );
        return max( 0, min( 5, $rating ) ); // Clamp between 0 and 5
    }

    return false;
}

/**
 * Get AI tool reviews count
 *
 * @param int $post_id Post ID (optional)
 * @return int Number of reviews
 */
function get_ai_tool_reviews( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $reviews = get_post_meta( $post_id, AI_TOOL_REVIEWS_KEY, true );
    return absint( $reviews );
}

/**
 * Check if AI tool is verified
 *
 * @param int $post_id Post ID (optional)
 * @return bool True if verified
 */
function is_ai_tool_verified( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $verified = get_post_meta( $post_id, AI_TOOL_VERIFIED_KEY, true );
    return (bool) $verified;
}

/**
 * Get AI tool social links
 *
 * @param int $post_id Post ID (optional)
 * @return array Array of platform => URL pairs
 */
function get_ai_tool_social_links( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $social_links = get_post_meta( $post_id, AI_TOOL_SOCIAL_LINKS_KEY, true );

    if ( is_array( $social_links ) ) {
        return $social_links;
    }

    // Parse text format "Platform: URL" (one per line)
    if ( is_string( $social_links ) && ! empty( $social_links ) ) {
        $links = array();
        $lines = explode( "\n", $social_links );

        foreach ( $lines as $line ) {
            $line = trim( $line );
            if ( strpos( $line, ':' ) !== false ) {
                list( $platform, $url ) = explode( ':', $line, 2 );
                $platform = strtolower( trim( $platform ) );
                $url = trim( $url );

                // Handle URLs that might have been split at http:
                if ( strpos( $url, '//' ) === 0 ) {
                    $url = 'https:' . $url;
                } elseif ( strpos( $url, 'http' ) !== 0 ) {
                    $url = 'https://' . ltrim( $url, '/' );
                }

                if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
                    $links[ $platform ] = esc_url( $url );
                }
            }
        }

        return $links;
    }

    return array();
}

/**
 * Get AI tool video URL
 *
 * @param int $post_id Post ID (optional)
 * @return string Video URL or empty string
 */
function get_ai_tool_video_url( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $video_url = get_post_meta( $post_id, AI_TOOL_VIDEO_URL_KEY, true );
    return $video_url ? esc_url( $video_url ) : '';
}

/**
 * Get AI tool click count
 *
 * @param int $post_id Post ID (optional)
 * @return int Click count
 */
function get_ai_tool_click_count( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    return absint( get_post_meta( $post_id, AI_TOOL_CLICK_COUNT_KEY, true ) );
}

/**
 * Get AI tool save count
 *
 * @param int $post_id Post ID (optional)
 * @return int Save count
 */
function get_ai_tool_save_count( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    return absint( get_post_meta( $post_id, AI_TOOL_SAVE_COUNT_KEY, true ) );
}

/**
 * ============================================================================
 * DISPLAY HELPERS
 * ============================================================================
 */

/**
 * Display star rating HTML
 *
 * @param float $rating Rating value (0-5)
 * @param bool $show_value Whether to show numeric value
 * @return string HTML output
 */
function display_star_rating( $rating = 0, $show_value = false ) {
    $rating = floatval( $rating );
    $rating = max( 0, min( 5, $rating ) );

    $full_stars = floor( $rating );
    $half_star = ( $rating - $full_stars ) >= 0.5 ? 1 : 0;
    $empty_stars = 5 - $full_stars - $half_star;

    $output = '<div class="star-rating" aria-label="' . sprintf( esc_attr__( 'Rating: %.1f out of 5', 'ai-outils' ), $rating ) . '">';

    // Full stars
    for ( $i = 0; $i < $full_stars; $i++ ) {
        $output .= '<span class="star star-full" aria-hidden="true">&#9733;</span>';
    }

    // Half star
    if ( $half_star ) {
        $output .= '<span class="star star-half" aria-hidden="true">&#9733;</span>';
    }

    // Empty stars
    for ( $i = 0; $i < $empty_stars; $i++ ) {
        $output .= '<span class="star star-empty" aria-hidden="true">&#9734;</span>';
    }

    if ( $show_value ) {
        $output .= '<span class="star-value">' . number_format( $rating, 1 ) . '</span>';
    }

    $output .= '</div>';

    return $output;
}

/**
 * Get YouTube video embed URL from various YouTube URL formats
 *
 * @param string $url YouTube URL
 * @return string|false Embed URL or false if not a valid YouTube URL
 */
function get_youtube_embed_url( $url ) {
    $video_id = '';

    // Standard watch URL
    if ( preg_match( '/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $matches ) ) {
        $video_id = $matches[1];
    }
    // Short URL
    elseif ( preg_match( '/youtu\.be\/([^\&\?\/]+)/', $url, $matches ) ) {
        $video_id = $matches[1];
    }
    // Embed URL
    elseif ( preg_match( '/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $matches ) ) {
        $video_id = $matches[1];
    }

    if ( $video_id ) {
        return 'https://www.youtube.com/embed/' . esc_attr( $video_id );
    }

    return false;
}

/**
 * Get Vimeo video embed URL
 *
 * @param string $url Vimeo URL
 * @return string|false Embed URL or false if not a valid Vimeo URL
 */
function get_vimeo_embed_url( $url ) {
    if ( preg_match( '/vimeo\.com\/(\d+)/', $url, $matches ) ) {
        return 'https://player.vimeo.com/video/' . esc_attr( $matches[1] );
    }

    return false;
}

/**
 * Get video embed URL (YouTube or Vimeo)
 *
 * @param string $url Video URL
 * @return string|false Embed URL or false
 */
function get_video_embed_url( $url ) {
    // Try YouTube first
    $embed = get_youtube_embed_url( $url );
    if ( $embed ) {
        return $embed;
    }

    // Try Vimeo
    $embed = get_vimeo_embed_url( $url );
    if ( $embed ) {
        return $embed;
    }

    return false;
}

/**
 * ============================================================================
 * USER / SAVED TOOLS FUNCTIONS
 * ============================================================================
 */

/**
 * Get user's saved tools
 *
 * @param int $user_id User ID (optional, defaults to current user)
 * @return array Array of post IDs
 */
function get_user_saved_tools( $user_id = null ) {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    if ( ! $user_id ) {
        return array();
    }

    $saved = get_user_meta( $user_id, USER_SAVED_TOOLS_KEY, true );

    if ( is_array( $saved ) ) {
        return array_map( 'absint', $saved );
    }

    return array();
}

/**
 * Check if tool is saved by user
 *
 * @param int $post_id Tool post ID
 * @param int $user_id User ID (optional)
 * @return bool True if saved
 */
function is_tool_saved_by_user( $post_id, $user_id = null ) {
    $saved_tools = get_user_saved_tools( $user_id );
    return in_array( absint( $post_id ), $saved_tools, true );
}

/**
 * Get saved tools count for user
 *
 * @param int $user_id User ID (optional)
 * @return int Count of saved tools
 */
function get_user_saved_tools_count( $user_id = null ) {
    return count( get_user_saved_tools( $user_id ) );
}

/**
 * ============================================================================
 * CATEGORY HELPERS
 * ============================================================================
 */

/**
 * Get category icon by slug
 *
 * @param string $slug Category slug
 * @return string Icon (emoji or HTML)
 */
function get_ai_category_icon( $slug ) {
    $icons = array(
        // Main categories
        'ai-agents'             => '<span class="category-emoji">&#129302;</span>', // Robot
        'ai-code-assistants'    => '<span class="category-emoji">&#128187;</span>', // Laptop
        'automation'            => '<span class="category-emoji">&#9881;</span>',   // Gear
        'content-writing'       => '<span class="category-emoji">&#9997;</span>',   // Writing hand
        'presentation'          => '<span class="category-emoji">&#128202;</span>', // Chart
        'social-media'          => '<span class="category-emoji">&#128241;</span>', // Phone
        'video-editing'         => '<span class="category-emoji">&#127916;</span>', // Clapper
        'writing'               => '<span class="category-emoji">&#128221;</span>', // Memo
        '3d-modelling'          => '<span class="category-emoji">&#127912;</span>', // Palette
        'productivity'          => '<span class="category-emoji">&#9889;</span>',   // Lightning
        'design'                => '<span class="category-emoji">&#127912;</span>', // Palette
        'marketing'             => '<span class="category-emoji">&#128200;</span>', // Chart up
        'research'              => '<span class="category-emoji">&#128270;</span>', // Magnifying glass
        'audio'                 => '<span class="category-emoji">&#127911;</span>', // Headphones
        'chatbots'              => '<span class="category-emoji">&#128172;</span>', // Speech bubble
        'image-generation'      => '<span class="category-emoji">&#128444;</span>', // Frame with picture
        'data-analysis'         => '<span class="category-emoji">&#128202;</span>', // Chart
        'education'             => '<span class="category-emoji">&#127891;</span>', // Graduation cap
        'finance'               => '<span class="category-emoji">&#128176;</span>', // Money bag
        'healthcare'            => '<span class="category-emoji">&#127973;</span>', // Hospital
    );

    if ( isset( $icons[ $slug ] ) ) {
        return $icons[ $slug ];
    }

    // Default icon
    return '<span class="category-emoji">&#128202;</span>';
}

/**
 * Get excluded category slugs for directory listings
 *
 * @return array Array of category slugs to exclude
 */
function get_excluded_category_slugs() {
    return apply_filters( 'ai_outils_excluded_categories', array(
        'latest-tools',
        'featured',
        'homepage-featured',
        'all-resources',
    ) );
}

/**
 * Get excluded category IDs
 *
 * @return array Array of term IDs
 */
function get_excluded_category_ids() {
    $slugs = get_excluded_category_slugs();
    $ids = array();

    foreach ( $slugs as $slug ) {
        $term = get_term_by( 'slug', $slug, AI_OUTILS_TAXONOMY_SLUG );
        if ( $term ) {
            $ids[] = $term->term_id;
        }
    }

    return $ids;
}

/**
 * ============================================================================
 * RENDER FUNCTIONS (used by AJAX handlers)
 * ============================================================================
 */

/**
 * Render a single tool card
 *
 * Used by AJAX handlers to return consistent HTML.
 * This function outputs the tool card directly or returns it.
 *
 * @param int|WP_Post $post Post ID or object
 * @param bool $return Whether to return HTML instead of echoing
 * @return string|void HTML if $return is true
 */
function render_tool_card( $post = null, $return = false ) {
    if ( $return ) {
        ob_start();
    }

    if ( $post ) {
        $GLOBALS['post'] = is_numeric( $post ) ? get_post( $post ) : $post;
        setup_postdata( $GLOBALS['post'] );
    }

    get_template_part( 'template-parts/tool-card' );

    if ( $post ) {
        wp_reset_postdata();
    }

    if ( $return ) {
        return ob_get_clean();
    }
}

/**
 * Render a blog post card
 *
 * @param int|WP_Post $post Post ID or object
 * @param bool $return Whether to return HTML instead of echoing
 * @return string|void HTML if $return is true
 */
function render_blog_card( $post = null, $return = false ) {
    if ( $return ) {
        ob_start();
    }

    if ( $post ) {
        $GLOBALS['post'] = is_numeric( $post ) ? get_post( $post ) : $post;
        setup_postdata( $GLOBALS['post'] );
    }

    get_template_part( 'template-parts/blog-card' );

    if ( $post ) {
        wp_reset_postdata();
    }

    if ( $return ) {
        return ob_get_clean();
    }
}
