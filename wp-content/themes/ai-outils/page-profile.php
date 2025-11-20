<?php
/**
 * Template Name: Profile & Interests
 *
 * @package AI_Outils
 */

// Redirect if not logged in
if ( ! ai_outils_is_member() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

get_header();

$current_user = wp_get_current_user();
?>

<main class="site-main">
    <div class="container">
        <div class="section">
            <!-- Page Header -->
            <header class="page-header text-center mb-xl">
                <h1><?php the_title(); ?></h1>
                <p style="color: var(--color-text-light); max-width: 600px; margin: 1rem auto 0;">
                    <?php _e( 'Update your profile information and interests to get better recommendations.', 'ai-outils' ); ?>
                </p>
            </header>

            <div style="max-width: 700px; margin: 0 auto;">
                <!-- User Avatar -->
                <div class="text-center mb-xl">
                    <div class="user-avatar" style="margin: 0 auto;">
                        <?php echo ai_outils_get_user_avatar_letter(); ?>
                    </div>
                    <h2 style="margin-top: 1rem;"><?php echo esc_html( ai_outils_get_user_name() ); ?></h2>
                    <p style="color: var(--color-text-light);"><?php echo esc_html( $current_user->user_email ); ?></p>
                </div>

                <!-- Profile Form -->
                <div class="card" style="padding: 2rem;">
                    <?php
                    // Check if membership plugin provides profile form
                    if ( ai_outils_has_membership_plugin() ) :
                        // Display membership plugin profile form
                        ai_outils_membership_profile_form();

                        // Also display page content if any
                        the_content();
                    else :
                        // Fallback: Show basic profile form structure
                        ?>
                        <form method="post" action="">
                            <?php wp_nonce_field( 'update_profile', 'profile_nonce' ); ?>

                            <div style="margin-bottom: 1.5rem;">
                                <label for="first_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                                    <?php _e( 'First Name', 'ai-outils' ); ?>
                                </label>
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    class="form-input"
                                    value="<?php echo esc_attr( $current_user->user_firstname ); ?>"
                                    style="width: 100%;"
                                >
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label for="last_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                                    <?php _e( 'Last Name', 'ai-outils' ); ?>
                                </label>
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    class="form-input"
                                    value="<?php echo esc_attr( $current_user->user_lastname ); ?>"
                                    style="width: 100%;"
                                >
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                                    <?php _e( 'Your Interests', 'ai-outils' ); ?>
                                </label>
                                <p style="color: var(--color-text-light); font-size: 0.875rem; margin-bottom: 1rem;">
                                    <?php _e( 'Select the categories that interest you to get personalized recommendations.', 'ai-outils' ); ?>
                                </p>

                                <?php
                                // Get all AI categories
                                $categories = get_terms( array(
                                    'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
                                    'hide_empty' => false,
                                ) );

                                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                                    foreach ( $categories as $category ) :
                                        ?>
                                        <label style="display: flex; align-items: center; padding: 0.75rem; margin-bottom: 0.5rem; background: var(--color-bg-light); border-radius: var(--radius-md); cursor: pointer;">
                                            <input
                                                type="checkbox"
                                                name="interests[]"
                                                value="<?php echo esc_attr( $category->term_id ); ?>"
                                                style="margin-right: 0.75rem;"
                                            >
                                            <span style="flex: 1;">
                                                <?php echo esc_html( $category->name ); ?>
                                            </span>
                                            <span style="color: var(--color-text-light); font-size: 0.875rem;">
                                                <?php echo esc_html( $category->count ); ?> tools
                                            </span>
                                        </label>
                                    <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label for="bio" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                                    <?php _e( 'Bio', 'ai-outils' ); ?>
                                </label>
                                <textarea
                                    id="bio"
                                    name="bio"
                                    class="form-input"
                                    rows="4"
                                    style="width: 100%;"
                                    placeholder="<?php esc_attr_e( 'Tell us about yourself...', 'ai-outils' ); ?>"
                                ><?php echo esc_textarea( get_user_meta( $current_user->ID, 'description', true ) ); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                                <?php _e( 'Update Profile', 'ai-outils' ); ?>
                            </button>
                        </form>

                        <p style="margin-top: 2rem; text-align: center; color: var(--color-text-light); font-size: 0.875rem;">
                            <?php _e( 'Note: This is a placeholder form. Install the AI Membership plugin for full functionality.', 'ai-outils' ); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Footer Navigation -->
                <div class="members-footer-nav mt-xl">
                    <a href="<?php echo esc_url( home_url( '/members-dashboard/' ) ); ?>" class="btn btn-outline">
                        <?php _e( '← Back to Dashboard', 'ai-outils' ); ?>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/tool-recommendations/' ) ); ?>" class="btn btn-primary">
                        <?php _e( 'View Recommendations', 'ai-outils' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
