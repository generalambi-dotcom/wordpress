<?php
/**
 * Template part for displaying the tool hero section on single-ai_tool.php
 *
 * This template displays:
 * - Tool logo/image
 * - Title with verified badge
 * - Rating and reviews
 * - Short description/pitch
 * - Categories
 * - Pricing info
 * - Action buttons (Visit Site, Save Tool)
 * - Social links
 * - Video embed or featured image
 *
 * @package AI_Outils
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id = get_the_ID();

// Get tool meta data
$rating       = get_ai_tool_rating( $post_id );
$reviews      = get_ai_tool_reviews( $post_id );
$verified     = is_ai_tool_verified( $post_id );
$pricing      = get_ai_tool_pricing( $post_id );
$website_url  = get_ai_tool_affiliate_link( $post_id );
$video_url    = get_ai_tool_video_url( $post_id );
$social_links = get_ai_tool_social_links( $post_id );
$icon_url     = get_ai_tool_icon( $post_id, 'tool-logo' );
$is_saved     = is_user_logged_in() ? is_tool_saved_by_user( $post_id ) : false;

// Get video embed URL
$video_embed = $video_url ? get_video_embed_url( $video_url ) : false;
?>

<div class="tool-hero">
    <div class="tool-hero-grid">
        <!-- Left Column: Tool Info -->
        <div class="tool-hero-left">
            <?php if ( $icon_url ) : ?>
                <img
                    src="<?php echo esc_url( $icon_url ); ?>"
                    alt="<?php echo esc_attr( get_the_title() ); ?> logo"
                    class="tool-hero-logo"
                    width="100"
                    height="100"
                    loading="eager"
                >
            <?php elseif ( has_post_thumbnail() ) : ?>
                <img
                    src="<?php echo esc_url( get_the_post_thumbnail_url( $post_id, 'tool-logo' ) ); ?>"
                    alt="<?php echo esc_attr( get_the_title() ); ?>"
                    class="tool-hero-logo"
                    width="100"
                    height="100"
                    loading="eager"
                >
            <?php endif; ?>

            <h1 class="tool-hero-title">
                <?php the_title(); ?>
                <?php if ( $verified ) : ?>
                    <span class="badge badge-verified" title="<?php esc_attr_e( 'Verified Tool', 'ai-outils' ); ?>">
                        <span aria-hidden="true">&#10003;</span>
                        <?php esc_html_e( 'Verified', 'ai-outils' ); ?>
                    </span>
                <?php endif; ?>
            </h1>

            <!-- Rating & Reviews -->
            <?php if ( $rating ) : ?>
                <div class="tool-hero-rating">
                    <?php echo display_star_rating( $rating, true ); ?>
                    <?php if ( $reviews ) : ?>
                        <span class="review-count">
                            (<?php printf( _n( '%d review', '%d reviews', $reviews, 'ai-outils' ), $reviews ); ?>)
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Short Pitch / Excerpt -->
            <div class="tool-hero-pitch">
                <?php echo esc_html( ai_outils_get_excerpt( $post_id, 50 ) ); ?>
            </div>

            <!-- Categories -->
            <?php echo ai_outils_get_tool_categories( $post_id ); ?>

            <!-- Pricing Model -->
            <?php if ( $pricing ) : ?>
                <div class="tool-pricing">
                    <span class="pricing-label"><?php esc_html_e( 'Pricing:', 'ai-outils' ); ?></span>
                    <span class="pricing-value"><?php echo esc_html( $pricing ); ?></span>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="tool-hero-actions">
                <?php if ( $website_url ) : ?>
                    <a
                        href="<?php echo esc_url( $website_url ); ?>"
                        target="_blank"
                        rel="noopener noreferrer sponsored"
                        class="btn btn-primary btn-lg visit-site-btn"
                        data-tool-id="<?php echo esc_attr( $post_id ); ?>"
                        aria-label="<?php printf( esc_attr__( 'Visit %s website (opens in new tab)', 'ai-outils' ), get_the_title() ); ?>"
                    >
                        <?php esc_html_e( 'Visit Site', 'ai-outils' ); ?>
                        <span aria-hidden="true"> &rarr;</span>
                    </a>
                <?php endif; ?>

                <?php if ( is_user_logged_in() ) : ?>
                    <button
                        type="button"
                        class="btn btn-outline btn-lg save-tool-btn <?php echo $is_saved ? 'is-saved' : ''; ?>"
                        data-tool-id="<?php echo esc_attr( $post_id ); ?>"
                        aria-pressed="<?php echo $is_saved ? 'true' : 'false'; ?>"
                    >
                        <span class="save-icon" aria-hidden="true"><?php echo $is_saved ? '&#10003;' : '&#9825;'; ?></span>
                        <span class="save-text"><?php echo $is_saved ? esc_html__( 'Saved', 'ai-outils' ) : esc_html__( 'Save Tool', 'ai-outils' ); ?></span>
                    </button>
                <?php else : ?>
                    <a
                        href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>"
                        class="btn btn-outline btn-lg"
                    >
                        <?php esc_html_e( 'Login to Save', 'ai-outils' ); ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Social Links -->
            <?php if ( ! empty( $social_links ) ) : ?>
                <div class="tool-social-links">
                    <span class="social-label"><?php esc_html_e( 'Follow:', 'ai-outils' ); ?></span>
                    <?php foreach ( $social_links as $platform => $url ) : ?>
                        <a
                            href="<?php echo esc_url( $url ); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="social-link social-<?php echo esc_attr( $platform ); ?>"
                            aria-label="<?php printf( esc_attr__( 'Visit %1$s on %2$s (opens in new tab)', 'ai-outils' ), get_the_title(), ucfirst( $platform ) ); ?>"
                        >
                            <?php echo esc_html( ucfirst( $platform ) ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Video or Image -->
        <div class="tool-hero-right">
            <?php if ( $video_embed ) : ?>
                <div class="tool-video">
                    <iframe
                        src="<?php echo esc_url( $video_embed ); ?>"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        loading="lazy"
                        title="<?php printf( esc_attr__( '%s video', 'ai-outils' ), get_the_title() ); ?>"
                    ></iframe>
                </div>
            <?php elseif ( has_post_thumbnail() ) : ?>
                <div class="tool-featured-image">
                    <?php the_post_thumbnail( 'large', array(
                        'loading' => 'lazy',
                        'alt'     => get_the_title(),
                    ) ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
