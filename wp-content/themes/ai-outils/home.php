<?php
/**
 * The blog posts index template
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
                <h1><?php _e( 'Blog', 'ai-outils' ); ?></h1>
                <p style="max-width: 600px; margin: 0 auto; color: var(--color-text-light);">
                    <?php _e( 'Discover the latest AI news, guides, tutorials, and insights.', 'ai-outils' ); ?>
                </p>
            </header>

            <!-- Filter Pills -->
            <div class="filter-pills">
                <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="filter-pill <?php echo ! is_category() ? 'active' : ''; ?>">
                    <?php _e( 'All', 'ai-outils' ); ?>
                </a>
                <?php
                // Get popular categories
                $blog_categories = get_categories( array(
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'number'     => 5,
                    'hide_empty' => true,
                ) );

                foreach ( $blog_categories as $cat ) :
                    ?>
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="filter-pill">
                        <?php echo esc_html( $cat->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if ( have_posts() ) : ?>
                <!-- Blog Grid -->
                <div class="card-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/blog-card' );
                    endwhile;
                    ?>
                </div>

                <!-- Pagination -->
                <?php ai_outils_pagination(); ?>

            <?php else : ?>
                <div class="no-results text-center" style="padding: 4rem 0;">
                    <h2><?php _e( 'No Posts Found', 'ai-outils' ); ?></h2>
                    <p style="color: var(--color-text-light);">
                        <?php _e( 'Check back soon for new content.', 'ai-outils' ); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
