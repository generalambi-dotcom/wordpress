<?php
/**
 * The template for displaying archive pages (generic fallback)
 *
 * @package AI_Outils
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="section">
            <!-- Archive Header -->
            <header class="page-header text-center mb-xl">
                <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
                <?php the_archive_description( '<div class="archive-description" style="max-width: 700px; margin: 1rem auto 0; color: var(--color-text-light);">', '</div>' ); ?>
            </header>

            <?php if ( have_posts() ) : ?>
                <!-- Content Grid -->
                <div class="card-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();

                        // Use appropriate template part based on post type
                        if ( get_post_type() === AI_OUTILS_CPT_SLUG ) {
                            get_template_part( 'template-parts/tool-card' );
                        } else {
                            get_template_part( 'template-parts/blog-card' );
                        }

                    endwhile;
                    ?>
                </div>

                <!-- Pagination -->
                <?php ai_outils_pagination(); ?>

            <?php else : ?>
                <div class="no-results text-center" style="padding: 4rem 0;">
                    <h2><?php _e( 'Nothing Found', 'ai-outils' ); ?></h2>
                    <p style="color: var(--color-text-light);">
                        <?php _e( 'It seems we can\'t find what you\'re looking for.', 'ai-outils' ); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
