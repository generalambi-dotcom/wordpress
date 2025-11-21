<?php
/**
 * AI Tools AJAX Handlers
 *
 * Handles AJAX requests for the AI Tools directory functionality.
 * - filter_ai_tools: Filter tools by category
 * - load_more_ai_tools: Infinite scroll / load more
 * - track_tool_click: Track affiliate link clicks
 * - toggle_tool_save: Save/unsave tools for logged-in users
 *
 * @package AI_Outils
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ============================================================================
 * AJAX ACTION REGISTRATION
 * ============================================================================
 */

// Filter AI Tools - available to all users
add_action( 'wp_ajax_filter_ai_tools', 'ai_outils_filter_tools_handler' );
add_action( 'wp_ajax_nopriv_filter_ai_tools', 'ai_outils_filter_tools_handler' );

// Load More AI Tools - available to all users
add_action( 'wp_ajax_load_more_ai_tools', 'ai_outils_load_more_tools_handler' );
add_action( 'wp_ajax_nopriv_load_more_ai_tools', 'ai_outils_load_more_tools_handler' );

// Track Tool Click - available to all users
add_action( 'wp_ajax_track_tool_click', 'ai_outils_track_tool_click_handler' );
add_action( 'wp_ajax_nopriv_track_tool_click', 'ai_outils_track_tool_click_handler' );

// Toggle Tool Save - logged-in users only
add_action( 'wp_ajax_toggle_tool_save', 'ai_outils_toggle_tool_save_handler' );

// Remove Saved Tool - logged-in users only
add_action( 'wp_ajax_remove_saved_tool_from_page', 'ai_outils_remove_saved_tool_handler' );

// Filter Blog Posts - available to all users
add_action( 'wp_ajax_filter_blog_posts', 'ai_outils_filter_blog_posts_handler' );
add_action( 'wp_ajax_nopriv_filter_blog_posts', 'ai_outils_filter_blog_posts_handler' );

/**
 * ============================================================================
 * FILTER AI TOOLS HANDLER
 * ============================================================================
 *
 * Filters tools by AI category with pagination.
 *
 * Expected POST data:
 * - category: Term ID or 'all'
 * - page: Page number (default 1)
 * - per_page: Items per page (default 10)
 * - search: Search query (optional)
 * - nonce: Security nonce
 *
 * Returns JSON:
 * - success: bool
 * - data.html: HTML content
 * - data.pagination: Pagination HTML
 * - data.total: Total tools count
 * - data.found: Found posts count
 * - data.max_pages: Maximum number of pages
 */
function ai_outils_filter_tools_handler() {
    // Verify nonce
    if ( ! check_ajax_referer( 'ai_outils_nonce', 'nonce', false ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'ai-outils' ) ) );
    }

    // Get and sanitize parameters
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : 'all';
    $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
    $per_page = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 10;
    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

    // Limit per_page to reasonable values
    $per_page = max( 1, min( 50, $per_page ) );

    // Build query args
    $args = array(
        'post_type'      => AI_OUTILS_CPT_SLUG,
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
    );

    // Add category filter
    if ( $category !== 'all' && ! empty( $category ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => AI_OUTILS_TAXONOMY_SLUG,
                'field'    => is_numeric( $category ) ? 'term_id' : 'slug',
                'terms'    => $category,
            ),
        );
    } else {
        // Exclude special categories when showing all
        $excluded_ids = get_excluded_category_ids();
        if ( ! empty( $excluded_ids ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => AI_OUTILS_TAXONOMY_SLUG,
                    'field'    => 'term_id',
                    'terms'    => $excluded_ids,
                    'operator' => 'NOT IN',
                ),
            );
        }
    }

    // Add search
    if ( ! empty( $search ) ) {
        $args['s'] = $search;
    }

    // Execute query
    $query = new WP_Query( $args );

    // Build HTML output
    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/tool-card' );
        }
        wp_reset_postdata();
    } else {
        ?>
        <div class="no-tools-found">
            <p><?php esc_html_e( 'No tools found matching your criteria.', 'ai-outils' ); ?></p>
        </div>
        <?php
    }

    $html = ob_get_clean();

    // Build pagination
    $pagination = '';
    if ( $query->max_num_pages > 1 ) {
        $pagination = ai_outils_ajax_pagination( $page, $query->max_num_pages );
    }

    // Send response
    wp_send_json_success( array(
        'html'       => $html,
        'pagination' => $pagination,
        'total'      => $query->found_posts,
        'found'      => $query->post_count,
        'max_pages'  => $query->max_num_pages,
        'page'       => $page,
    ) );
}

/**
 * ============================================================================
 * LOAD MORE AI TOOLS HANDLER
 * ============================================================================
 *
 * Handles infinite scroll / load more functionality.
 *
 * Expected POST data:
 * - page: Page number
 * - category: Category term ID or 'all'
 * - nonce: Security nonce
 */
function ai_outils_load_more_tools_handler() {
    // Verify nonce
    if ( ! check_ajax_referer( 'ai_outils_nonce', 'nonce', false ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'ai-outils' ) ) );
    }

    // Get parameters
    $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : 'all';
    $per_page = 10;

    // Build query
    $args = array(
        'post_type'      => AI_OUTILS_CPT_SLUG,
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
    );

    // Add category filter
    if ( $category !== 'all' && ! empty( $category ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => AI_OUTILS_TAXONOMY_SLUG,
                'field'    => is_numeric( $category ) ? 'term_id' : 'slug',
                'terms'    => $category,
            ),
        );
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/tool-card' );
        }
        wp_reset_postdata();
    }

    $html = ob_get_clean();

    wp_send_json_success( array(
        'html'      => $html,
        'has_more'  => $page < $query->max_num_pages,
        'max_pages' => $query->max_num_pages,
        'page'      => $page,
    ) );
}

/**
 * ============================================================================
 * TRACK TOOL CLICK HANDLER
 * ============================================================================
 *
 * Increments the click count when user clicks affiliate link.
 *
 * Expected POST data:
 * - tool_id: Post ID of the tool
 * - nonce: Security nonce
 */
function ai_outils_track_tool_click_handler() {
    // Verify nonce
    if ( ! check_ajax_referer( 'ai_outils_nonce', 'nonce', false ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'ai-outils' ) ) );
    }

    // Get tool ID
    $tool_id = isset( $_POST['tool_id'] ) ? absint( $_POST['tool_id'] ) : 0;

    if ( ! $tool_id || get_post_type( $tool_id ) !== AI_OUTILS_CPT_SLUG ) {
        wp_send_json_error( array( 'message' => __( 'Invalid tool ID.', 'ai-outils' ) ) );
    }

    // Get current count
    $current_count = get_ai_tool_click_count( $tool_id );

    // Increment count
    $new_count = $current_count + 1;
    update_post_meta( $tool_id, AI_TOOL_CLICK_COUNT_KEY, $new_count );

    wp_send_json_success( array(
        'count'   => $new_count,
        'tool_id' => $tool_id,
    ) );
}

/**
 * ============================================================================
 * TOGGLE TOOL SAVE HANDLER
 * ============================================================================
 *
 * Saves or unsaves a tool for the logged-in user.
 *
 * Expected POST data:
 * - tool_id: Post ID of the tool
 * - nonce: Security nonce
 */
function ai_outils_toggle_tool_save_handler() {
    // Check if user is logged in
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array(
            'message'    => __( 'You must be logged in to save tools.', 'ai-outils' ),
            'login_url'  => wp_login_url(),
            'login_required' => true,
        ) );
    }

    // Verify nonce
    if ( ! check_ajax_referer( 'ai_outils_nonce', 'nonce', false ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'ai-outils' ) ) );
    }

    // Get tool ID
    $tool_id = isset( $_POST['tool_id'] ) ? absint( $_POST['tool_id'] ) : 0;

    if ( ! $tool_id || get_post_type( $tool_id ) !== AI_OUTILS_CPT_SLUG ) {
        wp_send_json_error( array( 'message' => __( 'Invalid tool ID.', 'ai-outils' ) ) );
    }

    $user_id = get_current_user_id();
    $saved_tools = get_user_saved_tools( $user_id );
    $is_saved = in_array( $tool_id, $saved_tools, true );

    if ( $is_saved ) {
        // Remove from saved
        $saved_tools = array_diff( $saved_tools, array( $tool_id ) );
        $action = 'removed';

        // Decrement save count
        $save_count = max( 0, get_ai_tool_save_count( $tool_id ) - 1 );
    } else {
        // Add to saved
        $saved_tools[] = $tool_id;
        $action = 'saved';

        // Increment save count
        $save_count = get_ai_tool_save_count( $tool_id ) + 1;
    }

    // Update user meta
    update_user_meta( $user_id, USER_SAVED_TOOLS_KEY, array_values( array_unique( $saved_tools ) ) );

    // Update tool save count
    update_post_meta( $tool_id, AI_TOOL_SAVE_COUNT_KEY, $save_count );

    // Sync with membership plugin if available
    do_action( 'ai_outils_tool_save_toggled', $tool_id, $user_id, $action );

    wp_send_json_success( array(
        'action'      => $action,
        'is_saved'    => $action === 'saved',
        'save_count'  => $save_count,
        'tool_id'     => $tool_id,
        'saved_count' => count( $saved_tools ),
    ) );
}

/**
 * ============================================================================
 * REMOVE SAVED TOOL HANDLER
 * ============================================================================
 *
 * Removes a tool from saved list (used on saved tools page).
 *
 * Expected POST data:
 * - tool_id: Post ID of the tool
 * - nonce: Security nonce
 */
function ai_outils_remove_saved_tool_handler() {
    // Check if user is logged in
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'ai-outils' ) ) );
    }

    // Verify nonce
    if ( ! check_ajax_referer( 'ai_outils_nonce', 'nonce', false ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'ai-outils' ) ) );
    }

    // Get tool ID
    $tool_id = isset( $_POST['tool_id'] ) ? absint( $_POST['tool_id'] ) : 0;

    if ( ! $tool_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid tool ID.', 'ai-outils' ) ) );
    }

    $user_id = get_current_user_id();
    $saved_tools = get_user_saved_tools( $user_id );

    // Remove the tool
    $saved_tools = array_diff( $saved_tools, array( $tool_id ) );

    // Update user meta
    update_user_meta( $user_id, USER_SAVED_TOOLS_KEY, array_values( $saved_tools ) );

    // Decrement save count
    $save_count = max( 0, get_ai_tool_save_count( $tool_id ) - 1 );
    update_post_meta( $tool_id, AI_TOOL_SAVE_COUNT_KEY, $save_count );

    // Sync with membership plugin if available
    do_action( 'ai_outils_tool_removed_from_saved', $tool_id, $user_id );

    wp_send_json_success( array(
        'removed'     => true,
        'tool_id'     => $tool_id,
        'saved_count' => count( $saved_tools ),
    ) );
}

/**
 * ============================================================================
 * FILTER BLOG POSTS HANDLER
 * ============================================================================
 *
 * Filters blog posts by category.
 *
 * Expected POST data:
 * - category: Category ID or 'all'
 * - page: Page number
 * - nonce: Security nonce
 */
function ai_outils_filter_blog_posts_handler() {
    // Verify nonce
    if ( ! check_ajax_referer( 'ai_outils_nonce', 'nonce', false ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'ai-outils' ) ) );
    }

    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : 'all';
    $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
    $per_page = 12;

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
    );

    if ( $category !== 'all' && ! empty( $category ) ) {
        $args['cat'] = absint( $category );
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/blog-card' );
        }
        wp_reset_postdata();
    } else {
        ?>
        <div class="no-posts-found">
            <p><?php esc_html_e( 'No posts found.', 'ai-outils' ); ?></p>
        </div>
        <?php
    }

    $html = ob_get_clean();

    wp_send_json_success( array(
        'html'      => $html,
        'total'     => $query->found_posts,
        'max_pages' => $query->max_num_pages,
        'page'      => $page,
    ) );
}

/**
 * ============================================================================
 * HELPER FUNCTIONS FOR AJAX
 * ============================================================================
 */

/**
 * Generate AJAX pagination HTML
 *
 * @param int $current Current page
 * @param int $total Total pages
 * @return string Pagination HTML
 */
function ai_outils_ajax_pagination( $current, $total ) {
    if ( $total <= 1 ) {
        return '';
    }

    $output = '<nav class="ajax-pagination" aria-label="' . esc_attr__( 'Tool pagination', 'ai-outils' ) . '">';
    $output .= '<ul class="pagination-list">';

    // Previous button
    if ( $current > 1 ) {
        $output .= '<li><button class="pagination-btn prev" data-page="' . ( $current - 1 ) . '">' . esc_html__( 'Previous', 'ai-outils' ) . '</button></li>';
    }

    // Page numbers
    $range = 2;
    $start = max( 1, $current - $range );
    $end = min( $total, $current + $range );

    if ( $start > 1 ) {
        $output .= '<li><button class="pagination-btn" data-page="1">1</button></li>';
        if ( $start > 2 ) {
            $output .= '<li><span class="pagination-dots">...</span></li>';
        }
    }

    for ( $i = $start; $i <= $end; $i++ ) {
        $active = $i === $current ? ' active' : '';
        $output .= '<li><button class="pagination-btn' . $active . '" data-page="' . $i . '">' . $i . '</button></li>';
    }

    if ( $end < $total ) {
        if ( $end < $total - 1 ) {
            $output .= '<li><span class="pagination-dots">...</span></li>';
        }
        $output .= '<li><button class="pagination-btn" data-page="' . $total . '">' . $total . '</button></li>';
    }

    // Next button
    if ( $current < $total ) {
        $output .= '<li><button class="pagination-btn next" data-page="' . ( $current + 1 ) . '">' . esc_html__( 'Next', 'ai-outils' ) . '</button></li>';
    }

    $output .= '</ul>';
    $output .= '</nav>';

    return $output;
}

/**
 * Enqueue AJAX scripts and localize data
 *
 * Called from main theme's enqueue function.
 */
function ai_outils_enqueue_ajax_scripts() {
    // Only enqueue on pages that need it
    if ( ! is_page_template( 'page-ai-tools-directory.php' )
         && ! is_tax( AI_OUTILS_TAXONOMY_SLUG )
         && ! is_singular( AI_OUTILS_CPT_SLUG )
         && ! is_post_type_archive( AI_OUTILS_CPT_SLUG )
         && ! is_page_template( 'page-saved-tools.php' ) ) {
        return;
    }

    wp_enqueue_script(
        'ai-outils-directory',
        get_template_directory_uri() . '/assets/js/ai-directory.js',
        array(),
        AI_OUTILS_VERSION,
        true
    );

    wp_localize_script( 'ai-outils-directory', 'aiOutilsAjax', array(
        'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
        'nonce'       => wp_create_nonce( 'ai_outils_nonce' ),
        'isLoggedIn'  => is_user_logged_in(),
        'loginUrl'    => wp_login_url( get_permalink() ),
        'strings'     => array(
            'loading'      => __( 'Loading...', 'ai-outils' ),
            'loadMore'     => __( 'Load More', 'ai-outils' ),
            'noMore'       => __( 'No more tools', 'ai-outils' ),
            'error'        => __( 'An error occurred. Please try again.', 'ai-outils' ),
            'saved'        => __( 'Saved!', 'ai-outils' ),
            'removed'      => __( 'Removed', 'ai-outils' ),
            'saveTool'     => __( 'Save Tool', 'ai-outils' ),
            'unsaveTool'   => __( 'Unsave', 'ai-outils' ),
            'loginToSave'  => __( 'Please log in to save tools', 'ai-outils' ),
        ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'ai_outils_enqueue_ajax_scripts' );
