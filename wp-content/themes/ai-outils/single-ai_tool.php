<?php
/**
 * The template for displaying single AI Tool
 *
 * @package AI_Outils
 */

get_header();

// Add JSON-LD schema in head
add_action( 'wp_head', function() {
    ai_outils_tool_schema();
} );

while ( have_posts() ) :
    the_post();
    ?>

    <main class="site-main">
        <div class="container">
            <div class="section-sm">
                <!-- Tool Hero Section -->
                <div class="tool-hero">
                    <div class="tool-hero-grid">
                        <!-- Left Column: Tool Info -->
                        <div class="tool-hero-left">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img
                                    src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'tool-logo' ) ); ?>"
                                    alt="<?php echo esc_attr( get_the_title() ); ?>"
                                    class="tool-hero-logo"
                                    loading="eager"
                                >
                            <?php endif; ?>

                            <h1 class="tool-hero-title"><?php the_title(); ?></h1>

                            <!-- Rating & Verified Badge -->
                            <div class="tool-hero-rating">
                                <?php
                                $rating = ai_outils_get_tool_meta( get_the_ID(), 'rating' );
                                $verified = ai_outils_get_tool_meta( get_the_ID(), 'verified' );

                                if ( $rating ) {
                                    echo ai_outils_star_rating( $rating );
                                }

                                if ( $verified ) {
                                    echo '<span class="badge badge-verified">✓ Verified</span>';
                                }
                                ?>
                            </div>

                            <!-- Short Pitch -->
                            <div class="tool-hero-pitch">
                                <?php echo esc_html( ai_outils_get_excerpt( get_the_ID(), 50 ) ); ?>
                            </div>

                            <!-- Categories -->
                            <?php echo ai_outils_get_tool_categories( get_the_ID() ); ?>

                            <!-- Pricing Model -->
                            <?php
                            $pricing = ai_outils_get_tool_meta( get_the_ID(), 'pricing_model' );
                            if ( $pricing ) :
                                ?>
                                <div class="tool-pricing" style="margin-top: 1rem;">
                                    <strong><?php _e( 'Pricing:', 'ai-outils' ); ?></strong>
                                    <span><?php echo esc_html( $pricing ); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Action Buttons -->
                            <div class="tool-hero-actions">
                                <?php
                                $website_url = ai_outils_get_tool_meta( get_the_ID(), 'website_url' );
                                if ( $website_url ) :
                                    ?>
                                    <a href="<?php echo esc_url( $website_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg">
                                        <?php _e( 'Visit Site →', 'ai-outils' ); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ( ai_outils_is_member() ) : ?>
                                    <button class="btn btn-outline btn-lg" onclick="alert('Save feature integration needed')">
                                        <?php _e( 'Save Tool', 'ai-outils' ); ?>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- Social Links (if available) -->
                            <?php
                            $social_links = ai_outils_get_tool_meta( get_the_ID(), 'social_links' );
                            if ( $social_links && is_array( $social_links ) ) :
                                ?>
                                <div class="tool-social-links" style="margin-top: 1rem; display: flex; gap: 1rem;">
                                    <?php foreach ( $social_links as $platform => $url ) : ?>
                                        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" style="color: var(--color-text-light);">
                                            <?php echo esc_html( ucfirst( $platform ) ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right Column: Video Embed -->
                        <div class="tool-hero-right">
                            <?php
                            $video_url = ai_outils_get_tool_meta( get_the_ID(), 'video_url' );
                            if ( $video_url ) :
                                // Convert YouTube URL to embed format
                                $video_id = '';
                                if ( preg_match( '/youtube\.com\/watch\?v=([^\&\?\/]+)/', $video_url, $id ) ) {
                                    $video_id = $id[1];
                                } elseif ( preg_match( '/youtu\.be\/([^\&\?\/]+)/', $video_url, $id ) ) {
                                    $video_id = $id[1];
                                }

                                if ( $video_id ) :
                                    ?>
                                    <div class="tool-video">
                                        <iframe
                                            src="https://www.youtube.com/embed/<?php echo esc_attr( $video_id ); ?>"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                            loading="lazy"
                                        ></iframe>
                                    </div>
                                <?php
                                endif;
                            elseif ( has_post_thumbnail() ) :
                                ?>
                                <div class="tool-video" style="padding-bottom: 0; height: auto;">
                                    <?php the_post_thumbnail( 'large', array( 'style' => 'border-radius: var(--radius-lg);' ) ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tool Content Sections -->
                <div class="tool-content">
                    <?php if ( get_the_content() ) : ?>
                        <!-- Overview Section -->
                        <div class="tool-content-section">
                            <h2><?php _e( 'Overview', 'ai-outils' ); ?></h2>
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Key Features Section (can be populated via custom fields or ACF) -->
                    <!-- This is a placeholder structure -->
                    <div class="tool-content-section">
                        <h2><?php _e( 'Key Features', 'ai-outils' ); ?></h2>
                        <div class="tool-features">
                            <p style="color: var(--color-text-light); font-style: italic;">
                                <?php _e( 'Key features can be added via custom fields or the AI Tools plugin settings.', 'ai-outils' ); ?>
                            </p>
                            <!-- Example structure:
                            <ul class="tool-features-list">
                                <li>
                                    <h4>Feature Title</h4>
                                    <p>Feature description goes here.</p>
                                </li>
                            </ul>
                            -->
                        </div>
                    </div>
                </div>

                <!-- Similar/Featured Tools -->
                <?php
                $similar_tools = ai_outils_get_similar_tools( get_the_ID(), 3 );

                if ( $similar_tools->have_posts() ) :
                    ?>
                    <div class="tool-content-section">
                        <h2><?php _e( 'Similar AI Tools', 'ai-outils' ); ?></h2>
                        <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                            <?php
                            while ( $similar_tools->have_posts() ) :
                                $similar_tools->the_post();
                                get_template_part( 'template-parts/tool-card' );
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php
endwhile;

get_footer();
