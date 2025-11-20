<?php
/**
 * Template part for displaying price comparison table
 *
 * @package Lyststyle_Aggregator
 */

$product_id      = get_the_ID();
$affiliate_links = get_post_meta( $product_id, '_product_affiliate_links', true );
$links           = $affiliate_links ? json_decode( $affiliate_links, true ) : array();

if ( empty( $links ) ) {
    return;
}

// Sort by price
usort( $links, function( $a, $b ) {
    return floatval( $a['price'] ) - floatval( $b['price'] );
} );
?>

<div class="price-comparison-section">
    <h2 class="section-title"><?php esc_html_e( 'Where to Buy', 'lyststyle-aggregator' ); ?></h2>

    <div class="price-comparison-table">
        <table class="comparison-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Retailer', 'lyststyle-aggregator' ); ?></th>
                    <th><?php esc_html_e( 'Price', 'lyststyle-aggregator' ); ?></th>
                    <th><?php esc_html_e( 'Shipping', 'lyststyle-aggregator' ); ?></th>
                    <th><?php esc_html_e( 'Action', 'lyststyle-aggregator' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $links as $link ) : ?>
                    <tr class="retailer-row">
                        <td class="retailer-name">
                            <strong><?php echo esc_html( $link['retailer_name'] ); ?></strong>
                        </td>
                        <td class="retailer-price">
                            <strong><?php echo esc_html( lyststyle_format_price( $link['price'], $link['currency'] ) ); ?></strong>
                        </td>
                        <td class="retailer-shipping">
                            <?php echo esc_html( $link['shipping_info'] ?: '—' ); ?>
                        </td>
                        <td class="retailer-action">
                            <a href="<?php echo esc_url( $link['url'] ); ?>"
                               class="btn-go-to-store"
                               target="_blank"
                               rel="nofollow noopener noreferrer">
                                <?php esc_html_e( 'Go to store', 'lyststyle-aggregator' ); ?>
                                <?php echo lyststyle_get_icon( 'external-link' ); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
