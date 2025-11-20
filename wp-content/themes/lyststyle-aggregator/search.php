<?php
/**
 * The template for displaying search results
 *
 * @package Lyststyle_Aggregator
 */

get_header();

$search_query = get_search_query();
?>

<main id="primary" class="site-main search-results">
    <div class="container">

        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    /* translators: %s: search query */
                    esc_html__( 'Search Results for: %s', 'lyststyle-aggregator' ),
                    '<span>' . esc_html( $search_query ) . '</span>'
                );
                ?>
            </h1>
        </header>

        <?php
        // Query 1: Search Products
        $products_query = new WP_Query(
            array(
                'post_type'      => 'product',
                's'              => $search_query,
                'posts_per_page' => 24,
                'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
            )
        );

        if ( $products_query->have_posts() ) :
            ?>

            <section class="search-section search-products">
                <div class="section-header">
                    <h2 class="section-title"><?php esc_html_e( 'Products', 'lyststyle-aggregator' ); ?></h2>
                    <p class="results-count">
                        <?php
                        printf(
                            /* translators: %s: number of products found */
                            esc_html( _n( '%s product found', '%s products found', $products_query->found_posts, 'lyststyle-aggregator' ) ),
                            '<strong>' . esc_html( number_format_i18n( $products_query->found_posts ) ) . '</strong>'
                        );
                        ?>
                    </p>
                </div>

                <?php get_template_part( 'template-parts/filters', 'bar' ); ?>

                <div class="products-grid">
                    <?php
                    while ( $products_query->have_posts() ) :
                        $products_query->the_post();
                        get_template_part( 'template-parts/product', 'card' );
                    endwhile;
                    ?>
                </div>

                <?php
                // Pagination for products
                $big = 999999999;
                echo '<div class="pagination">';
                echo paginate_links(
                    array(
                        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format'    => '?paged=%#%',
                        'current'   => max( 1, get_query_var( 'paged' ) ),
                        'total'     => $products_query->max_num_pages,
                        'prev_text' => '&laquo; ' . esc_html__( 'Previous', 'lyststyle-aggregator' ),
                        'next_text' => esc_html__( 'Next', 'lyststyle-aggregator' ) . ' &raquo;',
                    )
                );
                echo '</div>';
                ?>

            </section>

        <?php
        endif;
        wp_reset_postdata();
        ?>

        <?php
        // Query 2: Search Articles
        $articles_query = new WP_Query(
            array(
                'post_type'      => 'article',
                's'              => $search_query,
                'posts_per_page' => 12,
            )
        );

        if ( $articles_query->have_posts() ) :
            ?>

            <section class="search-section search-articles">
                <div class="section-header">
                    <h2 class="section-title"><?php esc_html_e( 'Articles & Guides', 'lyststyle-aggregator' ); ?></h2>
                    <p class="results-count">
                        <?php
                        printf(
                            /* translators: %s: number of articles found */
                            esc_html( _n( '%s article found', '%s articles found', $articles_query->found_posts, 'lyststyle-aggregator' ) ),
                            '<strong>' . esc_html( number_format_i18n( $articles_query->found_posts ) ) . '</strong>'
                        );
                        ?>
                    </p>
                </div>

                <div class="articles-grid">
                    <?php
                    while ( $articles_query->have_posts() ) :
                        $articles_query->the_post();
                        get_template_part( 'template-parts/article', 'card' );
                    endwhile;
                    ?>
                </div>
            </section>

        <?php
        endif;
        wp_reset_postdata();
        ?>

        <?php
        // No results found
        if ( ! $products_query->have_posts() && ! $articles_query->have_posts() ) :
            ?>

            <div class="no-results">
                <h2><?php esc_html_e( 'Nothing Found', 'lyststyle-aggregator' ); ?></h2>
                <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'lyststyle-aggregator' ); ?></p>

                <div class="search-suggestions">
                    <h3><?php esc_html_e( 'Search Suggestions:', 'lyststyle-aggregator' ); ?></h3>
                    <ul>
                        <li><?php esc_html_e( 'Check your spelling', 'lyststyle-aggregator' ); ?></li>
                        <li><?php esc_html_e( 'Try more general keywords', 'lyststyle-aggregator' ); ?></li>
                        <li><?php esc_html_e( 'Try different keywords', 'lyststyle-aggregator' ); ?></li>
                    </ul>
                </div>

                <div class="search-try-again">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="search-input-wrapper">
                            <input
                                type="search"
                                class="search-field"
                                placeholder="<?php echo esc_attr_x( 'Try another search...', 'placeholder', 'lyststyle-aggregator' ); ?>"
                                value=""
                                name="s"
                            />
                            <button type="submit" class="search-submit">
                                <?php esc_html_e( 'Search', 'lyststyle-aggregator' ); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
