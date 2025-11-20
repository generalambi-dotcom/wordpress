<?php
/**
 * The main template file
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php bloginfo( 'name' ); ?></h1>
                <p class="page-description"><?php bloginfo( 'description' ); ?></p>
            </header>

            <div class="posts-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'medium' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <header class="entry-header">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="entry-meta">
                                <span class="posted-on"><?php echo esc_html( get_the_date() ); ?></span>
                            </div>
                        </header>

                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>

                        <footer class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php esc_html_e( 'Read more', 'lyststyle-aggregator' ); ?>
                                <?php echo lyststyle_get_icon( 'arrow-right' ); ?>
                            </a>
                        </footer>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>

            <?php lyststyle_pagination(); ?>

        <?php else : ?>

            <div class="no-content">
                <h2><?php esc_html_e( 'Nothing Found', 'lyststyle-aggregator' ); ?></h2>
                <p><?php esc_html_e( 'It looks like nothing was found at this location.', 'lyststyle-aggregator' ); ?></p>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
