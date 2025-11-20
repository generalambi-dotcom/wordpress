<?php
/**
 * The template for displaying category archives
 *
 * @package AI_Outils
 */

get_header();

$category = get_queried_object();
?>

<main class="site-main">
    <div class="container">
        <div class="section">
            <!-- Category Header -->
            <header class="page-header text-center mb-xl">
                <h1><?php single_cat_title(); ?></h1>
                <?php if ( category_description() ) : ?>
                    <div class="archive-description" style="max-width: 700px; margin: 1rem auto 0; color: var(--color-text-light);">
                        <?php echo category_description(); ?>
                    </div>
                <?php endif; ?>
                <p style="color: var(--color-text-lighter); margin-top: 0.5rem;">
                    <?php
                    printf(
                        _n( '%d Post', '%d Posts', $category->count, 'ai-outils' ),
                        $category->count
                    );
                    ?>
                </p>
            </header>

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
                        <?php _e( 'No posts in this category yet.', 'ai-outils' ); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
