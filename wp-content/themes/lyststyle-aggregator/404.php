<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main error-404">
    <div class="container">

        <div class="error-404-content">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( '404', 'lyststyle-aggregator' ); ?></h1>
                <h2 class="page-subtitle"><?php esc_html_e( 'Page Not Found', 'lyststyle-aggregator' ); ?></h2>
                <p class="error-message">
                    <?php esc_html_e( 'Sorry, the page you are looking for doesn&rsquo;t exist or has been moved.', 'lyststyle-aggregator' ); ?>
                </p>
            </header>

            <div class="error-404-search">
                <h3><?php esc_html_e( 'Try searching:', 'lyststyle-aggregator' ); ?></h3>
                <?php get_search_form(); ?>
            </div>

            <div class="error-404-links">
                <h3><?php esc_html_e( 'Quick Links', 'lyststyle-aggregator' ); ?></h3>
                <ul class="helpful-links">
                    <li>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <?php esc_html_e( 'Go to Homepage', 'lyststyle-aggregator' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>">
                            <?php esc_html_e( 'Browse All Products', 'lyststyle-aggregator' ); ?>
                        </a>
                    </li>
                    <?php
                    // Get popular product categories
                    $popular_categories = get_terms(
                        array(
                            'taxonomy'   => 'product_category',
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'number'     => 5,
                            'hide_empty' => true,
                        )
                    );

                    if ( $popular_categories && ! is_wp_error( $popular_categories ) ) :
                        foreach ( $popular_categories as $category ) :
                            ?>
                            <li>
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            </li>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>
        </div>

        <?php
        // Display recent products
        $recent_products = new WP_Query(
            array(
                'post_type'      => 'product',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
            )
        );

        if ( $recent_products->have_posts() ) :
            ?>
            <section class="error-404-products">
                <h2><?php esc_html_e( 'Recent Products', 'lyststyle-aggregator' ); ?></h2>
                <div class="products-grid">
                    <?php
                    while ( $recent_products->have_posts() ) :
                        $recent_products->the_post();
                        get_template_part( 'template-parts/product', 'card' );
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </section>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
