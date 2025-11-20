<?php
/**
 * The template for displaying search results pages
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main search-results">
    <div class="container">

        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__( 'Search Results for: %s', 'lyststyle-aggregator' ),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
        </header>

        <?php if ( have_posts() ) : ?>

            <?php
            // Separate products from other post types
            $products = array();
            $others   = array();

            while ( have_posts() ) :
                the_post();
                if ( get_post_type() === 'product' ) {
                    $products[] = $GLOBALS['post'];
                } else {
                    $others[] = $GLOBALS['post'];
                }
            endwhile;

            // Display products first
            if ( ! empty( $products ) ) :
                ?>
                <section class="search-products">
                    <h2 class="section-title"><?php esc_html_e( 'Products', 'lyststyle-aggregator' ); ?></h2>
                    <div class="products-grid">
                        <?php
                        foreach ( $products as $post ) :
                            setup_postdata( $post );
                            get_template_part( 'template-parts/product', 'card' );
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php
            // Display other content
            if ( ! empty( $others ) ) :
                ?>
                <section class="search-content">
                    <h2 class="section-title"><?php esc_html_e( 'Articles & Pages', 'lyststyle-aggregator' ); ?></h2>
                    <div class="posts-list">
                        <?php
                        foreach ( $others as $post ) :
                            setup_postdata( $post );
                            ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <header class="entry-header">
                                    <h3 class="entry-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="entry-meta">
                                        <span class="post-type"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
                                        <span class="posted-on"><?php echo esc_html( get_the_date() ); ?></span>
                                    </div>
                                </header>

                                <div class="entry-summary">
                                    <?php the_excerpt(); ?>
                                </div>
                            </article>
                            <?php
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php lyststyle_pagination(); ?>

        <?php else : ?>

            <div class="no-results">
                <h2><?php esc_html_e( 'Nothing Found', 'lyststyle-aggregator' ); ?></h2>
                <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'lyststyle-aggregator' ); ?></p>

                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
