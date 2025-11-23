<?php
/**
 * The header template
 *
 * @package AI_Outils
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link sr-only" href="#main"><?php _e( 'Skip to content', 'ai-outils' ); ?></a>

    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <div class="site-branding">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle hide-desktop" aria-label="<?php esc_attr_e( 'Toggle Menu', 'ai-outils' ); ?>" aria-expanded="false" aria-controls="main-navigation">
                    <span aria-hidden="true">☰</span>
                </button>

                <!-- Primary Navigation -->
                <nav id="main-navigation" class="main-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'ai-outils' ); ?>">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            // Default menu if no menu is set
                            echo '<ul class="primary-menu">';
                            echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">Discover</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/blog/' ) ) . '">Resources</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/tools/' ) ) . '">Useful Tools</a></li>';
                            echo '</ul>';
                        },
                    ) );
                    ?>
                </nav>

                <!-- Header Actions (Login/Signup) -->
                <div class="header-actions hide-mobile">
                    <?php if ( ai_outils_is_member() ) : ?>
                        <a href="<?php echo esc_url( home_url( '/members-dashboard/' ) ); ?>" class="btn btn-outline btn-sm">
                            <?php _e( 'Dashboard', 'ai-outils' ); ?>
                        </a>
                        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="btn btn-primary btn-sm">
                            <?php _e( 'Logout', 'ai-outils' ); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn-outline btn-sm">
                            <?php _e( 'Login', 'ai-outils' ); ?>
                        </a>
                        <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="btn btn-primary btn-sm">
                            <?php _e( 'Join For Free', 'ai-outils' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div id="content" class="site-content">
