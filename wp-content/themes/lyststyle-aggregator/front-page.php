<?php
/**
 * The template for displaying the front page
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main front-page">

    <?php
    // Hero Banner
    get_template_part( 'template-parts/hero', 'banner' );
    ?>

    <!-- Editor's Picks Section -->
    <section class="editors-picks-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e( 'Editor\'s Picks', 'lyststyle-aggregator' ); ?></h2>
                <p class="section-subtitle"><?php esc_html_e( 'Your shortcut to what\'s trending now.', 'lyststyle-aggregator' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/products' ) ); ?>" class="section-link">
                    <?php esc_html_e( 'See all', 'lyststyle-aggregator' ); ?>
                </a>
            </div>

            <?php
            $editors_pick_tag = get_theme_mod( 'lyststyle_editors_picks_tag', 'editors-pick' );

            $editors_picks_args = array(
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'post_status'    => 'publish',
            );

            if ( $editors_pick_tag ) {
                $editors_picks_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_tag',
                        'field'    => 'slug',
                        'terms'    => $editors_pick_tag,
                    ),
                );
            }

            $editors_picks = new WP_Query( $editors_picks_args );

            if ( $editors_picks->have_posts() ) :
                ?>
                <div class="products-carousel">
                    <div class="products-grid editors-grid">
                        <?php
                        while ( $editors_picks->have_posts() ) :
                            $editors_picks->the_post();
                            get_template_part( 'template-parts/product', 'card' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Featured Editorial Sections -->
    <section class="featured-articles-section section-padding bg-light">
        <div class="container-full">
            <div class="featured-articles-grid">
                <?php
                $featured_articles = new WP_Query( array(
                    'post_type'      => 'article',
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );

                if ( $featured_articles->have_posts() ) :
                    while ( $featured_articles->have_posts() ) :
                        $featured_articles->the_post();
                        ?>
                        <div class="featured-article-large">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="article-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'article-card' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="article-content">
                                <h2 class="article-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <?php if ( has_excerpt() ) : ?>
                                    <p class="article-excerpt"><?php the_excerpt(); ?></p>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="btn-explore">
                                    <?php esc_html_e( 'Explore now', 'lyststyle-aggregator' ); ?>
                                </a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Trending Products -->
    <section class="trending-products-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e( 'Trending Now', 'lyststyle-aggregator' ); ?></h2>
                <a href="<?php echo esc_url( home_url( '/products' ) ); ?>" class="section-link">
                    <?php esc_html_e( 'See all', 'lyststyle-aggregator' ); ?>
                </a>
            </div>

            <?php
            $trending_products = new WP_Query( array(
                'post_type'      => 'product',
                'posts_per_page' => 8,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );

            if ( $trending_products->have_posts() ) :
                ?>
                <div class="products-grid">
                    <?php
                    while ( $trending_products->have_posts() ) :
                        $trending_products->the_post();
                        get_template_part( 'template-parts/product', 'card' );
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Trending Brands -->
    <section class="trending-brands-section section-padding bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e( 'Trending Brands', 'lyststyle-aggregator' ); ?></h2>
            </div>

            <?php
            $brands = get_terms( array(
                'taxonomy'   => 'brand',
                'hide_empty' => true,
                'number'     => 12,
                'orderby'    => 'count',
                'order'      => 'DESC',
            ) );

            if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) :
                ?>
                <div class="brands-grid">
                    <?php foreach ( $brands as $brand ) : ?>
                        <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" class="brand-card">
                            <h3 class="brand-name"><?php echo esc_html( $brand->name ); ?></h3>
                            <span class="brand-count"><?php echo esc_html( $brand->count ); ?> <?php esc_html_e( 'products', 'lyststyle-aggregator' ); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php
get_footer();
