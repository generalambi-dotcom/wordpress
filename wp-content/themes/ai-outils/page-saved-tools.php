<?php
/**
 * Template Name: Saved Tools
 *
 * @package AI_Outils
 */

// Redirect if not logged in
if ( ! ai_outils_is_member() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="section">
            <!-- Page Header -->
            <header class="page-header text-center mb-xl">
                <h1><?php the_title(); ?></h1>
                <p style="color: var(--color-text-light); max-width: 600px; margin: 1rem auto 0;">
                    <?php _e( 'Access your collection of saved AI tools.', 'ai-outils' ); ?>
                </p>
            </header>

            <!-- Saved Tools Grid -->
            <?php
            // This would typically query the user's saved tools from the membership plugin
            // For now, we'll show a placeholder structure

            // Check if membership plugin provides saved tools functionality
            if ( ai_outils_has_membership_plugin() ) :
                // Display membership plugin saved tools
                ai_outils_membership_saved_tools();

                // Also display page content if any
                the_content();
            else :
                // Fallback: Show sample structure
                ?>
                <div class="card-grid">
                    <?php
                    // Example: Query recent tools as placeholder
                    $saved_tools = new WP_Query( array(
                        'post_type'      => AI_OUTILS_CPT_SLUG,
                        'posts_per_page' => 9,
                    ) );

                    if ( $saved_tools->have_posts() ) :
                        while ( $saved_tools->have_posts() ) :
                            $saved_tools->the_post();
                            get_template_part( 'template-parts/tool-card' );
                        endwhile;
                        wp_reset_postdata();
                    else :
                        ?>
                        <div class="text-center" style="grid-column: 1 / -1; padding: 4rem 0;">
                            <h2><?php _e( 'No Saved Tools Yet', 'ai-outils' ); ?></h2>
                            <p style="color: var(--color-text-light); margin-bottom: 2rem;">
                                <?php _e( 'Start exploring and save your favorite AI tools for quick access.', 'ai-outils' ); ?>
                            </p>
                            <a href="<?php echo esc_url( get_post_type_archive_link( AI_OUTILS_CPT_SLUG ) ); ?>" class="btn btn-primary">
                                <?php _e( 'Browse All Tools', 'ai-outils' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Footer Navigation -->
            <div class="members-footer-nav mt-xl">
                <a href="<?php echo esc_url( home_url( '/members-dashboard/' ) ); ?>" class="btn btn-outline">
                    <?php _e( '← Back to Dashboard', 'ai-outils' ); ?>
                </a>
                <a href="<?php echo esc_url( get_post_type_archive_link( AI_OUTILS_CPT_SLUG ) ); ?>" class="btn btn-primary">
                    <?php _e( 'Discover More Tools', 'ai-outils' ); ?>
                </a>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
