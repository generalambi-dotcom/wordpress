<?php
/**
 * Template part for displaying article cards
 *
 * @package Lyststyle_Aggregator
 */

$article_id = get_the_ID();
$image_url  = get_the_post_thumbnail_url( $article_id, 'article-card' );
?>

<article id="article-<?php echo esc_attr( $article_id ); ?>" <?php post_class( 'article-card' ); ?>>
    <div class="article-card-inner">
        <?php if ( $image_url ) : ?>
            <div class="article-image">
                <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                </a>
            </div>
        <?php endif; ?>

        <div class="article-content">
            <?php if ( has_term( '', 'category' ) ) : ?>
                <div class="article-category">
                    <?php
                    $categories = get_the_category();
                    if ( $categories ) {
                        echo '<span class="category-label">' . esc_html( $categories[0]->name ) . '</span>';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <h3 class="article-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <?php if ( has_excerpt() ) : ?>
                <div class="article-excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>

            <a href="<?php the_permalink(); ?>" class="article-link">
                <?php esc_html_e( 'Explore now', 'lyststyle-aggregator' ); ?>
                <?php echo lyststyle_get_icon( 'arrow-right' ); ?>
            </a>
        </div>
    </div>
</article>
