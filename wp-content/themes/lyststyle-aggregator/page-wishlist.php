<?php
/**
 * Template Name: Wishlist
 * The template for displaying the wishlist page
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main page-wishlist">
    <div class="container">

        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'My Wishlist', 'lyststyle-aggregator' ); ?></h1>
            <p class="page-description"><?php esc_html_e( 'Your saved favorite items', 'lyststyle-aggregator' ); ?></p>
        </header>

        <div id="wishlist-container">
            <div class="wishlist-loading">
                <p><?php esc_html_e( 'Loading your wishlist...', 'lyststyle-aggregator' ); ?></p>
            </div>

            <div id="wishlist-products" class="products-grid" style="display: none;"></div>

            <div id="wishlist-empty" style="display: none;">
                <div class="empty-wishlist">
                    <div class="empty-icon"><?php echo lyststyle_get_icon( 'heart' ); ?></div>
                    <h2><?php esc_html_e( 'Your wishlist is empty', 'lyststyle-aggregator' ); ?></h2>
                    <p><?php esc_html_e( 'Start adding your favorite items by clicking the heart icon on any product.', 'lyststyle-aggregator' ); ?></p>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="btn-primary">
                        <?php esc_html_e( 'Browse Products', 'lyststyle-aggregator' ); ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>

<?php
get_footer();
