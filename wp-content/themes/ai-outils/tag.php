<?php
/**
 * The template for displaying tag archives
 *
 * @package AI_Outils
 */

get_header();

$tag = get_queried_object();
?>

<main class="site-main">
    <div class="container">
        <div class="section">
            <!-- Tag Header -->
            <header class="page-header text-center mb-xl">
                <h1>
                    <?php
                    printf( __( 'Tag: %s', 'ai-outils' ), '<span>' . single_tag_title( '', false ) . '</span>' );
                    ?>
                </h1>
                <?php if ( tag_description() ) : ?>
                    <div class="archive-description" style="max-width: 700px; margin: 1rem auto 0; color: var(--color-text-light);">
                        <?php echo tag_description(); ?>
                    </div>
                <?php endif; ?>
                <p style="color: var(--color-text-lighter); margin-top: 0.5rem;">
                    <?php
                    printf(
                        _n( '%d Post', '%d Posts', $tag->count, 'ai-outils' ),
                        $tag->count
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
                        <?php _e( 'No posts with this tag yet.', 'ai-outils' ); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
