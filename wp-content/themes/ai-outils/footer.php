<?php
/**
 * The footer template
 *
 * @package AI_Outils
 */
?>

    </div><!-- #content -->

    <footer class="site-footer">
        <div class="container">
            <!-- Footer Top Section -->
            <div class="footer-grid">
                <!-- Brand & Newsletter Column -->
                <div class="footer-column footer-brand">
                    <div class="site-logo">
                        <?php bloginfo( 'name' ); ?>
                    </div>
                    <p class="footer-tagline">
                        <?php
                        $tagline = get_bloginfo( 'description' );
                        echo $tagline ? esc_html( $tagline ) : esc_html__( 'Explore the Future of AI: Tools, Insights, and Innovations for a Smarter Tomorrow.', 'ai-outils' );
                        ?>
                    </p>

                    <!-- Newsletter Form -->
                    <form class="newsletter-form" action="#" method="post">
                        <input
                            type="email"
                            name="newsletter_email"
                            class="form-input"
                            placeholder="<?php esc_attr_e( 'Your email address', 'ai-outils' ); ?>"
                            required
                        >
                        <button type="submit" class="btn btn-primary">
                            <?php _e( 'Subscribe', 'ai-outils' ); ?>
                        </button>
                    </form>
                </div>

                <!-- Navigation Column -->
                <div class="footer-column">
                    <h4><?php _e( 'Navigation', 'ai-outils' ); ?></h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-1',
                        'container'      => false,
                        'menu_class'     => '',
                        'fallback_cb'    => function() {
                            echo '<ul>';
                            echo '<li><a href="#">Terms & Conditions</a></li>';
                            echo '<li><a href="#">Cookie Policy</a></li>';
                            echo '<li><a href="#">Privacy Policy</a></li>';
                            echo '</ul>';
                        },
                    ) );
                    ?>
                </div>

                <!-- Pages Column -->
                <div class="footer-column">
                    <h4><?php _e( 'Pages', 'ai-outils' ); ?></h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-2',
                        'container'      => false,
                        'menu_class'     => '',
                        'fallback_cb'    => function() {
                            echo '<ul>';
                            echo '<li><a href="#">Marketing & Growth Tools</a></li>';
                            echo '<li><a href="#">Productivity & Workflow Automators</a></li>';
                            echo '<li><a href="#">Content & Writing Assistant</a></li>';
                            echo '<li><a href="#">Visual & Design Creators</a></li>';
                            echo '<li><a href="#">Video & Audio Creation</a></li>';
                            echo '<li><a href="#">AI Code Assistants</a></li>';
                            echo '</ul>';
                        },
                    ) );
                    ?>
                </div>

                <!-- Resources Column -->
                <div class="footer-column">
                    <h4><?php _e( 'Resources', 'ai-outils' ); ?></h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-3',
                        'container'      => false,
                        'menu_class'     => '',
                        'fallback_cb'    => function() {
                            echo '<ul>';
                            echo '<li><a href="#">Latest AI News</a></li>';
                            echo '<li><a href="#">AI Glossary</a></li>';
                            echo '</ul>';
                        },
                    ) );
                    ?>
                </div>

                <!-- Social Column -->
                <div class="footer-column">
                    <h4><?php _e( 'Social', 'ai-outils' ); ?></h4>
                    <ul>
                        <li><a href="#" target="_blank" rel="noopener noreferrer">Facebook</a></li>
                        <li><a href="#" target="_blank" rel="noopener noreferrer">Twitter</a></li>
                        <li><a href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>" target="_blank" rel="noopener noreferrer">RSS</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>
                    &copy; <?php echo date( 'Y' ); ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php bloginfo( 'name' ); ?>
                    </a>
                    <?php _e( '- All rights reserved.', 'ai-outils' ); ?>
                </p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
