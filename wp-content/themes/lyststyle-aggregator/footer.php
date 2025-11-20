    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="footer-container">
            <div class="footer-widgets">
                <div class="footer-column footer-trustpilot">
                    <div class="trustpilot-widget">
                        <div class="trustpilot-logo">
                            <svg width="98" height="24" viewBox="0 0 98 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 0L15.708 8.292L24 9.528L18 15.708L19.416 24L12 19.764L4.584 24L6 15.708L0 9.528L8.292 8.292L12 0Z" fill="#00B67A"/>
                            </svg>
                            <span>Trustpilot</span>
                        </div>
                        <div class="trustpilot-stars">
                            <?php for ( $i = 0; $i < 4; $i++ ) : ?>
                                <span class="star filled">★</span>
                            <?php endfor; ?>
                            <span class="star">★</span>
                        </div>
                        <p class="trustscore"><?php echo esc_html( get_theme_mod( 'lyststyle_trustscore_text', 'TrustScore 4.1' ) ); ?></p>
                    </div>

                    <div class="social-links">
                        <?php
                        $social_platforms = array( 'instagram', 'tiktok', 'x', 'facebook' );
                        foreach ( $social_platforms as $platform ) {
                            $url = get_theme_mod( 'lyststyle_social_' . $platform );
                            if ( $url ) {
                                echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="social-link">';
                                echo lyststyle_get_social_icon( $platform );
                                echo '</a>';
                            }
                        }
                        ?>
                    </div>

                    <div class="app-downloads">
                        <a href="#" class="app-badge app-store">
                            <span>Download on the</span>
                            <strong>App Store</strong>
                        </a>
                        <a href="#" class="app-badge google-play">
                            <span>GET IT ON</span>
                            <strong>Google Play</strong>
                        </a>
                    </div>

                    <p class="app-description"><?php esc_html_e( 'Learn about the Lyst app for iPhone, iPad and Android.', 'lyststyle-aggregator' ); ?></p>
                </div>

                <div class="footer-column footer-international">
                    <h3 class="footer-heading"><?php esc_html_e( 'INTERNATIONAL', 'lyststyle-aggregator' ); ?></h3>
                    <ul class="footer-links">
                        <li><a href="#"><?php esc_html_e( 'Lyst - AU', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - CA', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - US', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - Österreich', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - Schweiz', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - Deutschland', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - España', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - France', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - Italia', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - 日本', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - België', 'lyststyle-aggregator' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Lyst - Nederland', 'lyststyle-aggregator' ); ?></a></li>
                    </ul>
                </div>

                <div class="footer-column footer-help">
                    <h3 class="footer-heading"><?php esc_html_e( 'HELP AND INFO', 'lyststyle-aggregator' ); ?></h3>
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
                </div>
            </div>

            <div class="footer-bottom">
                <p class="copyright">
                    <?php echo esc_html( get_theme_mod( 'lyststyle_footer_text', '© ' . date( 'Y' ) . ' Lyst' ) ); ?>
                </p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
