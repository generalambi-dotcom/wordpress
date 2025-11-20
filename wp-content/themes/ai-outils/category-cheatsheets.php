<?php
/**
 * Template for Cheatsheets category
 *
 * @package AI_Outils
 */

get_header();
?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container text-center">
            <h1><?php _e( 'Cheatsheets', 'ai-outils' ); ?></h1>
            <p class="subtitle">
                <?php _e( 'Quick reference guides and cheatsheets for AI tools and technologies', 'ai-outils' ); ?>
            </p>
        </div>
    </section>

    <div class="container">
        <div class="section">
            <!-- Filter Pills -->
            <div class="filter-pills">
                <a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" class="filter-pill">
                    <?php _e( 'All Tools', 'ai-outils' ); ?>
                </a>
                <a href="<?php echo esc_url( get_term_link( get_term_by( 'slug', 'ai-agents', AI_OUTILS_TAXONOMY_SLUG ) ) ); ?>" class="filter-pill">
                    <?php _e( 'AI Agents', 'ai-outils' ); ?>
                </a>
                <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="filter-pill">
                    <?php _e( 'All', 'ai-outils' ); ?>
                </a>
                <a href="<?php echo esc_url( get_category_link( get_category_by_slug( 'cheatsheets' ) ) ); ?>" class="filter-pill active">
                    <?php _e( 'Cheatsheets', 'ai-outils' ); ?>
                </a>
                <a href="<?php echo esc_url( get_category_link( get_category_by_slug( 'latest' ) ) ); ?>" class="filter-pill">
                    <?php _e( 'Latest', 'ai-outils' ); ?>
                </a>
                <a href="<?php echo esc_url( get_category_link( get_category_by_slug( 'tutorials' ) ) ); ?>" class="filter-pill">
                    <?php _e( 'Tutorials', 'ai-outils' ); ?>
                </a>
            </div>

            <?php if ( have_posts() ) : ?>
                <!-- Blog Grid -->
                <div class="card-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/blog-card' );
                    endwhile;
                    ?>
                </div>

                <!-- Pagination -->
                <?php ai_outils_pagination(); ?>

            <?php else : ?>
                <div class="no-results text-center" style="padding: 4rem 0;">
                    <h2><?php _e( 'No Cheatsheets Found', 'ai-outils' ); ?></h2>
                    <p style="color: var(--color-text-light);">
                        <?php _e( 'Check back soon for new cheatsheets.', 'ai-outils' ); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
