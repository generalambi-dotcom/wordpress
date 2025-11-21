<?php
/**
 * Template part for displaying the directory/archive filters bar
 *
 * Supports:
 * - Category filter pills
 * - Search input
 * - Sort dropdown
 *
 * Expected variables (via $args or global):
 * - show_search: bool (default true)
 * - show_categories: bool (default true)
 * - show_sort: bool (default true)
 * - current_category: string|int (default 'all')
 * - categories_to_show: int (default 8)
 * - ajax_enabled: bool (default true)
 *
 * @package AI_Outils
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get passed arguments or defaults
$defaults = array(
    'show_search'       => true,
    'show_categories'   => true,
    'show_sort'         => true,
    'current_category'  => 'all',
    'categories_to_show' => 8,
    'ajax_enabled'      => true,
    'form_action'       => '',
);

$args = wp_parse_args( $args ?? array(), $defaults );

// Get categories
$categories = array();
if ( $args['show_categories'] ) {
    $categories = get_terms( array(
        'taxonomy'   => AI_OUTILS_TAXONOMY_SLUG,
        'hide_empty' => true,
        'number'     => $args['categories_to_show'],
        'exclude'    => get_excluded_category_ids(),
    ) );
}

$filter_class = $args['ajax_enabled'] ? 'directory-filters ajax-filters' : 'directory-filters';
?>

<div class="filters <?php echo esc_attr( $filter_class ); ?>">
    <form
        method="get"
        action="<?php echo esc_url( $args['form_action'] ?: get_post_type_archive_link( AI_OUTILS_CPT_SLUG ) ); ?>"
        class="filter-form"
    >
        <div class="filter-row">
            <?php if ( $args['show_search'] ) : ?>
                <!-- Search Input -->
                <div class="filter-search">
                    <label for="filter-search-input" class="sr-only">
                        <?php esc_html_e( 'Search tools', 'ai-outils' ); ?>
                    </label>
                    <input
                        type="search"
                        id="filter-search-input"
                        name="s"
                        class="form-input filter-search-input"
                        placeholder="<?php esc_attr_e( 'Search AI tools...', 'ai-outils' ); ?>"
                        value="<?php echo esc_attr( get_search_query() ); ?>"
                    >
                </div>
            <?php endif; ?>

            <?php if ( $args['show_sort'] ) : ?>
                <!-- Sort Dropdown -->
                <div class="filter-sort">
                    <label for="filter-sort-select" class="sr-only">
                        <?php esc_html_e( 'Sort by', 'ai-outils' ); ?>
                    </label>
                    <select id="filter-sort-select" name="orderby" class="filter-select">
                        <option value="date" <?php selected( isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : '', 'date' ); ?>>
                            <?php esc_html_e( 'Latest', 'ai-outils' ); ?>
                        </option>
                        <option value="title" <?php selected( isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : '', 'title' ); ?>>
                            <?php esc_html_e( 'Name (A-Z)', 'ai-outils' ); ?>
                        </option>
                        <option value="rating" <?php selected( isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : '', 'rating' ); ?>>
                            <?php esc_html_e( 'Top Rated', 'ai-outils' ); ?>
                        </option>
                        <option value="popular" <?php selected( isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : '', 'popular' ); ?>>
                            <?php esc_html_e( 'Most Popular', 'ai-outils' ); ?>
                        </option>
                    </select>
                </div>
            <?php endif; ?>

            <?php if ( ! $args['ajax_enabled'] ) : ?>
                <button type="submit" class="btn btn-primary">
                    <?php esc_html_e( 'Apply', 'ai-outils' ); ?>
                </button>
            <?php endif; ?>
        </div>

        <?php if ( $args['show_categories'] && ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
            <!-- Category Filter Pills -->
            <div class="filter-pills" role="tablist" aria-label="<?php esc_attr_e( 'Filter by category', 'ai-outils' ); ?>">
                <button
                    type="button"
                    class="filter-pill <?php echo $args['current_category'] === 'all' ? 'active' : ''; ?>"
                    data-category="all"
                    role="tab"
                    aria-selected="<?php echo $args['current_category'] === 'all' ? 'true' : 'false'; ?>"
                >
                    <?php esc_html_e( 'All Tools', 'ai-outils' ); ?>
                </button>

                <?php foreach ( $categories as $category ) :
                    $is_active = ( $args['current_category'] == $category->term_id || $args['current_category'] == $category->slug );
                    ?>
                    <button
                        type="button"
                        class="filter-pill <?php echo $is_active ? 'active' : ''; ?>"
                        data-category="<?php echo esc_attr( $category->term_id ); ?>"
                        role="tab"
                        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                    >
                        <?php echo esc_html( $category->name ); ?>
                        <span class="pill-count">(<?php echo esc_html( $category->count ); ?>)</span>
                    </button>
                <?php endforeach; ?>

                <?php
                // Show "View All Categories" link if there are more
                $total_categories = wp_count_terms( array( 'taxonomy' => AI_OUTILS_TAXONOMY_SLUG, 'hide_empty' => true ) );
                if ( $total_categories > $args['categories_to_show'] ) :
                    ?>
                    <a
                        href="<?php echo esc_url( home_url( '/ai-tools-directory/' ) ); ?>"
                        class="filter-pill filter-pill-link"
                    >
                        <?php esc_html_e( 'View All Categories', 'ai-outils' ); ?>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( $args['ajax_enabled'] ) : ?>
            <!-- Hidden field for AJAX category -->
            <input type="hidden" name="filter_category" class="filter-category-select" value="<?php echo esc_attr( $args['current_category'] ); ?>">
        <?php endif; ?>
    </form>
</div>

<?php if ( $args['ajax_enabled'] ) : ?>
<!-- Loading indicator -->
<div class="filter-loading" aria-hidden="true" style="display: none;">
    <div class="loading-spinner"></div>
    <span><?php esc_html_e( 'Loading tools...', 'ai-outils' ); ?></span>
</div>
<?php endif; ?>
