<?php
/**
 * The main template file (fallback)
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">

        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>

            <div class="posts-list">

                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'large' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <header class="entry-header">
                            <?php
                            if ( is_singular() ) :
                                the_title( '<h1 class="entry-title">', '</h1>' );
                            else :
                                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                            endif;
                            ?>

                            <div class="entry-meta">
                                <span class="posted-on">
                                    <time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                        <?php echo esc_html( get_the_date() ); ?>
                                    </time>
                                </span>
                                <span class="byline">
                                    <?php
                                    printf(
                                        /* translators: %s: post author */
                                        esc_html__( 'by %s', 'lyststyle-aggregator' ),
                                        '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
                                    );
                                    ?>
                                </span>
                                <?php if ( has_category() ) : ?>
                                    <span class="cat-links">
                                        <?php esc_html_e( 'in', 'lyststyle-aggregator' ); ?> <?php the_category( ', ' ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>

                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>

                        <footer class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php esc_html_e( 'Read More', 'lyststyle-aggregator' ); ?> &rarr;
                            </a>
                        </footer>

                    </article>

                <?php endwhile; ?>

            </div>

            <?php
            // Pagination
            the_posts_pagination(
                array(
                    'mid_size'  => 2,
                    'prev_text' => '&laquo; ' . esc_html__( 'Previous', 'lyststyle-aggregator' ),
                    'next_text' => esc_html__( 'Next', 'lyststyle-aggregator' ) . ' &raquo;',
                )
            );
            ?>

        <?php else : ?>

            <div class="no-content">
                <h1><?php esc_html_e( 'Nothing Found', 'lyststyle-aggregator' ); ?></h1>
                <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'lyststyle-aggregator' ); ?></p>

                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
