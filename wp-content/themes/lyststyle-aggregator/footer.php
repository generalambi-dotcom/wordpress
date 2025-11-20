    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="footer-container">
            <div class="footer-widgets">

                <!-- Trust/App Downloads Column -->
                <div class="footer-column footer-trust">
                    <!-- Trustpilot Widget -->
                    <div class="trustpilot-widget">
                        <div class="trustpilot-logo">
                            <svg width="98" height="24" viewBox="0 0 98 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 0L15.708 8.292L24 9.528L18 15.708L19.416 24L12 19.764L4.584 24L6 15.708L0 9.528L8.292 8.292L12 0Z" fill="#00B67A"/>
                            </svg>
                            <span>Trustpilot</span>
                        </div>
                        <div class="trustpilot-stars" role="img" aria-label="<?php echo esc_attr( get_theme_mod( 'lyststyle_trustscore_text', 'TrustScore 4.1' ) ); ?>">
                            <?php for ( $i = 0; $i < 4; $i++ ) : ?>
                                <span class="star filled" aria-hidden="true">★</span>
                            <?php endfor; ?>
                            <span class="star" aria-hidden="true">★</span>
                        </div>
                        <p class="trustscore"><?php echo esc_html( get_theme_mod( 'lyststyle_trustscore_text', 'TrustScore 4.1' ) ); ?></p>
                    </div>

                    <!-- Social Links -->
                    <?php
                    $social_platforms = array( 'instagram', 'tiktok', 'x', 'facebook' );
                    $has_social_links = false;
                    foreach ( $social_platforms as $platform ) {
                        if ( get_theme_mod( 'lyststyle_social_' . $platform ) ) {
                            $has_social_links = true;
                            break;
                        }
                    }

                    if ( $has_social_links ) :
                    ?>
                        <div class="social-links">
                            <?php
                            foreach ( $social_platforms as $platform ) {
                                $url = get_theme_mod( 'lyststyle_social_' . $platform );
                                if ( $url ) {
                                    $platform_name = ucfirst( $platform );
                                    if ( $platform === 'x' ) {
                                        $platform_name = 'X (Twitter)';
                                    }
                                    echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="' . esc_attr( sprintf( __( 'Follow us on %s', 'lyststyle-aggregator' ), $platform_name ) ) . '">';
                                    echo lyststyle_get_social_icon( $platform );
                                    echo '</a>';
                                }
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- App Download Badges -->
                    <div class="app-downloads">
                        <a href="#" class="app-badge app-store" aria-label="<?php esc_attr_e( 'Download on the App Store', 'lyststyle-aggregator' ); ?>">
                            <span><?php esc_html_e( 'Download on the', 'lyststyle-aggregator' ); ?></span>
                            <strong><?php esc_html_e( 'App Store', 'lyststyle-aggregator' ); ?></strong>
                        </a>
                        <a href="#" class="app-badge google-play" aria-label="<?php esc_attr_e( 'Get it on Google Play', 'lyststyle-aggregator' ); ?>">
                            <span><?php esc_html_e( 'GET IT ON', 'lyststyle-aggregator' ); ?></span>
                            <strong><?php esc_html_e( 'Google Play', 'lyststyle-aggregator' ); ?></strong>
                        </a>
                    </div>

                    <p class="app-description"><?php esc_html_e( 'Learn about the Lyst app for iPhone, iPad and Android.', 'lyststyle-aggregator' ); ?></p>
                </div>

                <!-- International Column -->
                <div class="footer-column footer-international">
                    <h3 class="footer-heading"><?php esc_html_e( 'INTERNATIONAL', 'lyststyle-aggregator' ); ?></h3>
                    <nav aria-label="<?php esc_attr_e( 'International sites', 'lyststyle-aggregator' ); ?>">
                        <ul class="footer-links">
                            <li><a href="#" hreflang="en-AU"><?php esc_html_e( 'Lyst - AU', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="en-CA"><?php esc_html_e( 'Lyst - CA', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="en-US"><?php esc_html_e( 'Lyst - US', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="de-AT"><?php esc_html_e( 'Lyst - Österreich', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="de-CH"><?php esc_html_e( 'Lyst - Schweiz', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="de-DE"><?php esc_html_e( 'Lyst - Deutschland', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="es-ES"><?php esc_html_e( 'Lyst - España', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="fr-FR"><?php esc_html_e( 'Lyst - France', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="it-IT"><?php esc_html_e( 'Lyst - Italia', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="ja-JP"><?php esc_html_e( 'Lyst - 日本', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="nl-BE"><?php esc_html_e( 'Lyst - België', 'lyststyle-aggregator' ); ?></a></li>
                            <li><a href="#" hreflang="nl-NL"><?php esc_html_e( 'Lyst - Nederland', 'lyststyle-aggregator' ); ?></a></li>
                        </ul>
                    </nav>
                </div>

                <!-- Help & Info Column -->
                <div class="footer-column footer-help">
                    <h3 class="footer-heading"><?php esc_html_e( 'HELP & INFO', 'lyststyle-aggregator' ); ?></h3>
                    <nav aria-label="<?php esc_attr_e( 'Help and information', 'lyststyle-aggregator' ); ?>">
                        <?php
                        // Use help_info_menu navigation location
                        $has_menu = wp_nav_menu(
                            array(
                                'theme_location' => 'help_info_menu',
                                'menu_id'        => 'help-info-menu',
                                'menu_class'     => 'footer-links',
                                'container'      => false,
                                'fallback_cb'    => '__return_false',
                                'echo'           => false,
                            )
                        );

                        if ( $has_menu ) {
                            echo $has_menu;
                        } else {
                            // Fallback menu if no menu is assigned
                            ?>
                            <ul class="footer-links">
                                <li><a href="#"><?php esc_html_e( 'Help centre', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'About us', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Shipping policy', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Returns policy', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Payments', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Refund policy', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Developers', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Careers', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Contact', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Terms & conditions', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Privacy & cookie policy', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Intellectual property', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Categories', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Become a partner', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Lyst Insights', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Lyst News', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Cookie Settings', 'lyststyle-aggregator' ); ?></a></li>
                                <li><a href="#"><?php esc_html_e( 'Modern slavery statement', 'lyststyle-aggregator' ); ?></a></li>
                            </ul>
                            <?php
                        }
                        ?>
                    </nav>
                </div>
            </div>

            <!-- Footer Bottom / Copyright -->
            <div class="footer-bottom">
                <p class="copyright">
                    <?php
                    $footer_text = get_theme_mod( 'lyststyle_footer_text', '' );
                    if ( $footer_text ) {
                        echo esc_html( $footer_text );
                    } else {
                        /* translators: %s: Current year */
                        printf( esc_html__( '© %s Lyst', 'lyststyle-aggregator' ), esc_html( gmdate( 'Y' ) ) );
                    }
                    ?>
                </p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
