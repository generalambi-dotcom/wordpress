<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package AI_Outils
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="section">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>

                <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header text-center mb-xl">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <?php if ( get_the_excerpt() ) : ?>
                            <div class="page-description" style="max-width: 700px; margin: 1rem auto 0; color: var(--color-text-light); font-size: 1.125rem;">
                                <?php echo get_the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="featured-image" style="max-width: 900px; margin: 0 auto 3rem; border-radius: var(--radius-lg); overflow: hidden;">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content" style="max-width: 800px; margin: 0 auto;">
                        <?php
                        the_content();

                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . __( 'Pages:', 'ai-outils' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>
                </article>

            <?php
            endwhile;
            ?>
        </div>
    </div>
</main>

<?php
get_footer();
