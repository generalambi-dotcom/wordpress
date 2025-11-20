<?php
/**
 * The template for displaying all single posts
 *
 * @package Lyststyle_Aggregator
 */

get_header();

while ( have_posts() ) :
    the_post();
    ?>

    <main id="primary" class="site-main">
        <div class="container">

            <?php lyststyle_breadcrumbs(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <?php if ( 'post' === get_post_type() ) : ?>
                        <div class="entry-meta">
                            <span class="posted-on"><?php echo esc_html( get_the_date() ); ?></span>
                            <span class="byline">
                                <?php
                                printf(
                                    esc_html__( 'by %s', 'lyststyle-aggregator' ),
                                    '<span class="author">' . esc_html( get_the_author() ) . '</span>'
                                );
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="entry-featured-image">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lyststyle-aggregator' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <?php if ( get_the_tags() ) : ?>
                    <footer class="entry-footer">
                        <div class="entry-tags">
                            <?php the_tags( '<span class="tags-label">' . esc_html__( 'Tags:', 'lyststyle-aggregator' ) . '</span> ', ', ' ); ?>
                        </div>
                    </footer>
                <?php endif; ?>

            </article>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        </div>
    </main>

<?php
endwhile;

get_footer();
