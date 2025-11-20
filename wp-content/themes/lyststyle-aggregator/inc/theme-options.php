<?php
/**
 * Theme Options via Customizer
 *
 * @package Lyststyle_Aggregator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Customizer Settings
 */
function lyststyle_customize_register( $wp_customize ) {

    // Add Lyststyle Settings Section
    $wp_customize->add_section( 'lyststyle_settings', array(
        'title'    => __( 'Lyststyle Settings', 'lyststyle-aggregator' ),
        'priority' => 30,
    ) );

    // Logo Upload
    $wp_customize->add_setting( 'lyststyle_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'lyststyle_logo', array(
        'label'    => __( 'Logo', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_settings',
        'settings' => 'lyststyle_logo',
    ) ) );

    // Primary Brand Color
    $wp_customize->add_setting( 'lyststyle_primary_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'lyststyle_primary_color', array(
        'label'    => __( 'Primary Brand Color', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_settings',
        'settings' => 'lyststyle_primary_color',
    ) ) );

    // Accent Color
    $wp_customize->add_setting( 'lyststyle_accent_color', array(
        'default'           => '#FF6B6B',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'lyststyle_accent_color', array(
        'label'    => __( 'Accent Color', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_settings',
        'settings' => 'lyststyle_accent_color',
    ) ) );

    // Default Currency
    $wp_customize->add_setting( 'lyststyle_default_currency', array(
        'default'           => 'GBP',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'lyststyle_default_currency', array(
        'label'    => __( 'Default Currency', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_settings',
        'type'     => 'select',
        'choices'  => array(
            'GBP' => 'GBP (£)',
            'USD' => 'USD ($)',
            'EUR' => 'EUR (€)',
        ),
    ) );

    // Footer Text
    $wp_customize->add_setting( 'lyststyle_footer_text', array(
        'default'           => '© ' . date( 'Y' ) . ' Lyststyle',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'lyststyle_footer_text', array(
        'label'    => __( 'Footer Copyright Text', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_settings',
        'type'     => 'text',
    ) );

    // TrustScore Text
    $wp_customize->add_setting( 'lyststyle_trustscore_text', array(
        'default'           => 'TrustScore 4.1',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'lyststyle_trustscore_text', array(
        'label'    => __( 'TrustScore Text', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_settings',
        'type'     => 'text',
    ) );

    // Homepage Settings Section
    $wp_customize->add_section( 'lyststyle_homepage', array(
        'title'    => __( 'Homepage Settings', 'lyststyle-aggregator' ),
        'priority' => 31,
    ) );

    // Hero Banner Title
    $wp_customize->add_setting( 'lyststyle_hero_title', array(
        'default'           => 'The Intelligent Gift Guide',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'lyststyle_hero_title', array(
        'label'    => __( 'Hero Banner Title', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_homepage',
        'type'     => 'text',
    ) );

    // Hero Banner Subtitle
    $wp_customize->add_setting( 'lyststyle_hero_subtitle', array(
        'default'           => 'Discover the ultimate wishlist for discerning fashion fans.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'lyststyle_hero_subtitle', array(
        'label'    => __( 'Hero Banner Subtitle', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_homepage',
        'type'     => 'textarea',
    ) );

    // Hero Banner Image
    $wp_customize->add_setting( 'lyststyle_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'lyststyle_hero_image', array(
        'label'    => __( 'Hero Banner Image', 'lyststyle-aggregator' ),
        'section'  => 'lyststyle_homepage',
        'settings' => 'lyststyle_hero_image',
    ) ) );

    // Editor's Picks Tag
    $wp_customize->add_setting( 'lyststyle_editors_picks_tag', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'lyststyle_editors_picks_tag', array(
        'label'       => __( 'Editor\'s Picks Product Tag', 'lyststyle-aggregator' ),
        'description' => __( 'Enter the slug of the product tag to show in Editor\'s Picks section (e.g., "editors-pick")', 'lyststyle-aggregator' ),
        'section'     => 'lyststyle_homepage',
        'type'        => 'text',
    ) );

    // Social Media Links
    $wp_customize->add_section( 'lyststyle_social', array(
        'title'    => __( 'Social Media Links', 'lyststyle-aggregator' ),
        'priority' => 32,
    ) );

    $social_platforms = array(
        'instagram' => 'Instagram',
        'tiktok'    => 'TikTok',
        'facebook'  => 'Facebook',
        'x'         => 'X (Twitter)',
    );

    foreach ( $social_platforms as $platform => $label ) {
        $wp_customize->add_setting( 'lyststyle_social_' . $platform, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );

        $wp_customize->add_control( 'lyststyle_social_' . $platform, array(
            'label'    => $label . ' ' . __( 'URL', 'lyststyle-aggregator' ),
            'section'  => 'lyststyle_social',
            'type'     => 'url',
        ) );
    }
}
add_action( 'customize_register', 'lyststyle_customize_register' );

/**
 * Output custom CSS for customizer options
 */
function lyststyle_customizer_css() {
    $primary_color = get_theme_mod( 'lyststyle_primary_color', '#000000' );
    $accent_color  = get_theme_mod( 'lyststyle_accent_color', '#FF6B6B' );
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr( $primary_color ); ?>;
            --accent-color: <?php echo esc_attr( $accent_color ); ?>;
        }
        .btn-primary,
        .button-primary {
            background-color: var(--primary-color);
        }
        .btn-primary:hover,
        .button-primary:hover {
            background-color: var(--accent-color);
        }
        a:hover,
        .product-card .brand {
            color: var(--accent-color);
        }
    </style>
    <?php
}
add_action( 'wp_head', 'lyststyle_customizer_css' );
