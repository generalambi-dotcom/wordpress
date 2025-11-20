<?php
/**
 * Template part for displaying product grid
 *
 * @package Lyststyle_Aggregator
 */

if ( ! isset( $args ) ) {
    $args = array();
}

$products_query = isset( $args['query'] ) ? $args['query'] : null;

if ( ! $products_query ) {
    global $wp_query;
    $products_query = $wp_query;
}

if ( $products_query->have_posts() ) :
    ?>
    <div class="products-grid">
        <?php
        while ( $products_query->have_posts() ) :
            $products_query->the_post();
            get_template_part( 'template-parts/product', 'card' );
        endwhile;
        ?>
    </div>
    <?php
    if ( isset( $args['pagination'] ) && $args['pagination'] ) {
        lyststyle_pagination();
    }
    ?>
<?php else : ?>
    <div class="no-products-found">
        <h2><?php esc_html_e( 'No products found', 'lyststyle-aggregator' ); ?></h2>
        <p><?php esc_html_e( 'Try adjusting your filters or search terms.', 'lyststyle-aggregator' ); ?></p>
    </div>
<?php endif; ?>
