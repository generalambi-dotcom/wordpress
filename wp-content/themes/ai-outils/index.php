<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and is used as a fallback when no more specific template is available.
 *
 * @package AI_Outils
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="section">
            <?php if ( have_posts() ) : ?>

                <header class="page-header">
                    <?php if ( is_home() && ! is_front_page() ) : ?>
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    <?php elseif ( is_search() ) : ?>
                        <h1 class="page-title">
                            <?php printf( __( 'Search Results for: %s', 'ai-outils' ), '<span>' . get_search_query() . '</span>' ); ?>
                        </h1>
                    <?php elseif ( is_archive() ) : ?>
                        <h1 class="page-title"><?php the_archive_title(); ?></h1>
                        <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                    <?php endif; ?>
                </header>

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

                <?php ai_outils_pagination(); ?>

            <?php else : ?>

                <div class="no-results">
                    <h1><?php _e( 'Nothing Found', 'ai-outils' ); ?></h1>
                    <p><?php _e( 'Sorry, no content was found. Try using the search form below.', 'ai-outils' ); ?></p>
                    <?php get_search_form(); ?>
                </div>

            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
