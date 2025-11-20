<?php
/**
 * Template part for displaying blog post cards
 *
 * @package AI_Outils
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card blog-card' ); ?>>
    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit; display: block;">
        <!-- Featured Image -->
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="blog-card-image">
                <?php the_post_thumbnail( 'blog-featured' ); ?>
            </div>
        <?php endif; ?>

        <!-- Category -->
        <?php
        $categories = get_the_category();
        if ( ! empty( $categories ) ) :
            ?>
            <span class="blog-card-category">
                <?php echo esc_html( $categories[0]->name ); ?>
            </span>
        <?php endif; ?>

        <!-- Title -->
        <h3 class="blog-card-title">
            <?php the_title(); ?>
        </h3>

        <!-- Excerpt -->
        <div class="blog-card-excerpt">
            <?php echo esc_html( ai_outils_get_excerpt( get_the_ID(), 20 ) ); ?>
        </div>

        <!-- Meta -->
        <div class="blog-card-meta">
            <span>
                <?php the_author(); ?>
            </span>
            <span>&middot;</span>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                <?php echo get_the_date(); ?>
            </time>
            <span>&middot;</span>
            <span><?php echo ai_outils_reading_time(); ?></span>
        </div>
    </a>
</article>
