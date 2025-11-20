<?php
/**
 * The template for displaying AI Category taxonomy archive
 *
 * @package AI_Outils
 */

get_header();

$term = get_queried_object();
?>

<main class="site-main">
    <!-- Category Hero -->
    <section class="hero">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                <h1><?php echo esc_html( $term->name ); ?></h1>
                <?php if ( $term->description ) : ?>
                    <p class="subtitle" style="margin-top: 1rem;">
                        <?php echo esc_html( $term->description ); ?>
                    </p>
                <?php endif; ?>
                <p style="color: rgba(255,255,255,0.9); margin-top: 1rem;">
                    <?php printf( _n( '%d Tool', '%d Tools', $term->count, 'ai-outils' ), $term->count ); ?>
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Filters -->
            <div class="filters">
                <form method="get" action="<?php echo esc_url( get_term_link( $term ) ); ?>">
                    <div class="filter-row">
                        <!-- Search within category -->
                        <div class="filter-search">
                            <input
                                type="search"
                                name="s"
                                class="form-input"
                                placeholder="<?php esc_attr_e( 'Search within this category...', 'ai-outils' ); ?>"
                                value="<?php echo get_search_query(); ?>"
                            >
                        </div>

                        <!-- Sort Filter -->
                        <select name="orderby" class="filter-select">
                            <option value="date"><?php _e( 'Latest', 'ai-outils' ); ?></option>
                            <option value="title" <?php selected( isset( $_GET['orderby'] ) ? $_GET['orderby'] : '', 'title' ); ?>>
                                <?php _e( 'Name (A-Z)', 'ai-outils' ); ?>
                            </option>
                        </select>

                        <button type="submit" class="btn btn-primary">
                            <?php _e( 'Apply', 'ai-outils' ); ?>
                        </button>
                    </div>
                </form>
            </div>

            <?php if ( have_posts() ) : ?>
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
                    <p style="color: var(--color-text-light);">
                        <?php _e( 'No tools available in this category yet.', 'ai-outils' ); ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Related Categories -->
            <?php
            $all_categories = get_terms( array(
                'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
                'hide_empty' => true,
                'exclude'    => array( $term->term_id ),
                'number'     => 6,
            ) );

            if ( ! empty( $all_categories ) && ! is_wp_error( $all_categories ) ) :
                ?>
                <div class="mt-xl">
                    <h2 class="mb-lg"><?php _e( 'Explore Other Categories', 'ai-outils' ); ?></h2>
                    <div class="card-grid">
                        <?php foreach ( $all_categories as $category ) : ?>
                            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="card category-card">
                                <div class="category-icon">
                                    <?php
                                    $icons = array(
                                        'ai-agents'             => '🤖',
                                        'ai-code-assistants'    => '💻',
                                        'automation'            => '⚙️',
                                        'content-writing'       => '✍️',
                                        'presentation'          => '📊',
                                        'social-media'          => '📱',
                                        'video-editing'         => '🎬',
                                        'writing'               => '📝',
                                    );
                                    $icon = isset( $icons[ $category->slug ] ) ? $icons[ $category->slug ] : '📊';
                                    echo $icon;
                                    ?>
                                </div>
                                <h3 class="category-name"><?php echo esc_html( $category->name ); ?></h3>
                                <p class="category-count">
                                    <?php printf( _n( '%d Tool', '%d Tools', $category->count, 'ai-outils' ), $category->count ); ?>
                                </p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
