<?php
/**
 * Meta Boxes
 *
 * @package Lyststyle_Aggregator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Product Meta Boxes
 */
function lyststyle_add_product_meta_boxes() {
    add_meta_box(
        'lyststyle_product_details',
        __( 'Product Details', 'lyststyle-aggregator' ),
        'lyststyle_product_details_callback',
        'product',
        'normal',
        'high'
    );

    add_meta_box(
        'lyststyle_product_affiliate_links',
        __( 'Retailer Affiliate Links', 'lyststyle-aggregator' ),
        'lyststyle_product_affiliate_links_callback',
        'product',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'lyststyle_add_product_meta_boxes' );

/**
 * Product Details Meta Box Callback
 */
function lyststyle_product_details_callback( $post ) {
    wp_nonce_field( 'lyststyle_save_product_meta', 'lyststyle_product_meta_nonce' );

    $sku         = get_post_meta( $post->ID, '_product_sku', true );
    $base_price  = get_post_meta( $post->ID, '_product_base_price', true );
    $currency    = get_post_meta( $post->ID, '_product_currency', true ) ?: 'GBP';
    $gender      = get_post_meta( $post->ID, '_product_gender', true );
    $color       = get_post_meta( $post->ID, '_product_color', true );
    $material    = get_post_meta( $post->ID, '_product_material', true );
    ?>
    <div class="lyststyle-meta-box">
        <p>
            <label for="product_sku"><strong><?php esc_html_e( 'SKU:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <input type="text" id="product_sku" name="product_sku" value="<?php echo esc_attr( $sku ); ?>" style="width: 100%;">
        </p>

        <p>
            <label for="product_base_price"><strong><?php esc_html_e( 'Base Price:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <input type="number" step="0.01" id="product_base_price" name="product_base_price" value="<?php echo esc_attr( $base_price ); ?>" style="width: 200px;">
        </p>

        <p>
            <label for="product_currency"><strong><?php esc_html_e( 'Currency:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <select id="product_currency" name="product_currency" style="width: 200px;">
                <option value="GBP" <?php selected( $currency, 'GBP' ); ?>>GBP (£)</option>
                <option value="USD" <?php selected( $currency, 'USD' ); ?>>USD ($)</option>
                <option value="EUR" <?php selected( $currency, 'EUR' ); ?>>EUR (€)</option>
            </select>
        </p>

        <p>
            <label for="product_gender"><strong><?php esc_html_e( 'Gender:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <select id="product_gender" name="product_gender" style="width: 200px;">
                <option value=""><?php esc_html_e( 'Select Gender', 'lyststyle-aggregator' ); ?></option>
                <option value="women" <?php selected( $gender, 'women' ); ?>><?php esc_html_e( 'Women', 'lyststyle-aggregator' ); ?></option>
                <option value="men" <?php selected( $gender, 'men' ); ?>><?php esc_html_e( 'Men', 'lyststyle-aggregator' ); ?></option>
                <option value="unisex" <?php selected( $gender, 'unisex' ); ?>><?php esc_html_e( 'Unisex', 'lyststyle-aggregator' ); ?></option>
            </select>
        </p>

        <p>
            <label for="product_color"><strong><?php esc_html_e( 'Color:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <input type="text" id="product_color" name="product_color" value="<?php echo esc_attr( $color ); ?>" style="width: 100%;">
            <span class="description"><?php esc_html_e( 'e.g., Black, Brown, Blue', 'lyststyle-aggregator' ); ?></span>
        </p>

        <p>
            <label for="product_material"><strong><?php esc_html_e( 'Material:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <input type="text" id="product_material" name="product_material" value="<?php echo esc_attr( $material ); ?>" style="width: 100%;">
            <span class="description"><?php esc_html_e( 'e.g., Leather, Cotton, Wool', 'lyststyle-aggregator' ); ?></span>
        </p>
    </div>
    <style>
        .lyststyle-meta-box p { margin-bottom: 15px; }
        .lyststyle-meta-box label { display: inline-block; margin-bottom: 5px; }
    </style>
    <?php
}

/**
 * Product Affiliate Links Meta Box Callback
 */
function lyststyle_product_affiliate_links_callback( $post ) {
    $affiliate_links = get_post_meta( $post->ID, '_product_affiliate_links', true );
    $links = $affiliate_links ? json_decode( $affiliate_links, true ) : array();
    ?>
    <div class="lyststyle-affiliate-links">
        <div id="affiliate-links-container">
            <?php
            if ( ! empty( $links ) ) {
                foreach ( $links as $index => $link ) {
                    lyststyle_affiliate_link_row( $index, $link );
                }
            } else {
                lyststyle_affiliate_link_row( 0 );
            }
            ?>
        </div>
        <p>
            <button type="button" class="button" id="add-affiliate-link"><?php esc_html_e( 'Add Retailer Link', 'lyststyle-aggregator' ); ?></button>
        </p>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var linkIndex = $('#affiliate-links-container .affiliate-link-row').length;

        $('#add-affiliate-link').on('click', function() {
            var newRow = `
                <div class="affiliate-link-row" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; background: #f9f9f9;">
                    <p>
                        <label><strong>Retailer Name:</strong></label><br>
                        <input type="text" name="affiliate_links[${linkIndex}][retailer_name]" style="width: 100%;">
                    </p>
                    <p>
                        <label><strong>Price:</strong></label><br>
                        <input type="number" step="0.01" name="affiliate_links[${linkIndex}][price]" style="width: 200px;">
                    </p>
                    <p>
                        <label><strong>Currency:</strong></label><br>
                        <select name="affiliate_links[${linkIndex}][currency]" style="width: 200px;">
                            <option value="GBP">GBP (£)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                        </select>
                    </p>
                    <p>
                        <label><strong>Affiliate URL:</strong></label><br>
                        <input type="url" name="affiliate_links[${linkIndex}][url]" style="width: 100%;">
                    </p>
                    <p>
                        <label><strong>Shipping Info:</strong></label><br>
                        <input type="text" name="affiliate_links[${linkIndex}][shipping_info]" style="width: 100%;" placeholder="e.g., Free shipping">
                    </p>
                    <p>
                        <button type="button" class="button remove-affiliate-link">Remove</button>
                    </p>
                </div>
            `;
            $('#affiliate-links-container').append(newRow);
            linkIndex++;
        });

        $(document).on('click', '.remove-affiliate-link', function() {
            $(this).closest('.affiliate-link-row').remove();
        });
    });
    </script>
    <?php
}

/**
 * Affiliate Link Row HTML
 */
function lyststyle_affiliate_link_row( $index, $link = array() ) {
    $retailer_name  = isset( $link['retailer_name'] ) ? $link['retailer_name'] : '';
    $price          = isset( $link['price'] ) ? $link['price'] : '';
    $currency       = isset( $link['currency'] ) ? $link['currency'] : 'GBP';
    $url            = isset( $link['url'] ) ? $link['url'] : '';
    $shipping_info  = isset( $link['shipping_info'] ) ? $link['shipping_info'] : '';
    ?>
    <div class="affiliate-link-row" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; background: #f9f9f9;">
        <p>
            <label><strong><?php esc_html_e( 'Retailer Name:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <input type="text" name="affiliate_links[<?php echo esc_attr( $index ); ?>][retailer_name]" value="<?php echo esc_attr( $retailer_name ); ?>" style="width: 100%;">
        </p>
        <p>
            <label><strong><?php esc_html_e( 'Price:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <input type="number" step="0.01" name="affiliate_links[<?php echo esc_attr( $index ); ?>][price]" value="<?php echo esc_attr( $price ); ?>" style="width: 200px;">
        </p>
        <p>
            <label><strong><?php esc_html_e( 'Currency:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <select name="affiliate_links[<?php echo esc_attr( $index ); ?>][currency]" style="width: 200px;">
                <option value="GBP" <?php selected( $currency, 'GBP' ); ?>>GBP (£)</option>
                <option value="USD" <?php selected( $currency, 'USD' ); ?>>USD ($)</option>
                <option value="EUR" <?php selected( $currency, 'EUR' ); ?>>EUR (€)</option>
            </select>
        </p>
        <p>
            <label><strong><?php esc_html_e( 'Affiliate URL:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <input type="url" name="affiliate_links[<?php echo esc_attr( $index ); ?>][url]" value="<?php echo esc_attr( $url ); ?>" style="width: 100%;">
        </p>
        <p>
            <label><strong><?php esc_html_e( 'Shipping Info:', 'lyststyle-aggregator' ); ?></strong></label><br>
            <input type="text" name="affiliate_links[<?php echo esc_attr( $index ); ?>][shipping_info]" value="<?php echo esc_attr( $shipping_info ); ?>" style="width: 100%;" placeholder="e.g., Free shipping">
        </p>
        <p>
            <button type="button" class="button remove-affiliate-link"><?php esc_html_e( 'Remove', 'lyststyle-aggregator' ); ?></button>
        </p>
    </div>
    <?php
}

/**
 * Save Product Meta
 */
function lyststyle_save_product_meta( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['lyststyle_product_meta_nonce'] ) ||
         ! wp_verify_nonce( $_POST['lyststyle_product_meta_nonce'], 'lyststyle_save_product_meta' ) ) {
        return;
    }

    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save SKU
    if ( isset( $_POST['product_sku'] ) ) {
        update_post_meta( $post_id, '_product_sku', sanitize_text_field( $_POST['product_sku'] ) );
    }

    // Save base price
    if ( isset( $_POST['product_base_price'] ) ) {
        update_post_meta( $post_id, '_product_base_price', floatval( $_POST['product_base_price'] ) );
    }

    // Save currency
    if ( isset( $_POST['product_currency'] ) ) {
        update_post_meta( $post_id, '_product_currency', sanitize_text_field( $_POST['product_currency'] ) );
    }

    // Save gender
    if ( isset( $_POST['product_gender'] ) ) {
        update_post_meta( $post_id, '_product_gender', sanitize_text_field( $_POST['product_gender'] ) );
    }

    // Save color
    if ( isset( $_POST['product_color'] ) ) {
        update_post_meta( $post_id, '_product_color', sanitize_text_field( $_POST['product_color'] ) );
    }

    // Save material
    if ( isset( $_POST['product_material'] ) ) {
        update_post_meta( $post_id, '_product_material', sanitize_text_field( $_POST['product_material'] ) );
    }

    // Save affiliate links
    if ( isset( $_POST['affiliate_links'] ) && is_array( $_POST['affiliate_links'] ) ) {
        $affiliate_links = array();
        foreach ( $_POST['affiliate_links'] as $link ) {
            if ( ! empty( $link['retailer_name'] ) && ! empty( $link['url'] ) ) {
                $affiliate_links[] = array(
                    'retailer_name'  => sanitize_text_field( $link['retailer_name'] ),
                    'price'          => floatval( $link['price'] ),
                    'currency'       => sanitize_text_field( $link['currency'] ),
                    'url'            => esc_url_raw( $link['url'] ),
                    'shipping_info'  => sanitize_text_field( $link['shipping_info'] ),
                );
            }
        }
        update_post_meta( $post_id, '_product_affiliate_links', wp_json_encode( $affiliate_links ) );
    }
}
add_action( 'save_post_product', 'lyststyle_save_product_meta' );
