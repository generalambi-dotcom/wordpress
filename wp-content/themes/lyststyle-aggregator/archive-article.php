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

        <!-- Hero Area -->
        <div class="hero-area">
            <h1 class="hero-title"><?php esc_html_e( 'Guides & Stories', 'lyststyle-aggregator' ); ?></h1>
            <p class="hero-description"><?php esc_html_e( 'Discover the latest trends, style tips, and fashion inspiration.', 'lyststyle-aggregator' ); ?></p>
        </div>

        <?php if ( have_posts() ) : ?>

            <div class="articles-header">
                <p class="articles-count">
                    <?php
                    global $wp_query;
                    $total = $wp_query->found_posts;
                    printf(
                        esc_html( _n( '%s article found', '%s articles found', $total, 'lyststyle-aggregator' ) ),
                        '<strong>' . number_format_i18n( $total ) . '</strong>'
                    );
                    ?>
                </p>
            </div>

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
