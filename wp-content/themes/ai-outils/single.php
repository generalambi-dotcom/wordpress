<?php
/**
 * The template for displaying single blog posts
 *
 * @package AI_Outils
 */

get_header();

while ( have_posts() ) :
    the_post();
    ?>

    <main class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="container">
                <div class="section-sm">
                    <!-- Article Header -->
                    <header class="entry-header text-center" style="max-width: 800px; margin: 0 auto 3rem;">
                        <!-- Categories -->
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) :
                            ?>
                            <div class="mb-sm">
                                <?php foreach ( $categories as $category ) : ?>
                                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="chip chip-primary">
                                        <?php echo esc_html( $category->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <h1 class="entry-title"><?php the_title(); ?></h1>

                        <!-- Post Meta -->
                        <div class="entry-meta" style="display: flex; justify-content: center; gap: 1.5rem; color: var(--color-text-light); font-size: 0.9375rem; margin-top: 1rem;">
                            <span>
                                <?php _e( 'By', 'ai-outils' ); ?>
                                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" style="color: var(--color-text);">
                                    <?php the_author(); ?>
                                </a>
                            </span>
                            <span>
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                            </span>
                            <span><?php echo ai_outils_reading_time(); ?></span>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="featured-image" style="max-width: 900px; margin: 0 auto 3rem; border-radius: var(--radius-lg); overflow: hidden;">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Article Content with Sidebar -->
                    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 3rem; max-width: 1200px; margin: 0 auto; align-items: start;">
                        <!-- Main Content -->
                        <div class="entry-content">
                            <?php
                            the_content();

                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . __( 'Pages:', 'ai-outils' ),
                                'after'  => '</div>',
                            ) );
                            ?>

                            <!-- Tags -->
                            <?php
                            $tags = get_the_tags();
                            if ( $tags ) :
                                ?>
                                <div class="post-tags" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--color-border);">
                                    <strong style="margin-right: 0.5rem;"><?php _e( 'Tags:', 'ai-outils' ); ?></strong>
                                    <?php foreach ( $tags as $tag ) : ?>
                                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="chip">
                                            <?php echo esc_html( $tag->name ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sidebar -->
                        <aside class="post-sidebar" style="position: sticky; top: 100px;">
                            <!-- Featured Posts -->
                            <?php
                            $featured_posts = new WP_Query( array(
                                'posts_per_page' => 3,
                                'post__not_in'   => array( get_the_ID() ),
                                'orderby'        => 'rand',
                            ) );

                            if ( $featured_posts->have_posts() ) :
                                ?>
                                <div class="sidebar-section">
                                    <h3 style="font-size: 1rem; margin-bottom: 1rem;"><?php _e( 'Featured Posts', 'ai-outils' ); ?></h3>
                                    <?php
                                    while ( $featured_posts->have_posts() ) :
                                        $featured_posts->the_post();
                                        ?>
                                        <article style="margin-bottom: 1.5rem;">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <a href="<?php the_permalink(); ?>" style="display: block; margin-bottom: 0.5rem;">
                                                    <?php the_post_thumbnail( 'thumbnail', array( 'style' => 'border-radius: var(--radius-md); width: 100%;' ) ); ?>
                                                </a>
                                            <?php endif; ?>
                                            <h4 style="font-size: 0.9375rem; margin-bottom: 0.25rem;">
                                                <a href="<?php the_permalink(); ?>" style="color: var(--color-text);">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h4>
                                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" style="font-size: 0.8125rem; color: var(--color-text-lighter);">
                                                <?php echo get_the_date(); ?>
                                            </time>
                                        </article>
                                    <?php
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            <?php endif; ?>

                            <!-- Latest Posts -->
                            <?php
                            $latest_posts = new WP_Query( array(
                                'posts_per_page' => 3,
                                'post__not_in'   => array( get_the_ID() ),
                            ) );

                            if ( $latest_posts->have_posts() ) :
                                ?>
                                <div class="sidebar-section" style="margin-top: 2rem;">
                                    <h3 style="font-size: 1rem; margin-bottom: 1rem;"><?php _e( 'Latest Posts', 'ai-outils' ); ?></h3>
                                    <ul style="list-style: none;">
                                        <?php
                                        while ( $latest_posts->have_posts() ) :
                                            $latest_posts->the_post();
                                            ?>
                                            <li style="margin-bottom: 1rem;">
                                                <a href="<?php the_permalink(); ?>" style="color: var(--color-text); font-size: 0.9375rem;">
                                                    <?php the_title(); ?>
                                                </a>
                                                <br>
                                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" style="font-size: 0.8125rem; color: var(--color-text-lighter);">
                                                    <?php echo get_the_date(); ?>
                                                </time>
                                            </li>
                                        <?php
                                        endwhile;
                                        wp_reset_postdata();
                                        ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </aside>
                    </div>

                    <!-- Post Navigation -->
                    <?php
                    the_post_navigation( array(
                        'prev_text' => '<span class="nav-subtitle">' . __( 'Previous:', 'ai-outils' ) . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . __( 'Next:', 'ai-outils' ) . '</span> <span class="nav-title">%title</span>',
                    ) );
                    ?>
                </div>
            </div>
        </article>
    </main>

<?php
endwhile;

get_footer();
