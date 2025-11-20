<?php
/**
 * Template part for displaying category panels
 *
 * @package AI_Outils
 *
 * Expected variables:
 * $category - The category term object
 * $icon - The icon to display (optional)
 * $tools_query - The WP_Query object with tools (optional)
 * $tools_limit - Number of tools to display (default: 5)
 */

if ( ! isset( $category ) || ! $category ) {
    return;
}

$icon = isset( $icon ) ? $icon : '📊';
$tools_limit = isset( $tools_limit ) ? $tools_limit : 5;

// Query tools if not provided
if ( ! isset( $tools_query ) ) {
    $tools_query = new WP_Query( array(
        'post_type'      => AI_OUTILS_CPT_SLUG,
        'posts_per_page' => $tools_limit,
        'tax_query'      => array(
            array(
                'taxonomy' => AI_OUTILS_TAXONOMY_SLUG,
                'field'    => 'term_id',
                'terms'    => $category->term_id,
            ),
        ),
    ) );
}
?>

<div class="category-panel">
    <div class="category-panel-header">
        <div class="category-panel-icon">
            <?php echo $icon; ?>
        </div>
        <div>
            <h2 class="category-panel-title"><?php echo esc_html( $category->name ); ?></h2>
            <p class="category-panel-count">
                <?php printf( _n( '%d resource', '%d resources', $category->count, 'ai-outils' ), $category->count ); ?>
            </p>
        </div>
    </div>

    <?php if ( $category->description ) : ?>
        <p class="category-panel-description">
            <?php echo esc_html( $category->description ); ?>
        </p>
    <?php endif; ?>

    <?php if ( $tools_query->have_posts() ) : ?>
        <ul class="category-panel-list">
            <?php
            while ( $tools_query->have_posts() ) :
                $tools_query->the_post();
                ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </li>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </ul>

        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="btn btn-primary">
            <?php printf( __( 'View All %s Tools →', 'ai-outils' ), esc_html( $category->name ) ); ?>
        </a>
    <?php endif; ?>
</div>
