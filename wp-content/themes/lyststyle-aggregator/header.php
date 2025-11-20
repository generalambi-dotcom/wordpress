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

    <header id="masthead" class="site-header">
        <div class="header-container">
            <div class="header-top">
                <div class="header-logo">
                    <?php
                    $logo = get_theme_mod( 'lyststyle_logo' );
                    if ( $logo ) :
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
                            <img src="<?php echo esc_url( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title" rel="home">
                            <strong>LYSTSTYLE</strong>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="header-navigation">
                    <nav id="site-navigation" class="main-navigation">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => function() {
                                echo '<ul id="primary-menu" class="menu">';
                                echo '<li><a href="#">Clothing</a></li>';
                                echo '<li><a href="#">Shoes</a></li>';
                                echo '<li><a href="#">Accessories</a></li>';
                                echo '<li><a href="#">Bags</a></li>';
                                echo '<li><a href="#">Jewellery</a></li>';
                                echo '<li><a href="#">Brands</a></li>';
                                echo '<li><a href="' . esc_url( home_url( '/guides' ) ) . '">Guides</a></li>';
                                echo '</ul>';
                            },
                        ) );
                        ?>
                    </nav>
                </div>

                <div class="header-search">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="search-input-wrapper">
                            <span class="search-icon"><?php echo lyststyle_get_icon( 'search' ); ?></span>
                            <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search (e.g. \'Modern chelsea boots\')', 'placeholder', 'lyststyle-aggregator' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        </div>
                    </form>
                </div>

                <div class="header-actions">
                    <a href="#" class="header-action-link" id="notifications">
                        <span class="icon-bell">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Notifications', 'lyststyle-aggregator' ); ?></span>
                    </a>

                    <a href="<?php echo esc_url( home_url( '/wishlist' ) ); ?>" class="header-action-link" id="wishlist-link">
                        <span class="icon-heart"><?php echo lyststyle_get_icon( 'heart' ); ?></span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Wishlist', 'lyststyle-aggregator' ); ?></span>
                    </a>

                    <a href="#" class="header-action-link" id="user-account">
                        <span class="icon-user"><?php echo lyststyle_get_icon( 'user' ); ?></span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Account', 'lyststyle-aggregator' ); ?></span>
                    </a>

                    <button class="btn-sign-up"><?php esc_html_e( 'Sign up or log in', 'lyststyle-aggregator' ); ?></button>

                    <div class="currency-selector">
                        <select id="currency-select" name="currency">
                            <option value="GBP">GB · £</option>
                            <option value="USD">US · $</option>
                            <option value="EUR">EU · €</option>
                        </select>
                    </div>
                </div>

                <button class="mobile-menu-toggle" aria-controls="mobile-navigation" aria-expanded="false">
                    <span class="icon-menu"><?php echo lyststyle_get_icon( 'menu' ); ?></span>
                    <span class="icon-close"><?php echo lyststyle_get_icon( 'close' ); ?></span>
                    <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'lyststyle-aggregator' ); ?></span>
                </button>
            </div>

            <!-- Secondary Navigation (Categories) -->
            <div class="header-categories">
                <nav class="categories-navigation">
                    <ul class="categories-menu">
                        <li><a href="<?php echo esc_url( home_url( '/category/clothing' ) ); ?>"><?php esc_html_e( 'Clothing', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/category/shoes' ) ); ?>"><?php esc_html_e( 'Shoes', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/category/accessories' ) ); ?>"><?php esc_html_e( 'Accessories', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/category/bags' ) ); ?>"><?php esc_html_e( 'Bags', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/category/jewellery' ) ); ?>"><?php esc_html_e( 'Jewellery', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/brands' ) ); ?>"><?php esc_html_e( 'Brands', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/guides' ) ); ?>"><?php esc_html_e( 'Guides', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'The Lyst Index', 'lyststyle-aggregator' ); ?></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-navigation" class="mobile-navigation">
            <div class="mobile-menu-wrapper">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                ) );
                ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content">
