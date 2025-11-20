<?php
/**
 * The template for displaying the front page
 * Fully customizable homepage with sections controlled via Customizer
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main front-page">

    <?php
    /**
     * Hero Banner Section
     * Displays a full-width banner with customizable content
     */
    $hero_title           = get_theme_mod( 'hero_title', 'The Intelligent Gift Guide' );
    $hero_subtitle        = get_theme_mod( 'hero_subtitle', 'Discover the ultimate wishlist for discerning fashion fans.' );
    $hero_sponsored_by    = get_theme_mod( 'hero_sponsored_by', '' );
    $hero_background_image = get_theme_mod( 'hero_background_image', '' );
    $hero_cta_label       = get_theme_mod( 'hero_cta_label', 'Explore now' );
    $hero_cta_url         = get_theme_mod( 'hero_cta_url', '#' );
    ?>

    <section class="hero-banner" <?php if ( $hero_background_image ) : ?>style="background-image: url('<?php echo esc_url( $hero_background_image ); ?>');"<?php endif; ?>>
        <div class="hero-container">
            <div class="hero-content">
                <?php if ( $hero_sponsored_by ) : ?>
                    <div class="hero-label">
                        <span><?php echo esc_html( $hero_sponsored_by ); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ( $hero_title ) : ?>
                    <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
                <?php endif; ?>

                <?php if ( $hero_subtitle ) : ?>
                    <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
                <?php endif; ?>

                <?php if ( $hero_cta_label && $hero_cta_url ) : ?>
                    <a href="<?php echo esc_url( $hero_cta_url ); ?>" class="hero-btn btn-primary">
                        <?php echo esc_html( $hero_cta_label ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php
    /**
     * Editor's Picks Section
     * Displays curated products based on tag or specific product IDs
     */
    if ( get_theme_mod( 'show_editors_picks', true ) ) :
        $editors_picks_title    = get_theme_mod( 'editors_picks_title', "Editor's Picks" );
        $editors_picks_subtitle = get_theme_mod( 'editors_picks_subtitle', "Your shortcut to what's trending now." );
        $editors_picks_tag      = get_theme_mod( 'editors_picks_tag', '' );
        $editors_picks_products = get_theme_mod( 'editors_picks_products', '' );
        $editors_picks_count    = get_theme_mod( 'editors_picks_count', 4 );

        // Build query arguments
        $editors_picks_args = array(
            'post_type'      => 'product',
            'posts_per_page' => absint( $editors_picks_count ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        // If specific product IDs are provided, use them
        if ( ! empty( $editors_picks_products ) ) {
            $product_ids = array_map( 'absint', explode( ',', $editors_picks_products ) );
            $product_ids = array_filter( $product_ids ); // Remove empty values

            if ( ! empty( $product_ids ) ) {
                $editors_picks_args['post__in'] = $product_ids;
                $editors_picks_args['orderby']  = 'post__in';
            }
        }
        // Otherwise, query by tag if set
        elseif ( ! empty( $editors_picks_tag ) ) {
            $editors_picks_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $editors_picks_tag ),
                ),
            );
        }

        $editors_picks = new WP_Query( $editors_picks_args );

        if ( $editors_picks->have_posts() ) :
            ?>
            <section class="editors-picks-section section-padding">
                <div class="container">
                    <div class="section-header">
                        <?php if ( $editors_picks_title ) : ?>
                            <h2 class="section-title"><?php echo esc_html( $editors_picks_title ); ?></h2>
                        <?php endif; ?>

                        <?php if ( $editors_picks_subtitle ) : ?>
                            <p class="section-subtitle"><?php echo esc_html( $editors_picks_subtitle ); ?></p>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="section-link">
                            <?php esc_html_e( 'See all', 'lyststyle-aggregator' ); ?>
                        </a>
                    </div>

                    <div class="products-grid editors-grid">
                        <?php
                        while ( $editors_picks->have_posts() ) :
                            $editors_picks->the_post();
                            get_template_part( 'template-parts/product-card' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
    endif;
    ?>

    <?php
    /**
     * Trending Now Section
     * Displays recent products
     */
    if ( get_theme_mod( 'show_trending_now', true ) ) :
        $trending_now_title    = get_theme_mod( 'trending_now_title', 'Trending Now' );
        $trending_now_subtitle = get_theme_mod( 'trending_now_subtitle', '' );
        $trending_now_count    = get_theme_mod( 'trending_now_count', 8 );

        $trending_products = new WP_Query( array(
            'post_type'      => 'product',
            'posts_per_page' => absint( $trending_now_count ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $trending_products->have_posts() ) :
            ?>
            <section class="trending-products-section section-padding">
                <div class="container">
                    <div class="section-header">
                        <?php if ( $trending_now_title ) : ?>
                            <h2 class="section-title"><?php echo esc_html( $trending_now_title ); ?></h2>
                        <?php endif; ?>

                        <?php if ( $trending_now_subtitle ) : ?>
                            <p class="section-subtitle"><?php echo esc_html( $trending_now_subtitle ); ?></p>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="section-link">
                            <?php esc_html_e( 'See all', 'lyststyle-aggregator' ); ?>
                        </a>
                    </div>

                    <div class="products-grid">
                        <?php
                        while ( $trending_products->have_posts() ) :
                            $trending_products->the_post();
                            get_template_part( 'template-parts/product-card' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
    endif;
    ?>

    <?php
    /**
     * Trending Brands Section
     * Displays popular brands from the brand taxonomy
     */
    if ( get_theme_mod( 'show_trending_brands', true ) ) :
        $trending_brands_title    = get_theme_mod( 'trending_brands_title', 'Trending Brands' );
        $trending_brands_subtitle = get_theme_mod( 'trending_brands_subtitle', '' );
        $trending_brands_count    = get_theme_mod( 'trending_brands_count', 12 );

        $brands = get_terms( array(
            'taxonomy'   => 'brand',
            'hide_empty' => true,
            'number'     => absint( $trending_brands_count ),
            'orderby'    => 'count',
            'order'      => 'DESC',
        ) );

        if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) :
            ?>
            <section class="trending-brands-section section-padding bg-light">
                <div class="container">
                    <div class="section-header">
                        <?php if ( $trending_brands_title ) : ?>
                            <h2 class="section-title"><?php echo esc_html( $trending_brands_title ); ?></h2>
                        <?php endif; ?>

                        <?php if ( $trending_brands_subtitle ) : ?>
                            <p class="section-subtitle"><?php echo esc_html( $trending_brands_subtitle ); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="brands-grid">
                        <?php foreach ( $brands as $brand ) :
                            $brand_logo = get_term_meta( $brand->term_id, 'brand_logo', true );
                            ?>
                            <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" class="brand-card">
                                <?php if ( $brand_logo ) : ?>
                                    <div class="brand-logo">
                                        <img src="<?php echo esc_url( $brand_logo ); ?>" alt="<?php echo esc_attr( $brand->name ); ?>" loading="lazy">
                                    </div>
                                <?php endif; ?>

                                <h3 class="brand-name"><?php echo esc_html( $brand->name ); ?></h3>

                                <span class="brand-count">
                                    <?php
                                    printf(
                                        /* translators: %s: number of products */
                                        esc_html( _n( '%s product', '%s products', $brand->count, 'lyststyle-aggregator' ) ),
                                        esc_html( number_format_i18n( $brand->count ) )
                                    );
                                    ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
    endif;
    ?>

    <?php
    /**
     * Luxury Deals Section
     * Displays products tagged with luxury deals
     */
    if ( get_theme_mod( 'show_luxury_deals', false ) ) :
        $luxury_deals_title    = get_theme_mod( 'luxury_deals_title', 'Luxury Deals' );
        $luxury_deals_subtitle = get_theme_mod( 'luxury_deals_subtitle', 'Premium products at exclusive prices.' );
        $luxury_deals_tag      = get_theme_mod( 'luxury_deals_tag', 'luxury-deals' );
        $luxury_deals_count    = get_theme_mod( 'luxury_deals_count', 6 );

        $luxury_deals_args = array(
            'post_type'      => 'product',
            'posts_per_page' => absint( $luxury_deals_count ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( ! empty( $luxury_deals_tag ) ) {
            $luxury_deals_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $luxury_deals_tag ),
                ),
            );
        }

        $luxury_deals = new WP_Query( $luxury_deals_args );

        if ( $luxury_deals->have_posts() ) :
            ?>
            <section class="luxury-deals-section section-padding bg-light">
                <div class="container">
                    <div class="section-header">
                        <?php if ( $luxury_deals_title ) : ?>
                            <h2 class="section-title"><?php echo esc_html( $luxury_deals_title ); ?></h2>
                        <?php endif; ?>

                        <?php if ( $luxury_deals_subtitle ) : ?>
                            <p class="section-subtitle"><?php echo esc_html( $luxury_deals_subtitle ); ?></p>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="section-link">
                            <?php esc_html_e( 'See all deals', 'lyststyle-aggregator' ); ?>
                        </a>
                    </div>

                    <div class="products-grid luxury-grid">
                        <?php
                        while ( $luxury_deals->have_posts() ) :
                            $luxury_deals->the_post();
                            get_template_part( 'template-parts/product-card' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
    endif;
    ?>

    <?php
    /**
     * Articles & Guides Section
     * Displays editorial content from the article post type
     */
    if ( get_theme_mod( 'show_articles', true ) ) :
        $articles_title    = get_theme_mod( 'articles_title', 'Articles & Guides' );
        $articles_subtitle = get_theme_mod( 'articles_subtitle', 'Expert insights and style inspiration.' );
        $articles_count    = get_theme_mod( 'articles_count', 3 );

        $articles = new WP_Query( array(
            'post_type'      => 'article',
            'posts_per_page' => absint( $articles_count ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $articles->have_posts() ) :
            ?>
            <section class="articles-section section-padding">
                <div class="container">
                    <div class="section-header">
                        <?php if ( $articles_title ) : ?>
                            <h2 class="section-title"><?php echo esc_html( $articles_title ); ?></h2>
                        <?php endif; ?>

                        <?php if ( $articles_subtitle ) : ?>
                            <p class="section-subtitle"><?php echo esc_html( $articles_subtitle ); ?></p>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( get_post_type_archive_link( 'article' ) ); ?>" class="section-link">
                            <?php esc_html_e( 'View all articles', 'lyststyle-aggregator' ); ?>
                        </a>
                    </div>

                    <div class="articles-grid">
                        <?php
                        while ( $articles->have_posts() ) :
                            $articles->the_post();
                            get_template_part( 'template-parts/article-card' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
    endif;
    ?>

</main>

<?php
get_footer();
