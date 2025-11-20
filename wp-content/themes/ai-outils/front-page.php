<?php
/**
 * The front page template
 *
 * This template displays the homepage with hero section,
 * featured tools, and browse by category sections.
 *
 * @package AI_Outils
 */

get_header();
?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <!-- Hero Badge -->
            <div class="hero-badge">
                <?php
                $tools_count = ai_outils_get_tools_count();
                printf( __( 'NEW %d+ AI tools added this month', 'ai-outils' ), $tools_count );
                ?>
            </div>

            <!-- Hero Title -->
            <h1><?php _e( 'The #1 Place to Discover', 'ai-outils' ); ?><br><span style="color: #10B981;"><?php _e( 'AI Tools', 'ai-outils' ); ?></span></h1>

            <!-- Hero Subtitle -->
            <p class="subtitle">
                <?php
                printf(
                    __( 'Unlock Free Access to %d+ curated AI tools', 'ai-outils' ),
                    $tools_count
                );
                ?>
            </p>

            <!-- Hero Description -->
            <p class="description">
                <?php _e( 'Browse the latest and best AI tools by category. Save time, make smarter decisions, and stay ahead of the curve with verified recommendations — all in one place.', 'ai-outils' ); ?>
            </p>

            <!-- Email Form -->
            <?php if ( ! ai_outils_is_member() ) : ?>
                <form class="email-form" method="post" action="<?php echo esc_url( wp_registration_url() ); ?>">
                    <input
                        type="email"
                        name="user_email"
                        class="form-input"
                        placeholder="<?php esc_attr_e( 'Enter your email...', 'ai-outils' ); ?>"
                        required
                    >
                    <button type="submit" class="btn btn-secondary btn-lg">
                        <?php _e( 'Get your free membership', 'ai-outils' ); ?>
                    </button>
                </form>
                <p class="form-text-sm" style="color: rgba(255,255,255,0.9); text-align: center;">
                    <?php
                    printf(
                        __( 'Already have an account? <a href="%s" style="color: white; text-decoration: underline;">Sign in.</a>', 'ai-outils' ),
                        esc_url( wp_login_url() )
                    );
                    ?>
                </p>
            <?php else : ?>
                <div style="margin-top: 2rem;">
                    <a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" class="btn btn-secondary btn-lg">
                        <?php _e( 'Browse All Tools', 'ai-outils' ); ?>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/members-dashboard/' ) ); ?>" class="btn btn-outline btn-lg" style="background: rgba(255,255,255,0.2); color: white; border-color: rgba(255,255,255,0.3);">
                        <?php _e( 'Go to Dashboard', 'ai-outils' ); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Social Proof -->
            <div class="social-proof">
                <div class="avatar-group">
                    <div class="avatar">A</div>
                    <div class="avatar">B</div>
                    <div class="avatar">C</div>
                    <div class="avatar">D</div>
                </div>
                <span style="color: rgba(255,255,255,0.95);"><?php _e( 'Join 1,800+ founders', 'ai-outils' ); ?></span>
                <div class="star-rating">★★★★★</div>
            </div>
        </div>
    </section>

    <!-- All Resources Section -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-xl"><?php _e( 'All Resources', 'ai-outils' ); ?></h2>

            <div class="two-column-layout">
                <!-- Category Sidebar -->
                <aside class="sidebar">
                    <ul class="sidebar-menu">
                        <li>
                            <a href="#popular" class="active">
                                <span style="margin-right: 0.5rem;">⭐</span>
                                <?php _e( 'Popular Tools', 'ai-outils' ); ?>
                            </a>
                        </li>
                        <?php
                        // Get categories for sidebar
                        $categories = get_terms( array(
                            'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
                            'hide_empty' => true,
                            'number'     => 8,
                        ) );

                        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                            foreach ( $categories as $category ) :
                                ?>
                                <li>
                                    <a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
                                        <span style="margin-right: 0.5rem;">🤖</span>
                                        <?php echo esc_html( $category->name ); ?>
                                    </a>
                                </li>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </aside>

                <!-- Tools Grid -->
                <div>
                    <?php
                    // Query featured/popular tools
                    $featured_tools = new WP_Query( array(
                        'post_type'      => AI_OUTILS_CPT_SLUG,
                        'posts_per_page' => 6,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ) );

                    if ( $featured_tools->have_posts() ) :
                        ?>
                        <div class="card-grid">
                            <?php
                            while ( $featured_tools->have_posts() ) :
                                $featured_tools->the_post();
                                get_template_part( 'template-parts/tool-card' );
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>

                        <div style="text-align: center; margin-top: 2rem;">
                            <a href="<?php echo esc_url( get_post_type_archive_link( AI_OUTILS_CPT_SLUG ) ); ?>" class="btn btn-primary">
                                <?php _e( 'View All Tools →', 'ai-outils' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Browse by Category Section -->
    <section class="section" style="background: var(--color-bg-gray);">
        <div class="container">
            <div class="text-center mb-xl">
                <h2><?php _e( 'Browse by Category', 'ai-outils' ); ?></h2>
                <p style="color: var(--color-text-light); max-width: 700px; margin: 0 auto;">
                    <?php
                    $categories_count = ai_outils_get_categories_count();
                    printf(
                        __( 'Explore our collection of %d AI tools organized into %d specialized categories.', 'ai-outils' ),
                        $tools_count,
                        $categories_count
                    );
                    ?>
                </p>
            </div>

            <?php
            // Get all categories for grid
            $all_categories = get_terms( array(
                'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
                'hide_empty' => true,
            ) );

            if ( ! empty( $all_categories ) && ! is_wp_error( $all_categories ) ) :
                ?>
                <div class="card-grid">
                    <?php foreach ( $all_categories as $category ) : ?>
                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="card category-card">
                            <div class="category-icon">
                                <?php
                                // Icon mapping - you can customize this
                                $icons = array(
                                    'ai-agents'             => '🤖',
                                    'ai-code-assistants'    => '💻',
                                    'automation'            => '⚙️',
                                    'content-writing'       => '✍️',
                                    'presentation'          => '📊',
                                    'social-media'          => '📱',
                                    'video-editing'         => '🎬',
                                    'writing'               => '📝',
                                );

                                $icon = isset( $icons[ $category->slug ] ) ? $icons[ $category->slug ] : '📊';
                                echo $icon;
                                ?>
                            </div>
                            <h3 class="category-name"><?php echo esc_html( $category->name ); ?></h3>
                            <p class="category-count">
                                <?php printf( _n( '%d Tool', '%d Tools', $category->count, 'ai-outils' ), $category->count ); ?>
                            </p>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <a href="<?php echo esc_url( home_url( '/ai-tools-directory/' ) ); ?>" class="btn btn-primary">
                        <?php _e( 'View Full Directory →', 'ai-outils' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Latest Blog Posts (Optional) -->
    <?php
    $latest_posts = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
    ) );

    if ( $latest_posts->have_posts() ) :
        ?>
        <section class="section">
            <div class="container">
                <div class="text-center mb-xl">
                    <h2><?php _e( 'Latest Resources', 'ai-outils' ); ?></h2>
                    <p style="color: var(--color-text-light);">
                        <?php _e( 'Stay updated with the latest AI news, guides, and insights.', 'ai-outils' ); ?>
                    </p>
                </div>

                <div class="card-grid">
                    <?php
                    while ( $latest_posts->have_posts() ) :
                        $latest_posts->the_post();
                        get_template_part( 'template-parts/blog-card' );
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-outline">
                        <?php _e( 'View All Posts →', 'ai-outils' ); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php
    endif;
    ?>
</main>

<?php
get_footer();
