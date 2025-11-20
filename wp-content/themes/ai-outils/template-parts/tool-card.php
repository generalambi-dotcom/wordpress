<?php
/**
 * Template part for displaying tool cards
 *
 * @package AI_Outils
 */
?>

<article id="tool-<?php the_ID(); ?>" <?php post_class( 'card tool-card' ); ?>>
    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit; display: block;">
        <!-- Tool Header -->
        <div class="tool-card-header">
            <?php if ( has_post_thumbnail() ) : ?>
                <img
                    src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'tool-thumbnail' ) ); ?>"
                    alt="<?php echo esc_attr( get_the_title() ); ?>"
                    class="tool-logo"
                    loading="lazy"
                >
            <?php else : ?>
                <div class="tool-logo" style="background: var(--color-primary-light); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                    <?php echo esc_html( substr( get_the_title(), 0, 1 ) ); ?>
                </div>
            <?php endif; ?>

            <div style="flex: 1;">
                <h3 class="tool-card-title"><?php the_title(); ?></h3>

                <!-- Tool Meta -->
                <div class="tool-card-meta">
                    <?php
                    $rating = ai_outils_get_tool_meta( get_the_ID(), 'rating' );
                    $verified = ai_outils_get_tool_meta( get_the_ID(), 'verified' );

                    if ( $rating ) :
                        ?>
                        <span>
                            <span style="color: #FBBF24;">★</span>
                            <?php echo esc_html( number_format( $rating, 1 ) ); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( $verified ) : ?>
                        <span class="badge badge-verified" style="font-size: 0.75rem;">
                            ✓ Verified
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tool Excerpt -->
        <p class="tool-card-excerpt">
            <?php echo esc_html( ai_outils_get_excerpt( get_the_ID(), 25 ) ); ?>
        </p>

        <!-- Categories -->
        <?php
        $terms = get_the_terms( get_the_ID(), AI_OUTILS_TAXONOMY_SLUG );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
            ?>
            <div style="margin-top: auto;">
                <?php
                $count = 0;
                foreach ( $terms as $term ) :
                    if ( $count >= 3 ) break; // Limit to 3 chips
                    ?>
                    <span class="chip chip-primary">
                        <?php echo esc_html( $term->name ); ?>
                    </span>
                    <?php
                    $count++;
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </a>
</article>
