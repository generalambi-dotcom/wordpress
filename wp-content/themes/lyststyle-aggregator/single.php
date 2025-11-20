<?php
/**
 * The template for displaying single posts
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main single-post">
    <div class="container">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

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
                        <?php if ( has_tag() ) : ?>
                            <span class="tags-links">
                                <?php the_tags( '', ', ' ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail">
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

                <footer class="entry-footer">
                    <?php if ( has_category() || has_tag() ) : ?>
                        <div class="post-taxonomy">
                            <?php if ( has_category() ) : ?>
                                <div class="post-categories">
                                    <strong><?php esc_html_e( 'Categories:', 'lyststyle-aggregator' ); ?></strong>
                                    <?php the_category( ', ' ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( has_tag() ) : ?>
                                <div class="post-tags">
                                    <strong><?php esc_html_e( 'Tags:', 'lyststyle-aggregator' ); ?></strong>
                                    <?php the_tags( '', ', ' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </footer>

            </article>

            <?php
            // Author bio
            if ( get_the_author_meta( 'description' ) ) :
                ?>
                <div class="author-bio">
                    <div class="author-avatar">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
                    </div>
                    <div class="author-info">
                        <h3 class="author-name">
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                                <?php echo esc_html( get_the_author() ); ?>
                            </a>
                        </h3>
                        <div class="author-description">
                            <?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
                        </div>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-link">
                            <?php esc_html_e( 'View all posts', 'lyststyle-aggregator' ); ?> &rarr;
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            // Post navigation
            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'lyststyle-aggregator' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'lyststyle-aggregator' ) . '</span> <span class="nav-title">%title</span>',
                )
            );
            ?>

            <?php
            // Comments
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

    </div>
</main>

<?php
get_footer();
