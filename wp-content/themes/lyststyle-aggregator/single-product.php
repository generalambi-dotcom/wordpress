<?php
/**
 * The template for displaying single products
 *
 * @package Lyststyle_Aggregator
 */

get_header();

while ( have_posts() ) :
    the_post();

    $product_id   = get_the_ID();
    $brand        = lyststyle_get_product_brand( $product_id );
    $category     = lyststyle_get_product_category( $product_id );
    $price        = lyststyle_get_product_price( $product_id );
    $currency     = get_post_meta( $product_id, '_product_currency', true ) ?: 'GBP';
    $sku          = get_post_meta( $product_id, '_product_sku', true );
    $color        = get_post_meta( $product_id, '_product_color', true );
    $material     = get_post_meta( $product_id, '_product_material', true );
    ?>

    <main id="primary" class="site-main single-product">
        <div class="container">

            <?php lyststyle_breadcrumbs(); ?>

            <article id="product-<?php echo esc_attr( $product_id ); ?>" <?php post_class(); ?>>
                <div class="product-single-layout">

                    <!-- Product Images -->
                    <div class="product-images">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="product-main-image">
                                <?php the_post_thumbnail( 'product-large' ); ?>
                            </div>
                        <?php else : ?>
                            <div class="product-placeholder-large">
                                <span><?php esc_html_e( 'No image available', 'lyststyle-aggregator' ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Details -->
                    <div class="product-info">
                        <?php if ( $brand ) : ?>
                            <div class="product-brand">
                                <a href="<?php echo esc_url( get_term_link( get_term_by( 'name', $brand, 'brand' ) ) ); ?>">
                                    <?php echo esc_html( $brand ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <h1 class="product-name"><?php the_title(); ?></h1>

                        <?php if ( $category ) : ?>
                            <div class="product-category">
                                <a href="<?php echo esc_url( get_term_link( get_term_by( 'name', $category, 'product_category' ) ) ); ?>">
                                    <?php echo esc_html( $category ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( $price > 0 ) : ?>
                            <div class="product-price-main">
                                <span class="price-label"><?php esc_html_e( 'From', 'lyststyle-aggregator' ); ?></span>
                                <span class="price-value"><?php echo esc_html( lyststyle_format_price( $price, $currency ) ); ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Product Meta -->
                        <div class="product-meta">
                            <?php if ( $sku ) : ?>
                                <div class="meta-item">
                                    <span class="meta-label"><?php esc_html_e( 'SKU:', 'lyststyle-aggregator' ); ?></span>
                                    <span class="meta-value"><?php echo esc_html( $sku ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $color ) : ?>
                                <div class="meta-item">
                                    <span class="meta-label"><?php esc_html_e( 'Color:', 'lyststyle-aggregator' ); ?></span>
                                    <span class="meta-value"><?php echo esc_html( $color ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $material ) : ?>
                                <div class="meta-item">
                                    <span class="meta-label"><?php esc_html_e( 'Material:', 'lyststyle-aggregator' ); ?></span>
                                    <span class="meta-value"><?php echo esc_html( $material ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php
                            $tags = get_the_terms( $product_id, 'product_tag' );
                            if ( $tags && ! is_wp_error( $tags ) ) :
                                ?>
                                <div class="meta-item product-tags">
                                    <span class="meta-label"><?php esc_html_e( 'Tags:', 'lyststyle-aggregator' ); ?></span>
                                    <div class="tags-list">
                                        <?php foreach ( $tags as $tag ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="tag-link">
                                                <?php echo esc_html( $tag->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Description -->
                        <?php if ( has_excerpt() || get_the_content() ) : ?>
                            <div class="product-description">
                                <h2><?php esc_html_e( 'Description', 'lyststyle-aggregator' ); ?></h2>
                                <?php
                                if ( has_excerpt() ) {
                                    the_excerpt();
                                } else {
                                    the_content();
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Size & Fit -->
                        <div class="product-size-fit">
                            <h3><?php esc_html_e( 'Size & Fit', 'lyststyle-aggregator' ); ?></h3>
                            <p><?php esc_html_e( 'Please refer to the retailer\'s size guide for specific measurements.', 'lyststyle-aggregator' ); ?></p>
                        </div>

                        <!-- Wishlist Button -->
                        <button class="wishlist-btn-large" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                            <span class="icon-heart"><?php echo lyststyle_get_icon( 'heart' ); ?></span>
                            <span class="btn-text"><?php esc_html_e( 'Add to Wishlist', 'lyststyle-aggregator' ); ?></span>
                        </button>
                    </div>

                </div>

                <!-- Price Comparison Table -->
                <div class="product-retailers">
                    <?php get_template_part( 'template-parts/price-comparison', 'table' ); ?>
                </div>

            </article>

            <!-- Related Products -->
            <?php
            $related_products = lyststyle_get_similar_products( $product_id, 4 );

            if ( $related_products->have_posts() ) :
                ?>
                <section class="related-products section-padding">
                    <h2 class="section-title"><?php esc_html_e( 'You May Also Like', 'lyststyle-aggregator' ); ?></h2>
                    <div class="products-grid">
                        <?php
                        while ( $related_products->have_posts() ) :
                            $related_products->the_post();
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
endwhile;

get_footer();
