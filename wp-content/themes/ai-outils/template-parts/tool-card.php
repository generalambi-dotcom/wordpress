<?php
/**
 * Template part for displaying tool cards
 *
 * Used in:
 * - AI Tools archive pages
 * - AI Category taxonomy pages
 * - Directory page
 * - Search results
 * - AJAX responses (via render_tool_card())
 *
 * Important: The structure of this template is expected by AJAX handlers.
 * Changes to class names should be made carefully to avoid breaking JS functionality.
 *
 * @package AI_Outils
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id = get_the_ID();

// Get tool meta using helper functions
$icon_url   = get_ai_tool_icon( $post_id, 'tool-thumbnail' );
$rating     = get_ai_tool_rating( $post_id );
$verified   = is_ai_tool_verified( $post_id );
$pricing    = get_ai_tool_pricing( $post_id );
$save_count = get_ai_tool_save_count( $post_id );

// Check if saved by current user
$is_saved = is_user_logged_in() ? is_tool_saved_by_user( $post_id ) : false;

// Get categories (limit to 3)
$terms = get_the_terms( $post_id, AI_OUTILS_TAXONOMY_SLUG );
?>

<article id="tool-<?php echo esc_attr( $post_id ); ?>" <?php post_class( 'card tool-card' ); ?>>
    <!-- Card Link Wrapper -->
    <a href="<?php the_permalink(); ?>" class="tool-card-link">
        <!-- Tool Header -->
        <div class="tool-card-header">
            <?php if ( $icon_url ) : ?>
                <img
                    src="<?php echo esc_url( $icon_url ); ?>"
                    alt=""
                    class="tool-logo"
                    width="60"
                    height="60"
                    loading="lazy"
                >
            <?php else : ?>
                <div class="tool-logo tool-logo-placeholder">
                    <span aria-hidden="true"><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></span>
                </div>
            <?php endif; ?>

            <div class="tool-card-info">
                <h3 class="tool-card-title"><?php the_title(); ?></h3>

                <!-- Tool Meta (Rating, Verified) -->
                <div class="tool-card-meta">
                    <?php if ( $rating ) : ?>
                        <span class="tool-rating" aria-label="<?php printf( esc_attr__( 'Rating: %s out of 5', 'ai-outils' ), number_format( $rating, 1 ) ); ?>">
                            <span class="star" aria-hidden="true">&#9733;</span>
                            <span class="rating-value"><?php echo esc_html( number_format( $rating, 1 ) ); ?></span>
                        </span>
                    <?php endif; ?>

                    <?php if ( $verified ) : ?>
                        <span class="badge badge-verified">
                            <span aria-hidden="true">&#10003;</span>
                            <span class="badge-text"><?php esc_html_e( 'Verified', 'ai-outils' ); ?></span>
                        </span>
                    <?php endif; ?>

                    <?php if ( $pricing ) : ?>
                        <span class="tool-pricing-badge"><?php echo esc_html( $pricing ); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tool Excerpt -->
        <p class="tool-card-excerpt">
            <?php echo esc_html( ai_outils_get_excerpt( $post_id, 20 ) ); ?>
        </p>

        <!-- Categories (Chips) -->
        <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
            <div class="tool-card-categories">
                <?php
                $count = 0;
                foreach ( $terms as $term ) :
                    if ( $count >= 3 ) break;
                    ?>
                    <span class="chip chip-primary"><?php echo esc_html( $term->name ); ?></span>
                    <?php
                    $count++;
                endforeach;

                if ( count( $terms ) > 3 ) :
                    ?>
                    <span class="chip chip-more">+<?php echo count( $terms ) - 3; ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </a>

    <!-- Card Actions (outside main link to prevent nested interactive elements) -->
    <div class="tool-card-actions">
        <?php if ( is_user_logged_in() ) : ?>
            <button
                type="button"
                class="save-tool-btn <?php echo $is_saved ? 'is-saved' : ''; ?>"
                data-tool-id="<?php echo esc_attr( $post_id ); ?>"
                aria-label="<?php echo $is_saved ? esc_attr__( 'Remove from saved tools', 'ai-outils' ) : esc_attr__( 'Save this tool', 'ai-outils' ); ?>"
                aria-pressed="<?php echo $is_saved ? 'true' : 'false'; ?>"
            >
                <span class="save-icon" aria-hidden="true"><?php echo $is_saved ? '&#9829;' : '&#9825;'; ?></span>
                <span class="sr-only"><?php echo $is_saved ? esc_html__( 'Saved', 'ai-outils' ) : esc_html__( 'Save', 'ai-outils' ); ?></span>
            </button>
        <?php else : ?>
            <a
                href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>"
                class="save-tool-btn"
                aria-label="<?php esc_attr_e( 'Log in to save this tool', 'ai-outils' ); ?>"
            >
                <span class="save-icon" aria-hidden="true">&#9825;</span>
            </a>
        <?php endif; ?>

        <?php if ( $save_count > 0 ) : ?>
            <span class="save-count" data-save-count="<?php echo esc_attr( $post_id ); ?>">
                <?php echo esc_html( $save_count ); ?>
            </span>
        <?php endif; ?>
    </div>
</article>
