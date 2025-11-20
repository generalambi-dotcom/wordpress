<?php
/**
 * Template Name: Tool Recommendations
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
                <?php if ( get_the_content() ) : ?>
                    <div class="entry-meta" style="color: var(--color-text-light); margin-top: 0.5rem;">
                        <?php
                        printf(
                            __( 'By %s on %s', 'ai-outils' ),
                            get_the_author(),
                            get_the_date()
                        );
                        ?>
                    </div>
                <?php endif; ?>
            </header>

            <!-- Recommendations Header -->
            <div class="mb-xl">
                <h2 class="text-center"><?php _e( 'Recommended Tools for You', 'ai-outils' ); ?></h2>
                <p class="text-center" style="color: var(--color-text-light); max-width: 600px; margin: 1rem auto 0;">
                    <?php _e( 'Based on your interests and goals, here are the AI tools we recommend for you.', 'ai-outils' ); ?>
                </p>
            </div>

            <!-- Recommendation Cards -->
            <div class="card-grid">
                <?php
                // This is a placeholder structure
                // The actual recommendations would come from the membership plugin

                // Example recommendation cards (replace with plugin data)
                for ( $i = 1; $i <= 6; $i++ ) :
                    ?>
                    <div class="card recommendation-card">
                        <div class="tool-card-header">
                            <div style="width: 60px; height: 60px; background: var(--color-primary-light); border-radius: var(--radius-md); flex-shrink: 0;"></div>
                            <div>
                                <div class="chip chip-primary mb-sm"><?php echo 'AI Agents'; ?></div>
                                <h3 class="tool-card-title" style="margin-bottom: 0;"><?php echo 'Tool Name ' . $i; ?></h3>
                            </div>
                        </div>

                        <div class="recommendation-match">
                            ✓ <?php _e( 'Matches your goals:', 'ai-outils' ); ?> <?php echo 'Productivity, Automation'; ?>
                        </div>

                        <p class="tool-card-excerpt">
                            <?php _e( 'This is a placeholder description for the recommended AI tool. The actual content would come from the membership plugin.', 'ai-outils' ); ?>
                        </p>

                        <div class="mb-md">
                            <span class="chip"><?php echo 'Productivity'; ?></span>
                            <span class="chip"><?php echo 'Automation'; ?></span>
                            <span class="chip"><?php echo 'AI'; ?></span>
                        </div>

                        <div style="display: flex; gap: 0.5rem;">
                            <a href="#" class="btn btn-primary">
                                <?php _e( 'Visit Tool', 'ai-outils' ); ?>
                            </a>
                            <button class="btn btn-outline" onclick="alert('Save feature integration needed')">
                                <?php _e( 'Save', 'ai-outils' ); ?>
                            </button>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Plugin Integration Area -->
            <div class="membership-plugin-content mt-xl">
                <?php
                // This is where the membership plugin recommendations shortcode/content would go
                ai_outils_membership_recommendations();

                // Also display page content if any
                the_content();
                ?>
            </div>

            <!-- Footer Navigation -->
            <div class="members-footer-nav mt-xl">
                <a href="<?php echo esc_url( home_url( '/members-dashboard/' ) ); ?>" class="btn btn-outline">
                    <?php _e( '← Back to Dashboard', 'ai-outils' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="btn btn-primary">
                    <?php _e( 'Update Interests', 'ai-outils' ); ?>
                </a>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
