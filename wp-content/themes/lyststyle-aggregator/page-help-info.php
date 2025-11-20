<?php
/**
 * Template Name: Help & Info Page
 *
 * A clean, readable template for help and information pages like
 * About, Shipping Policy, Terms & Conditions, Privacy Policy, etc.
 *
 * @package Lyststyle_Aggregator
 */

get_header();
?>

<main id="primary" class="site-main page-help-info">
    <div class="container">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="page-<?php the_ID(); ?>" <?php post_class( 'help-info-content' ); ?>>

                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lyststyle-aggregator' ),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div>

                <?php if ( get_edit_post_link() ) : ?>
                    <footer class="entry-footer">
                        <?php
                        edit_post_link(
                            sprintf(
                                /* translators: %s: Page title */
                                esc_html__( 'Edit %s', 'lyststyle-aggregator' ),
                                '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span>'
                            ),
                            '<span class="edit-link">',
                            '</span>'
                        );
                        ?>
                    </footer>
                <?php endif; ?>

            </article>

            <?php
            // Display last updated date
            $modified_date = get_the_modified_date();
            $published_date = get_the_date();

            if ( $modified_date !== $published_date ) :
                ?>
                <div class="page-last-updated">
                    <p>
                        <?php
                        printf(
                            /* translators: %s: Last updated date */
                            esc_html__( 'Last updated: %s', 'lyststyle-aggregator' ),
                            '<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( $modified_date ) . '</time>'
                        );
                        ?>
                    </p>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>

        <!-- Help & Info Navigation (optional) -->
        <?php
        // Display a list of other help pages for easy navigation
        $help_pages = get_pages(
            array(
                'meta_key'   => '_wp_page_template',
                'meta_value' => 'page-help-info.php',
                'exclude'    => get_the_ID(),
                'sort_column' => 'post_title',
            )
        );

        if ( $help_pages ) :
            ?>
            <aside class="related-help-pages">
                <h2><?php esc_html_e( 'Related Information', 'lyststyle-aggregator' ); ?></h2>
                <ul class="help-pages-list">
                    <?php foreach ( $help_pages as $page ) : ?>
                        <li>
                            <a href="<?php echo esc_url( get_permalink( $page->ID ) ); ?>">
                                <?php echo esc_html( $page->post_title ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
