<?php
/**
 * The template for displaying product archives
 *
 * @package Lyststyle_Aggregator
 */

get_header();

// Handle query modifications for filtering
add_action( 'pre_get_posts', 'lyststyle_filter_products_query' );

function lyststyle_filter_products_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'product' ) || is_tax( array( 'product_category', 'brand', 'product_tag' ) ) ) ) {

        // Price filter
        $meta_query = array();

        if ( isset( $_GET['min_price'] ) && $_GET['min_price'] !== '' ) {
            $meta_query[] = array(
                'key'     => '_product_base_price',
                'value'   => floatval( $_GET['min_price'] ),
                'compare' => '>=',
                'type'    => 'NUMERIC',
            );
        }

        if ( isset( $_GET['max_price'] ) && $_GET['max_price'] !== '' ) {
            $meta_query[] = array(
                'key'     => '_product_base_price',
                'value'   => floatval( $_GET['max_price'] ),
                'compare' => '<=',
                'type'    => 'NUMERIC',
            );
        }

        // Color filter
        if ( isset( $_GET['color'] ) && $_GET['color'] !== '' ) {
            $meta_query[] = array(
                'key'     => '_product_color',
                'value'   => sanitize_text_field( $_GET['color'] ),
                'compare' => '=',
            );
        }

        if ( ! empty( $meta_query ) ) {
            $meta_query['relation'] = 'AND';
            $query->set( 'meta_query', $meta_query );
        }

        // Sorting
        if ( isset( $_GET['sort'] ) ) {
            switch ( $_GET['sort'] ) {
                case 'price_low':
                    $query->set( 'meta_key', '_product_base_price' );
                    $query->set( 'orderby', 'meta_value_num' );
                    $query->set( 'order', 'ASC' );
                    break;
                case 'price_high':
                    $query->set( 'meta_key', '_product_base_price' );
                    $query->set( 'orderby', 'meta_value_num' );
                    $query->set( 'order', 'DESC' );
                    break;
                case 'new':
                    $query->set( 'orderby', 'date' );
                    $query->set( 'order', 'DESC' );
                    break;
            }
        }

        $query->set( 'posts_per_page', 24 );
    }
}
?>

<main id="primary" class="site-main archive-product">
    <div class="container">

        <?php lyststyle_breadcrumbs(); ?>

        <header class="page-header">
            <?php
            if ( is_tax() ) {
                $term = get_queried_object();
                echo '<h1 class="page-title">' . esc_html( $term->name ) . '</h1>';
                if ( $term->description ) {
                    echo '<div class="taxonomy-description">' . wp_kses_post( $term->description ) . '</div>';
                }
            } else {
                echo '<h1 class="page-title">' . esc_html__( 'All Products', 'lyststyle-aggregator' ) . '</h1>';
            }
            ?>
        </header>

        <?php get_template_part( 'template-parts/filters', 'bar' ); ?>

        <?php if ( have_posts() ) : ?>

            <div class="archive-results-info">
                <p class="results-count">
                    <?php
                    global $wp_query;
                    printf(
                        esc_html__( 'Showing %s products', 'lyststyle-aggregator' ),
                        '<strong>' . esc_html( $wp_query->found_posts ) . '</strong>'
                    );
                    ?>
                </p>
            </div>

            <?php get_template_part( 'template-parts/product', 'grid', array( 'pagination' => true ) ); ?>

        <?php else : ?>

            <div class="no-products-found">
                <h2><?php esc_html_e( 'No products found', 'lyststyle-aggregator' ); ?></h2>
                <p><?php esc_html_e( 'Try adjusting your filters or search terms.', 'lyststyle-aggregator' ); ?></p>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="btn-primary">
                    <?php esc_html_e( 'View All Products', 'lyststyle-aggregator' ); ?>
                </a>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
