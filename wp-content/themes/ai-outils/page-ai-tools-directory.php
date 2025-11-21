<?php
/**
 * Template Name: AI Tools Directory
 *
 * Displays all AI tools in a filterable directory format with:
 * - Category filter pills with AJAX filtering
 * - Search functionality
 * - Load more pagination
 * - Category panels for overview
 *
 * @package AI_Outils
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Include category icons helper
get_template_part( 'template-parts/category-icons' );

// Get tools count
$tools_count = ai_outils_get_tools_count();
$categories_count = ai_outils_get_categories_count();
?>

<main id="main" class="site-main ai-directory-page">
    <!-- Directory Hero -->
    <section class="hero directory-hero">
        <div class="container">
            <h1><?php esc_html_e( 'AI Tools Directory', 'ai-outils' ); ?></h1>
            <p class="subtitle">
                <?php
                printf(
                    esc_html__( 'Explore %1$d+ AI tools across %2$d categories', 'ai-outils' ),
                    $tools_count,
                    $categories_count
                );
                ?>
            </p>
            <p class="description">
                <?php esc_html_e( 'A comprehensive directory of AI tools. Find the best AI tools for your needs, from automation to content creation.', 'ai-outils' ); ?>
            </p>
        </div>
    </section>

    <div class="container">
        <div class="section ai-directory-container directory-section">

            <?php
            /**
             * Filters Bar
             * Uses template-parts/filters-bar.php
             */
            get_template_part( 'template-parts/filters-bar', null, array(
                'show_search'      => true,
                'show_categories'  => true,
                'show_sort'        => true,
                'current_category' => 'all',
                'ajax_enabled'     => true,
            ) );
            ?>

            <!-- Tools Grid (AJAX Target) -->
            <div class="tools-grid card-grid" id="tools-grid">
                <?php
                // Initial load of tools
                $initial_query = new WP_Query( array(
                    'post_type'      => AI_OUTILS_CPT_SLUG,
                    'posts_per_page' => 12,
                    'post_status'    => 'publish',
                ) );

                if ( $initial_query->have_posts() ) :
                    while ( $initial_query->have_posts() ) :
                        $initial_query->the_post();
                        get_template_part( 'template-parts/tool-card' );
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="no-tools-found">
                        <p><?php esc_html_e( 'No AI tools found. Please add some tools.', 'ai-outils' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- AJAX Pagination Container -->
            <div class="ajax-pagination-container" id="ajax-pagination">
                <?php if ( $initial_query->max_num_pages > 1 ) : ?>
                    <div class="load-more-container">
                        <button type="button" class="btn btn-primary btn-lg load-more-btn" data-page="1">
                            <?php esc_html_e( 'Load More Tools', 'ai-outils' ); ?>
                        </button>
                        <p class="tools-count-info">
                            <?php
                            printf(
                                esc_html__( 'Showing %1$d of %2$d tools', 'ai-outils' ),
                                min( 12, $initial_query->found_posts ),
                                $initial_query->found_posts
                            );
                            ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Browse by Category Section -->
        <section class="section category-browse-section">
            <div class="text-center mb-xl">
                <h2><?php esc_html_e( 'Browse by Category', 'ai-outils' ); ?></h2>
                <p class="section-description">
                    <?php esc_html_e( 'Explore AI tools organized by category', 'ai-outils' ); ?>
                </p>
            </div>

            <?php
            // Get all categories with tools
            $categories = get_terms( array(
                'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
                'hide_empty' => true,
                'orderby'    => 'count',
                'order'      => 'DESC',
                'exclude'    => get_excluded_category_ids(),
            ) );

            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                ?>
                <div class="category-panels">
                    <?php
                    foreach ( $categories as $category ) :
                        // Get sample tools for this category
                        $tools_query = new WP_Query( array(
                            'post_type'      => AI_OUTILS_CPT_SLUG,
                            'posts_per_page' => 5,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => AI_OUTILS_TAXONOMY_SLUG,
                                    'field'    => 'term_id',
                                    'terms'    => $category->term_id,
                                ),
                            ),
                        ) );

                        if ( ! $tools_query->have_posts() ) {
                            wp_reset_postdata();
                            continue;
                        }
                        ?>

                        <!-- Category Panel -->
                        <div class="category-panel" id="category-<?php echo esc_attr( $category->slug ); ?>">
                            <div class="category-panel-header">
                                <div class="category-panel-icon">
                                    <?php echo get_ai_category_icon_html( $category->slug ); ?>
                                </div>
                                <div class="category-panel-info">
                                    <h3 class="category-panel-title">
                                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
                                            <?php echo esc_html( $category->name ); ?>
                                        </a>
                                    </h3>
                                    <p class="category-panel-count">
                                        <?php
                                        printf(
                                            _n( '%d tool', '%d tools', $category->count, 'ai-outils' ),
                                            $category->count
                                        );
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <?php if ( $category->description ) : ?>
                                <p class="category-panel-description">
                                    <?php echo esc_html( $category->description ); ?>
                                </p>
                            <?php endif; ?>

                            <!-- Tools List -->
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

                            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="btn btn-primary btn-sm">
                                <?php printf( esc_html__( 'View All %s', 'ai-outils' ), esc_html( $category->name ) ); ?>
                                <span aria-hidden="true"> &rarr;</span>
                            </a>
                        </div>

                    <?php endforeach; ?>
                </div>

            <?php else : ?>
                <div class="no-categories text-center">
                    <p><?php esc_html_e( 'No categories found. Please add some AI tool categories.', 'ai-outils' ); ?></p>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php
get_footer();
