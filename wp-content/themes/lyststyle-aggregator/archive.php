<?php
/**
 * The template for displaying archive pages
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main archive">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <?php
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
                ?>
            </header>

            <div class="archive-results-info">
                <p class="results-count">
                    <?php
                    global $wp_query;
                    printf(
                        /* translators: %s: number of posts */
                        esc_html( _n( 'Showing %s post', 'Showing %s posts', $wp_query->found_posts, 'lyststyle-aggregator' ) ),
                        '<strong>' . esc_html( number_format_i18n( $wp_query->found_posts ) ) . '</strong>'
                    );
                    ?>
                </p>
            </div>

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
                            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
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
                                        <?php the_category( ', ' ); ?>
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
                <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'lyststyle-aggregator' ); ?></p>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
