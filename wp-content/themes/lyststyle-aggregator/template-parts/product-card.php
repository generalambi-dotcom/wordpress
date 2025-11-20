<?php
/**
 * Template part for displaying product cards
 *
 * @package Lyststyle_Aggregator
 */

$product_id = get_the_ID();
$brand      = lyststyle_get_product_brand( $product_id );
$price      = lyststyle_get_product_min_price( $product_id );
$currency   = get_post_meta( $product_id, '_product_currency', true ) ?: 'GBP';
$image_url  = get_the_post_thumbnail_url( $product_id, 'product-thumbnail' );
?>

<article id="product-<?php echo esc_attr( $product_id ); ?>" <?php post_class( 'product-card' ); ?> data-product-id="<?php echo esc_attr( $product_id ); ?>">
    <div class="product-card-inner">
        <div class="product-image">
            <a href="<?php the_permalink(); ?>">
                <?php if ( $image_url ) : ?>
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                <?php else : ?>
                    <div class="product-placeholder">
                        <span><?php esc_html_e( 'No image', 'lyststyle-aggregator' ); ?></span>
                    </div>
                <?php endif; ?>
            </a>

            <button class="wishlist-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Add to wishlist', 'lyststyle-aggregator' ); ?>">
                <span class="icon-heart"><?php echo lyststyle_get_icon( 'heart' ); ?></span>
                <span class="icon-heart-filled"><?php echo lyststyle_get_icon( 'heart-filled' ); ?></span>
            </button>
        </div>

        <div class="product-details">
            <?php if ( $brand ) : ?>
                <div class="brand"><?php echo esc_html( $brand ); ?></div>
            <?php endif; ?>

            <h3 class="product-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <?php if ( $price > 0 ) : ?>
                <div class="product-price">
                    <span class="price-from"><?php esc_html_e( 'FROM', 'lyststyle-aggregator' ); ?></span>
                    <span class="price-amount"><?php echo esc_html( lyststyle_format_price( $price, $currency ) ); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</article>
