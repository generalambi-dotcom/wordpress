<?php
/**
 * Category Icons Mapping
 *
 * Provides icon HTML for AI tool categories.
 * This file can be included or the function can be called directly.
 *
 * Usage:
 * - get_ai_category_icon( $slug ) - returns HTML span with icon
 * - get_ai_category_icon_emoji( $slug ) - returns raw emoji character
 *
 * @package AI_Outils
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get the raw emoji for a category
 *
 * @param string $slug Category slug
 * @return string Emoji character
 */
function get_ai_category_icon_emoji( $slug ) {
    $icons = array(
        // AI & Automation
        'ai-agents'             => '&#129302;', // Robot
        'ai-assistants'         => '&#129302;', // Robot
        'chatbots'              => '&#128172;', // Speech bubble
        'automation'            => '&#9881;',   // Gear

        // Code & Development
        'ai-code-assistants'    => '&#128187;', // Laptop
        'code-assistants'       => '&#128187;', // Laptop
        'development'           => '&#128187;', // Laptop
        'developer-tools'       => '&#128736;', // Wrench

        // Content & Writing
        'content-writing'       => '&#9997;',   // Writing hand
        'writing'               => '&#128221;', // Memo
        'copywriting'           => '&#128221;', // Memo
        'content-creation'      => '&#128221;', // Memo

        // Design & Visual
        'design'                => '&#127912;', // Palette
        '3d-modelling'          => '&#127912;', // Palette
        '3d'                    => '&#127912;', // Palette
        'image-generation'      => '&#128444;', // Frame with picture
        'image-editing'         => '&#128444;', // Frame with picture
        'graphics'              => '&#127912;', // Palette

        // Video & Audio
        'video-editing'         => '&#127916;', // Clapper
        'video'                 => '&#127916;', // Clapper
        'audio'                 => '&#127911;', // Headphones
        'music'                 => '&#127925;', // Musical note
        'voice'                 => '&#127908;', // Microphone
        'text-to-speech'        => '&#127908;', // Microphone

        // Business & Marketing
        'marketing'             => '&#128200;', // Chart up
        'social-media'          => '&#128241;', // Phone
        'seo'                   => '&#128270;', // Magnifying glass
        'sales'                 => '&#128176;', // Money bag
        'customer-service'      => '&#128172;', // Speech bubble
        'email'                 => '&#128231;', // Email

        // Productivity
        'productivity'          => '&#9889;',   // Lightning
        'presentation'          => '&#128202;', // Chart
        'spreadsheets'          => '&#128202;', // Chart
        'documents'             => '&#128196;', // Page
        'notes'                 => '&#128221;', // Memo
        'scheduling'            => '&#128197;', // Calendar

        // Research & Data
        'research'              => '&#128270;', // Magnifying glass
        'data-analysis'         => '&#128202;', // Chart
        'analytics'             => '&#128200;', // Chart up

        // Education & Learning
        'education'             => '&#127891;', // Graduation cap
        'learning'              => '&#127891;', // Graduation cap
        'tutoring'              => '&#128218;', // Books

        // Industry Specific
        'finance'               => '&#128176;', // Money bag
        'healthcare'            => '&#127973;', // Hospital
        'legal'                 => '&#9878;',   // Scales
        'real-estate'           => '&#127968;', // House
        'ecommerce'             => '&#128722;', // Shopping cart
        'hr'                    => '&#128101;', // People

        // Misc
        'gaming'                => '&#127918;', // Controller
        'fun'                   => '&#127881;', // Party popper
        'lifestyle'             => '&#127774;', // Sun
        'translation'           => '&#127760;', // Globe
        'security'              => '&#128274;', // Lock
    );

    $slug = sanitize_title( $slug );

    if ( isset( $icons[ $slug ] ) ) {
        return $icons[ $slug ];
    }

    // Default icon
    return '&#128202;'; // Chart
}

/**
 * Get full category icon HTML
 *
 * @param string $slug Category slug
 * @param string $class Additional CSS class
 * @return string HTML output
 */
function get_ai_category_icon_html( $slug, $class = '' ) {
    $emoji = get_ai_category_icon_emoji( $slug );
    $class = $class ? 'category-emoji ' . esc_attr( $class ) : 'category-emoji';

    return sprintf(
        '<span class="%s" aria-hidden="true">%s</span>',
        $class,
        $emoji
    );
}

/**
 * Display category card with icon
 *
 * @param WP_Term $category Term object
 * @param string $size Size class (small, medium, large)
 */
function display_category_card( $category, $size = 'medium' ) {
    if ( ! $category instanceof WP_Term ) {
        return;
    }
    ?>
    <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="card category-card category-card-<?php echo esc_attr( $size ); ?>">
        <div class="category-icon">
            <?php echo get_ai_category_icon_html( $category->slug ); ?>
        </div>
        <h3 class="category-name"><?php echo esc_html( $category->name ); ?></h3>
        <p class="category-count">
            <?php
            printf(
                _n( '%d Tool', '%d Tools', $category->count, 'ai-outils' ),
                $category->count
            );
            ?>
        </p>
        <?php if ( $category->description && $size === 'large' ) : ?>
            <p class="category-description">
                <?php echo esc_html( wp_trim_words( $category->description, 15 ) ); ?>
            </p>
        <?php endif; ?>
    </a>
    <?php
}

/**
 * Get array of all category icons (for JavaScript use)
 *
 * @return array Slug => emoji mapping
 */
function get_all_category_icons() {
    return array(
        'ai-agents'          => '&#129302;',
        'ai-code-assistants' => '&#128187;',
        'automation'         => '&#9881;',
        'content-writing'    => '&#9997;',
        'presentation'       => '&#128202;',
        'social-media'       => '&#128241;',
        'video-editing'      => '&#127916;',
        'writing'            => '&#128221;',
        '3d-modelling'       => '&#127912;',
        'productivity'       => '&#9889;',
        'design'             => '&#127912;',
        'marketing'          => '&#128200;',
        'research'           => '&#128270;',
        'audio'              => '&#127911;',
        'chatbots'           => '&#128172;',
        'image-generation'   => '&#128444;',
        'data-analysis'      => '&#128202;',
        'education'          => '&#127891;',
        'finance'            => '&#128176;',
        'healthcare'         => '&#127973;',
    );
}
