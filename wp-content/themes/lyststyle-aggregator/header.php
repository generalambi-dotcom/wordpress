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
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'lyststyle-aggregator' ); ?></a>

    <header id="masthead" class="site-header sticky-header">
        <div class="header-container">
            <div class="header-main">
                <!-- Logo -->
                <div class="header-logo">
                    <?php
                    $logo = get_theme_mod( 'lyststyle_logo' );
                    if ( $logo ) :
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
                            <img src="<?php echo esc_url( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="site-logo">
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title" rel="home">
                            <strong>LYSTSTYLE</strong>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Large Search Form (Center) -->
                <div class="header-search">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="search-input-wrapper">
                            <span class="search-icon"><?php echo lyststyle_get_icon( 'search' ); ?></span>
                            <input
                                type="search"
                                class="search-field"
                                placeholder="<?php echo esc_attr_x( "Search (e.g. 'Modern chelsea boots')", 'placeholder', 'lyststyle-aggregator' ); ?>"
                                value="<?php echo get_search_query(); ?>"
                                name="s"
                                aria-label="<?php esc_attr_e( 'Search products', 'lyststyle-aggregator' ); ?>"
                            />
                            <button type="submit" class="search-submit">
                                <span class="screen-reader-text"><?php esc_html_e( 'Search', 'lyststyle-aggregator' ); ?></span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Header Actions (Right Side) -->
                <div class="header-actions">
                    <!-- Notification Bell -->
                    <a href="#" class="header-action-link" id="notifications" aria-label="<?php esc_attr_e( 'Notifications', 'lyststyle-aggregator' ); ?>">
                        <span class="icon-bell">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                        </span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Notifications', 'lyststyle-aggregator' ); ?></span>
                    </a>

                    <!-- Wishlist Heart Icon -->
                    <?php
                    $wishlist_url = lyststyle_get_page_url( 'lyststyle_wishlist_page' );
                    if ( ! $wishlist_url ) {
                        $wishlist_url = home_url( '/wishlist' );
                    }
                    ?>
                    <a href="<?php echo esc_url( $wishlist_url ); ?>" class="header-action-link" id="wishlist-link" aria-label="<?php esc_attr_e( 'Wishlist', 'lyststyle-aggregator' ); ?>">
                        <span class="icon-heart"><?php echo lyststyle_get_icon( 'heart' ); ?></span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Wishlist', 'lyststyle-aggregator' ); ?></span>
                    </a>

                    <!-- Account Icon with Dropdown -->
                    <div class="account-dropdown-wrapper">
                        <button class="header-action-link" id="user-account" aria-expanded="false" aria-haspopup="true" aria-label="<?php esc_attr_e( 'Account menu', 'lyststyle-aggregator' ); ?>">
                            <span class="icon-user"><?php echo lyststyle_get_icon( 'user' ); ?></span>
                            <span class="screen-reader-text"><?php esc_html_e( 'Account', 'lyststyle-aggregator' ); ?></span>
                        </button>

                        <div class="account-dropdown" id="account-dropdown" aria-label="<?php esc_attr_e( 'Account menu', 'lyststyle-aggregator' ); ?>">
                            <?php if ( is_user_logged_in() ) : ?>
                                <!-- Logged In State -->
                                <?php
                                $account_url = lyststyle_get_page_url( 'lyststyle_account_page' );
                                $wishlist_url = lyststyle_get_page_url( 'lyststyle_wishlist_page' );

                                if ( ! $account_url ) {
                                    $account_url = home_url( '/my-account' );
                                }
                                if ( ! $wishlist_url ) {
                                    $wishlist_url = home_url( '/wishlist' );
                                }
                                ?>
                                <ul class="account-menu">
                                    <li><a href="<?php echo esc_url( $account_url ); ?>"><?php esc_html_e( 'My Account', 'lyststyle-aggregator' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( $wishlist_url ); ?>"><?php esc_html_e( 'Wishlist', 'lyststyle-aggregator' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Log out', 'lyststyle-aggregator' ); ?></a></li>
                                </ul>
                            <?php else : ?>
                                <!-- Logged Out State -->
                                <?php
                                $login_url = lyststyle_get_page_url( 'lyststyle_login_page' );
                                if ( ! $login_url ) {
                                    $login_url = wp_login_url();
                                }
                                ?>
                                <div class="account-login-prompt">
                                    <a href="<?php echo esc_url( $login_url ); ?>" class="btn-sign-up">
                                        <?php esc_html_e( 'Sign up or log in', 'lyststyle-aggregator' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Currency Switcher -->
                    <div class="currency-selector">
                        <label for="currency-select" class="screen-reader-text"><?php esc_html_e( 'Select currency', 'lyststyle-aggregator' ); ?></label>
                        <select id="currency-select" name="currency" aria-label="<?php esc_attr_e( 'Currency selector', 'lyststyle-aggregator' ); ?>">
                            <option value="GBP" selected><?php esc_html_e( 'GB · £', 'lyststyle-aggregator' ); ?></option>
                            <option value="USD"><?php esc_html_e( 'US · $', 'lyststyle-aggregator' ); ?></option>
                            <option value="EUR"><?php esc_html_e( 'EU · €', 'lyststyle-aggregator' ); ?></option>
                        </select>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle" aria-controls="mobile-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'lyststyle-aggregator' ); ?>">
                        <span class="icon-menu"><?php echo lyststyle_get_icon( 'menu' ); ?></span>
                        <span class="icon-close"><?php echo lyststyle_get_icon( 'close' ); ?></span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'lyststyle-aggregator' ); ?></span>
                    </button>
                </div>
            </div>

            <!-- Primary Navigation Menu -->
            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'lyststyle-aggregator' ); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary_menu',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            // Default fallback menu items
                            echo '<ul id="primary-menu" class="primary-menu">';
                            echo '<li><a href="' . esc_url( home_url( '/category/clothing' ) ) . '">' . esc_html__( 'Clothing', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/category/shoes' ) ) . '">' . esc_html__( 'Shoes', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/category/accessories' ) ) . '">' . esc_html__( 'Accessories', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/category/bags' ) ) . '">' . esc_html__( 'Bags', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/category/jewellery' ) ) . '">' . esc_html__( 'Jewellery', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/brands' ) ) . '">' . esc_html__( 'Brands', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/guides' ) ) . '">' . esc_html__( 'Guides', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '</ul>';
                        },
                    )
                );
                ?>
            </nav>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-navigation" class="mobile-navigation" aria-label="<?php esc_attr_e( 'Mobile navigation', 'lyststyle-aggregator' ); ?>">
            <div class="mobile-menu-wrapper">
                <!-- Mobile Search -->
                <div class="mobile-search">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="search-input-wrapper">
                            <span class="search-icon"><?php echo lyststyle_get_icon( 'search' ); ?></span>
                            <input
                                type="search"
                                class="search-field"
                                placeholder="<?php echo esc_attr_x( "Search products...", 'placeholder', 'lyststyle-aggregator' ); ?>"
                                value="<?php echo get_search_query(); ?>"
                                name="s"
                            />
                        </div>
                    </form>
                </div>

                <!-- Mobile Menu -->
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary_menu',
                        'menu_id'        => 'mobile-menu',
                        'menu_class'     => 'mobile-menu',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            echo '<ul id="mobile-menu" class="mobile-menu">';
                            echo '<li><a href="' . esc_url( home_url( '/category/clothing' ) ) . '">' . esc_html__( 'Clothing', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/category/shoes' ) ) . '">' . esc_html__( 'Shoes', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/category/accessories' ) ) . '">' . esc_html__( 'Accessories', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/category/bags' ) ) . '">' . esc_html__( 'Bags', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/category/jewellery' ) ) . '">' . esc_html__( 'Jewellery', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/brands' ) ) . '">' . esc_html__( 'Brands', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/guides' ) ) . '">' . esc_html__( 'Guides', 'lyststyle-aggregator' ) . '</a></li>';
                            echo '</ul>';
                        },
                    )
                );
                ?>

                <!-- Mobile Account Links -->
                <div class="mobile-account-links">
                    <?php if ( is_user_logged_in() ) : ?>
                        <?php
                        $account_url = lyststyle_get_page_url( 'lyststyle_account_page' );
                        $wishlist_url = lyststyle_get_page_url( 'lyststyle_wishlist_page' );

                        if ( ! $account_url ) {
                            $account_url = home_url( '/my-account' );
                        }
                        if ( ! $wishlist_url ) {
                            $wishlist_url = home_url( '/wishlist' );
                        }
                        ?>
                        <a href="<?php echo esc_url( $account_url ); ?>" class="mobile-account-link">
                            <?php echo lyststyle_get_icon( 'user' ); ?>
                            <?php esc_html_e( 'My Account', 'lyststyle-aggregator' ); ?>
                        </a>
                        <a href="<?php echo esc_url( $wishlist_url ); ?>" class="mobile-account-link">
                            <?php echo lyststyle_get_icon( 'heart' ); ?>
                            <?php esc_html_e( 'Wishlist', 'lyststyle-aggregator' ); ?>
                        </a>
                        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="mobile-account-link">
                            <?php esc_html_e( 'Log out', 'lyststyle-aggregator' ); ?>
                        </a>
                    <?php else : ?>
                        <?php
                        $login_url = lyststyle_get_page_url( 'lyststyle_login_page' );
                        if ( ! $login_url ) {
                            $login_url = wp_login_url();
                        }
                        ?>
                        <a href="<?php echo esc_url( $login_url ); ?>" class="btn-sign-up mobile-login-btn">
                            <?php esc_html_e( 'Sign up or log in', 'lyststyle-aggregator' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div id="content" class="site-content">
