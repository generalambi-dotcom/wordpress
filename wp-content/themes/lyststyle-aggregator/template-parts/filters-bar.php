<?php
/**
 * Template part for displaying product filters
 *
 * @package Lyststyle_Aggregator
 */

$current_category = get_query_var( 'product_category' );
$current_brand    = get_query_var( 'brand' );
$min_price        = isset( $_GET['min_price'] ) ? intval( $_GET['min_price'] ) : 0;
$max_price        = isset( $_GET['max_price'] ) ? intval( $_GET['max_price'] ) : 0;
$current_color    = isset( $_GET['color'] ) ? sanitize_text_field( $_GET['color'] ) : '';
$current_sort     = isset( $_GET['sort'] ) ? sanitize_text_field( $_GET['sort'] ) : '';

$price_range      = lyststyle_get_price_range();
$colors           = lyststyle_get_all_colors();
?>

<div class="filters-bar">
    <form class="filters-form" method="get" action="">
        <div class="filters-row">
            <!-- Category Filter -->
            <div class="filter-group">
                <label for="filter-category" class="filter-label"><?php esc_html_e( 'Category', 'lyststyle-aggregator' ); ?></label>
                <select name="product_category" id="filter-category" class="filter-select">
                    <option value=""><?php esc_html_e( 'All Categories', 'lyststyle-aggregator' ); ?></option>
                    <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'product_category',
                        'hide_empty' => true,
                    ) );

                    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                        foreach ( $categories as $category ) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                esc_attr( $category->slug ),
                                selected( $current_category, $category->slug, false ),
                                esc_html( $category->name )
                            );
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Brand Filter -->
            <div class="filter-group">
                <label for="filter-brand" class="filter-label"><?php esc_html_e( 'Brand', 'lyststyle-aggregator' ); ?></label>
                <select name="brand" id="filter-brand" class="filter-select">
                    <option value=""><?php esc_html_e( 'All Brands', 'lyststyle-aggregator' ); ?></option>
                    <?php
                    $brands = get_terms( array(
                        'taxonomy'   => 'brand',
                        'hide_empty' => true,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                    ) );

                    if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
                        foreach ( $brands as $brand ) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                esc_attr( $brand->slug ),
                                selected( $current_brand, $brand->slug, false ),
                                esc_html( $brand->name )
                            );
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Color Filter -->
            <?php if ( ! empty( $colors ) ) : ?>
            <div class="filter-group">
                <label for="filter-color" class="filter-label"><?php esc_html_e( 'Color', 'lyststyle-aggregator' ); ?></label>
                <select name="color" id="filter-color" class="filter-select">
                    <option value=""><?php esc_html_e( 'All Colors', 'lyststyle-aggregator' ); ?></option>
                    <?php foreach ( $colors as $color ) : ?>
                        <option value="<?php echo esc_attr( $color ); ?>" <?php selected( $current_color, $color ); ?>>
                            <?php echo esc_html( $color ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Price Range -->
            <div class="filter-group filter-price">
                <label for="filter-min-price" class="filter-label"><?php esc_html_e( 'Price Range', 'lyststyle-aggregator' ); ?></label>
                <div class="price-inputs">
                    <input
                        type="number"
                        name="min_price"
                        id="filter-min-price"
                        class="filter-input"
                        placeholder="Min"
                        min="<?php echo esc_attr( $price_range['min'] ); ?>"
                        max="<?php echo esc_attr( $price_range['max'] ); ?>"
                        value="<?php echo esc_attr( $min_price ); ?>"
                    >
                    <span class="price-separator">-</span>
                    <input
                        type="number"
                        name="max_price"
                        id="filter-max-price"
                        class="filter-input"
                        placeholder="Max"
                        min="<?php echo esc_attr( $price_range['min'] ); ?>"
                        max="<?php echo esc_attr( $price_range['max'] ); ?>"
                        value="<?php echo esc_attr( $max_price ); ?>"
                    >
                </div>
            </div>

            <!-- Sort -->
            <div class="filter-group">
                <label for="filter-sort" class="filter-label"><?php esc_html_e( 'Sort By', 'lyststyle-aggregator' ); ?></label>
                <select name="sort" id="filter-sort" class="filter-select">
                    <option value="" <?php selected( $current_sort, '' ); ?>><?php esc_html_e( 'Recommended', 'lyststyle-aggregator' ); ?></option>
                    <option value="price_low" <?php selected( $current_sort, 'price_low' ); ?>><?php esc_html_e( 'Price: Low to High', 'lyststyle-aggregator' ); ?></option>
                    <option value="price_high" <?php selected( $current_sort, 'price_high' ); ?>><?php esc_html_e( 'Price: High to Low', 'lyststyle-aggregator' ); ?></option>
                    <option value="new" <?php selected( $current_sort, 'new' ); ?>><?php esc_html_e( 'New In', 'lyststyle-aggregator' ); ?></option>
                </select>
            </div>

            <!-- Apply Filters Button -->
            <div class="filter-group filter-actions">
                <button type="submit" class="btn-filter"><?php esc_html_e( 'Apply Filters', 'lyststyle-aggregator' ); ?></button>
                <?php if ( ! empty( $_GET ) ) : ?>
                    <a href="<?php echo esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) ); ?>" class="btn-clear-filters"><?php esc_html_e( 'Clear', 'lyststyle-aggregator' ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>
