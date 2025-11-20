<?php
/**
 * The template for displaying article archives
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main archive-article">
    <div class="container">

        <?php lyststyle_breadcrumbs(); ?>

        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'Fashion Guides & Articles', 'lyststyle-aggregator' ); ?></h1>
            <p class="page-description"><?php esc_html_e( 'Discover the latest trends, style tips, and fashion inspiration.', 'lyststyle-aggregator' ); ?></p>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="articles-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/article', 'card' );
                endwhile;
                ?>
            </div>

            <?php lyststyle_pagination(); ?>

        <?php else : ?>

            <div class="no-articles-found">
                <h2><?php esc_html_e( 'No articles found', 'lyststyle-aggregator' ); ?></h2>
                <p><?php esc_html_e( 'Check back soon for new fashion guides and inspiration.', 'lyststyle-aggregator' ); ?></p>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
