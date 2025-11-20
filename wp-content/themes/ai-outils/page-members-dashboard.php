<?php
/**
 * Template Name: Members Dashboard
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
            <!-- Members Hero -->
            <div class="members-hero">
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
            </div>

            <!-- Welcome Card -->
            <div class="welcome-card">
                <div class="user-avatar">
                    <?php echo ai_outils_get_user_avatar_letter(); ?>
                </div>
                <h2><?php printf( __( 'Welcome, %s!', 'ai-outils' ), ai_outils_get_user_name() ); ?></h2>
                <p style="color: var(--color-text-light); margin-bottom: 0;">
                    <?php _e( 'Your personalized AI tools dashboard', 'ai-outils' ); ?>
                </p>

                <!-- Stat Cards -->
                <div class="stat-cards">
                    <div class="stat-card">
                        <span class="stat-value">
                            <?php
                            // This would be provided by the membership plugin
                            echo '12';
                            ?>
                        </span>
                        <span class="stat-label"><?php _e( 'Saved Tools', 'ai-outils' ); ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">
                            <?php
                            // Profile completion percentage
                            echo '85%';
                            ?>
                        </span>
                        <span class="stat-label"><?php _e( 'Profile', 'ai-outils' ); ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">
                            <?php
                            // Number of interests
                            echo '5';
                            ?>
                        </span>
                        <span class="stat-label"><?php _e( 'Interests', 'ai-outils' ); ?></span>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="action-cards">
                <div class="card action-card">
                    <h3><?php _e( 'Get Recommendations', 'ai-outils' ); ?></h3>
                    <p>
                        <?php _e( 'Discover AI tools tailored to your interests and goals.', 'ai-outils' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/tool-recommendations/' ) ); ?>" class="btn btn-primary">
                        <?php _e( 'View Recommendations', 'ai-outils' ); ?>
                    </a>
                </div>

                <div class="card action-card">
                    <h3><?php _e( 'Saved Tools', 'ai-outils' ); ?></h3>
                    <p>
                        <?php _e( 'Access your collection of saved AI tools.', 'ai-outils' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/saved-tools/' ) ); ?>" class="btn btn-primary">
                        <?php _e( 'View Saved Tools', 'ai-outils' ); ?>
                    </a>
                </div>

                <div class="card action-card">
                    <h3><?php _e( 'Your Profile', 'ai-outils' ); ?></h3>
                    <p>
                        <?php _e( 'Update your interests and preferences.', 'ai-outils' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="btn btn-primary">
                        <?php _e( 'Edit Profile', 'ai-outils' ); ?>
                    </a>
                </div>

                <div class="card action-card">
                    <h3><?php _e( 'Browse All Tools', 'ai-outils' ); ?></h3>
                    <p>
                        <?php _e( 'Explore our complete directory of AI tools.', 'ai-outils' ); ?>
                    </p>
                    <a href="<?php echo esc_url( get_post_type_archive_link( AI_OUTILS_CPT_SLUG ) ); ?>" class="btn btn-outline">
                        <?php _e( 'Browse Tools', 'ai-outils' ); ?>
                    </a>
                </div>
            </div>

            <!-- Plugin Integration Area -->
            <div class="membership-plugin-content">
                <?php
                // This is where the membership plugin dashboard shortcode/content would go
                ai_outils_membership_dashboard();

                // Also display page content if any
                the_content();
                ?>
            </div>

            <!-- Footer Navigation -->
            <div class="members-footer-nav">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline">
                    <?php _e( '← Back to Home', 'ai-outils' ); ?>
                </a>
                <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" style="color: var(--color-text-light);">
                    <?php _e( 'Logout', 'ai-outils' ); ?>
                </a>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
