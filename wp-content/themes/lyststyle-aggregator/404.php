<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main error-404 not-found">
    <div class="container">

        <div class="error-404-content">
            <h1 class="page-title"><?php esc_html_e( '404', 'lyststyle-aggregator' ); ?></h1>
            <h2><?php esc_html_e( 'Oops! That page can\'t be found.', 'lyststyle-aggregator' ); ?></h2>
            <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'lyststyle-aggregator' ); ?></p>

            <div class="search-form-wrapper">
                <?php get_search_form(); ?>
            </div>

            <div class="error-404-links">
                <h3><?php esc_html_e( 'Popular Pages', 'lyststyle-aggregator' ); ?></h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'lyststyle-aggregator' ); ?></a></li>
                    <li><a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>"><?php esc_html_e( 'All Products', 'lyststyle-aggregator' ); ?></a></li>
                    <li><a href="<?php echo esc_url( get_post_type_archive_link( 'article' ) ); ?>"><?php esc_html_e( 'Fashion Guides', 'lyststyle-aggregator' ); ?></a></li>
                </ul>
            </div>
        </div>

    </div>
</main>

<?php
get_footer();
