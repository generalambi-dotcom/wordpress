<?php
/**
 * The template for displaying all pages
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main page-template">
    <div class="container">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="page-thumbnail">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lyststyle-aggregator' ),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div>

            </article>

            <?php
            // Comments (if enabled on page)
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

    </div>
</main>

<?php
get_footer();
