<?php
/**
 * Template Name: AI Tools Directory
 *
 * Displays all categories with their tools in an organized directory format
 *
 * @package AI_Outils
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="section">
            <!-- Page Header -->
            <header class="page-header text-center mb-xl">
                <h1><?php _e( 'AI Tools Directory', 'ai-outils' ); ?></h1>
                <p style="max-width: 700px; margin: 0 auto; color: var(--color-text-light); font-size: 1.125rem;">
                    <?php _e( 'A comprehensive directory of AI tools. See how you can improve your process with the best AI tools for your needs.', 'ai-outils' ); ?>
                </p>
            </header>

            <!-- Directory Content -->
            <?php
            // Get all categories
            $categories = get_terms( array(
                'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ) );

            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                foreach ( $categories as $category ) :
                    // Get tools in this category
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
                        continue;
                    }
                    ?>

                    <!-- Category Panel -->
                    <div class="category-panel">
                        <div class="category-panel-header">
                            <div class="category-panel-icon">
                                <?php
                                // Icon mapping
                                $icons = array(
                                    'ai-agents'             => '🤖',
                                    'ai-code-assistants'    => '💻',
                                    'automation'            => '⚙️',
                                    'content-writing'       => '✍️',
                                    'presentation'          => '📊',
                                    'social-media'          => '📱',
                                    'video-editing'         => '🎬',
                                    'writing'               => '📝',
                                    '3d-modelling'          => '🎨',
                                );

                                $icon = isset( $icons[ $category->slug ] ) ? $icons[ $category->slug ] : '📊';
                                echo $icon;
                                ?>
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

                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="btn btn-primary">
                            <?php printf( __( 'View All %s Tools →', 'ai-outils' ), esc_html( $category->name ) ); ?>
                        </a>
                    </div>

                <?php
                endforeach;
            else :
                ?>
                <div class="text-center" style="padding: 4rem 0;">
                    <p style="color: var(--color-text-light);">
                        <?php _e( 'No categories found. Please add some AI tool categories.', 'ai-outils' ); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
