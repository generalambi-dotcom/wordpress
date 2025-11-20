<?php
/**
 * Template part for displaying hero banner
 *
 * @package Lyststyle_Aggregator
 */

// Expect $hero_data array with: title, subtitle, sponsored_by, background_image, cta_label, cta_url
if ( ! isset( $hero_data ) || ! is_array( $hero_data ) ) {
    return;
}

$hero_title      = isset( $hero_data['title'] ) ? $hero_data['title'] : '';
$hero_subtitle   = isset( $hero_data['subtitle'] ) ? $hero_data['subtitle'] : '';
$sponsored_by    = isset( $hero_data['sponsored_by'] ) ? $hero_data['sponsored_by'] : '';
$bg_image        = isset( $hero_data['background_image'] ) ? $hero_data['background_image'] : '';
$cta_label       = isset( $hero_data['cta_label'] ) ? $hero_data['cta_label'] : 'Explore now';
$cta_url         = isset( $hero_data['cta_url'] ) ? $hero_data['cta_url'] : '#';

$style = '';
if ( $bg_image ) {
    $style = 'background-image: url(' . esc_url( $bg_image ) . ');';
}
?>

<section class="hero-banner" <?php if ( $style ) echo 'style="' . esc_attr( $style ) . '"'; ?>>
    <div class="hero-overlay"></div>
    <div class="hero-container">
        <div class="hero-content">
            <?php if ( $sponsored_by ) : ?>
                <div class="hero-label">
                    <span><?php echo esc_html( sprintf( __( 'SPONSORED BY %s', 'lyststyle-aggregator' ), strtoupper( $sponsored_by ) ) ); ?></span>
                </div>
            <?php endif; ?>

            <?php if ( $hero_title ) : ?>
                <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
            <?php endif; ?>

            <?php if ( $hero_subtitle ) : ?>
                <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
            <?php endif; ?>

            <?php if ( $cta_label && $cta_url ) : ?>
                <a href="<?php echo esc_url( $cta_url ); ?>" class="hero-btn"><?php echo esc_html( $cta_label ); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>
