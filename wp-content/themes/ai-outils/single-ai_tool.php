<?php
/**
 * The template for displaying single AI Tool
 *
 * Displays the full single tool page with:
 * - Hero section (logo, title, rating, pricing, video/image, actions)
 * - Content sections (overview, features)
 * - Similar tools
 *
 * @package AI_Outils
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Output JSON-LD schema in footer (before closing body)
add_action( 'wp_footer', 'ai_outils_tool_schema', 5 );

while ( have_posts() ) :
    the_post();
    $post_id = get_the_ID();
    ?>

    <main id="main" class="site-main single-tool-page">
        <div class="container">
            <div class="section-sm">

                <?php
                /**
                 * Tool Hero Section
                 *
                 * Uses template-parts/tool-hero.php
                 * Includes: logo, title, rating, verified badge, description,
                 * categories, pricing, action buttons, social links, video/image
                 */
                get_template_part( 'template-parts/tool-hero' );
                ?>

                <!-- Tool Content Sections -->
                <div class="tool-content">

                    <?php if ( get_the_content() ) : ?>
                        <!-- Overview Section -->
                        <section class="tool-content-section">
                            <h2><?php esc_html_e( 'Overview', 'ai-outils' ); ?></h2>
                            <div class="entry-content">
                                <?php
                                the_content();

                                wp_link_pages( array(
                                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ai-outils' ),
                                    'after'  => '</div>',
                                ) );
                                ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php
                    /**
                     * Key Features Section
                     *
                     * This section can be populated via:
                     * - Custom fields (ACF or native)
                     * - Post content with specific headings
                     * - Plugin integration
                     *
                     * Hook: ai_outils_tool_features allows plugins to inject features
                     */
                    if ( has_action( 'ai_outils_tool_features' ) ) :
                        ?>
                        <section class="tool-content-section">
                            <h2><?php esc_html_e( 'Key Features', 'ai-outils' ); ?></h2>
                            <div class="tool-features">
                                <?php do_action( 'ai_outils_tool_features', $post_id ); ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php
                    /**
                     * Pros and Cons Section (if available via custom fields)
                     *
                     * Hook: ai_outils_tool_pros_cons allows plugins to inject content
                     */
                    if ( has_action( 'ai_outils_tool_pros_cons' ) ) :
                        ?>
                        <section class="tool-content-section">
                            <h2><?php esc_html_e( 'Pros & Cons', 'ai-outils' ); ?></h2>
                            <div class="tool-pros-cons">
                                <?php do_action( 'ai_outils_tool_pros_cons', $post_id ); ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php
                    /**
                     * Pricing Details Section (if available)
                     *
                     * Hook: ai_outils_tool_pricing_details
                     */
                    if ( has_action( 'ai_outils_tool_pricing_details' ) ) :
                        ?>
                        <section class="tool-content-section">
                            <h2><?php esc_html_e( 'Pricing', 'ai-outils' ); ?></h2>
                            <div class="tool-pricing-details">
                                <?php do_action( 'ai_outils_tool_pricing_details', $post_id ); ?>
                            </div>
                        </section>
                    <?php endif; ?>

                </div>

                <?php
                /**
                 * Similar/Related Tools Section
                 */
                $similar_tools = ai_outils_get_similar_tools( $post_id, 3 );

                if ( $similar_tools->have_posts() ) :
                    ?>
                    <section class="tool-content-section similar-tools-section">
                        <h2><?php esc_html_e( 'Similar AI Tools', 'ai-outils' ); ?></h2>
                        <div class="card-grid similar-tools-grid">
                            <?php
                            while ( $similar_tools->have_posts() ) :
                                $similar_tools->the_post();
                                get_template_part( 'template-parts/tool-card' );
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>

                        <!-- View More Link -->
                        <?php
                        $terms = get_the_terms( $post_id, AI_OUTILS_TAXONOMY_SLUG );
                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
                            $primary_term = $terms[0];
                            ?>
                            <div class="similar-tools-more">
                                <a href="<?php echo esc_url( get_term_link( $primary_term ) ); ?>" class="btn btn-outline">
                                    <?php printf( esc_html__( 'View All %s Tools', 'ai-outils' ), esc_html( $primary_term->name ) ); ?>
                                    <span aria-hidden="true"> &rarr;</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>

                <?php
                /**
                 * Comments Section (if enabled)
                 */
                if ( comments_open() || get_comments_number() ) :
                    ?>
                    <section class="tool-content-section tool-comments-section">
                        <?php comments_template(); ?>
                    </section>
                <?php endif; ?>

            </div>
        </div>
    </main>

<?php
endwhile;

get_footer();
