<?php
/**
 * The template for displaying all AI Tools archive
 *
 * @package AI_Outils
 */

get_header();
?>

<main class="site-main">
    <!-- Hero Header -->
    <section class="hero">
        <div class="container">
            <h1><?php _e( 'All AI Tools', 'ai-outils' ); ?></h1>
            <p class="subtitle">
                <?php
                $tools_count = ai_outils_get_tools_count();
                printf( __( 'Browse our collection of %d+ curated AI tools', 'ai-outils' ), $tools_count );
                ?>
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Filters -->
            <div class="filters">
                <form method="get" action="<?php echo esc_url( get_post_type_archive_link( AI_OUTILS_CPT_SLUG ) ); ?>">
                    <div class="filter-row">
                        <!-- Search -->
                        <div class="filter-search">
                            <input
                                type="search"
                                name="s"
                                class="form-input"
                                placeholder="<?php esc_attr_e( 'Search AI tools...', 'ai-outils' ); ?>"
                                value="<?php echo get_search_query(); ?>"
                            >
                        </div>

                        <!-- Category Filter -->
                        <select name="category" class="filter-select">
                            <option value=""><?php _e( 'All Categories', 'ai-outils' ); ?></option>
                            <?php
                            $categories = get_terms( array(
                                'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
                                'hide_empty' => true,
                            ) );

                            $selected_category = isset( $_GET['category'] ) ? $_GET['category'] : '';

                            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                                foreach ( $categories as $category ) :
                                    ?>
                                    <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( $selected_category, $category->slug ); ?>>
                                        <?php echo esc_html( $category->name ); ?>
                                    </option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>

                        <!-- Sort Filter -->
                        <select name="orderby" class="filter-select">
                            <option value="date"><?php _e( 'Latest', 'ai-outils' ); ?></option>
                            <option value="title" <?php selected( isset( $_GET['orderby'] ) ? $_GET['orderby'] : '', 'title' ); ?>>
                                <?php _e( 'Name (A-Z)', 'ai-outils' ); ?>
                            </option>
                        </select>

                        <button type="submit" class="btn btn-primary">
                            <?php _e( 'Apply Filters', 'ai-outils' ); ?>
                        </button>
                    </div>
                </form>
            </div>

            <?php if ( have_posts() ) : ?>
                <!-- Results Count -->
                <div class="mb-lg">
                    <p style="color: var(--color-text-light);">
                        <?php
                        global $wp_query;
                        printf(
                            __( 'Showing %d tools', 'ai-outils' ),
                            $wp_query->found_posts
                        );
                        ?>
                    </p>
                </div>

                <!-- Tools Grid -->
                <div class="card-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/tool-card' );
                    endwhile;
                    ?>
                </div>

                <!-- Pagination -->
                <?php ai_outils_pagination(); ?>

            <?php else : ?>
                <div class="no-results text-center" style="padding: 4rem 0;">
                    <h2><?php _e( 'No Tools Found', 'ai-outils' ); ?></h2>
                    <p style="color: var(--color-text-light); margin-bottom: 2rem;">
                        <?php _e( 'Try adjusting your filters or search query.', 'ai-outils' ); ?>
                    </p>
                    <a href="<?php echo esc_url( get_post_type_archive_link( AI_OUTILS_CPT_SLUG ) ); ?>" class="btn btn-primary">
                        <?php _e( 'View All Tools', 'ai-outils' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
