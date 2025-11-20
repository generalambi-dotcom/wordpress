<?php
/**
 * The template for displaying single articles
 *
 * @package Lyststyle_Aggregator
 */

get_header();

while ( have_posts() ) :
    the_post();
    ?>

    <main id="primary" class="site-main single-article">
        <div class="container">

            <?php lyststyle_breadcrumbs(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <?php
                    $categories = get_the_category();
                    if ( $categories ) :
                        ?>
                        <div class="entry-categories">
                            <?php foreach ( $categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="category-badge">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="entry-title"><?php the_title(); ?></h1>

                    <div class="entry-meta">
                        <span class="posted-on">
                            <?php echo esc_html( get_the_date() ); ?>
                        </span>
                        <?php if ( get_the_author() ) : ?>
                            <span class="byline">
                                <?php
                                printf(
                                    esc_html__( 'by %s', 'lyststyle-aggregator' ),
                                    '<span class="author">' . esc_html( get_the_author() ) . '</span>'
                                );
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="entry-featured-image">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lyststyle-aggregator' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <?php if ( has_tag() ) : ?>
                    <footer class="entry-footer">
                        <div class="entry-tags">
                            <?php the_tags( '<span class="tags-label">' . esc_html__( 'Tagged:', 'lyststyle-aggregator' ) . '</span> ', ', ' ); ?>
                        </div>
                    </footer>
                <?php endif; ?>

            </article>

            <!-- Recommended Products -->
            <?php
            // Get related products from custom meta field
            $related_product_ids = get_post_meta( get_the_ID(), '_article_related_products', true );

            // If no related products are set, get recent products
            if ( empty( $related_product_ids ) || ! is_array( $related_product_ids ) ) {
                $products_query = new WP_Query( array(
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );
            } else {
                // Query products by IDs
                $products_query = new WP_Query( array(
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'post__in'       => $related_product_ids,
                    'orderby'        => 'post__in',
                ) );
            }

            if ( $products_query->have_posts() ) :
                ?>
                <section class="recommended-products section-padding">
                    <h2 class="section-title"><?php esc_html_e( 'Recommended Products', 'lyststyle-aggregator' ); ?></h2>
                    <div class="products-grid">
                        <?php
                        while ( $products_query->have_posts() ) :
                            $products_query->the_post();
                            get_template_part( 'template-parts/product', 'card' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        </div>
    </main>

<?php
endwhile;

get_footer();
