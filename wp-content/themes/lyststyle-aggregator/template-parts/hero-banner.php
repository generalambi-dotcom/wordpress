<?php
/**
 * Template part for displaying hero banner
 *
 * @package Lyststyle_Aggregator
 */

$hero_title    = get_theme_mod( 'lyststyle_hero_title', 'The Intelligent Gift Guide' );
$hero_subtitle = get_theme_mod( 'lyststyle_hero_subtitle', 'Discover the ultimate wishlist for discerning fashion fans.' );
$hero_image    = get_theme_mod( 'lyststyle_hero_image' );
?>

<section class="hero-banner">
    <div class="hero-container">
        <?php if ( $hero_image ) : ?>
            <div class="hero-image">
                <img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>" loading="lazy">
            </div>
        <?php endif; ?>

        <div class="hero-content">
            <div class="hero-label">
                <span><?php esc_html_e( 'SPONSORED', 'lyststyle-aggregator' ); ?></span>
            </div>

            <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>

            <?php if ( $hero_subtitle ) : ?>
                <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
            <?php endif; ?>

            <a href="#" class="hero-btn"><?php esc_html_e( 'Explore now', 'lyststyle-aggregator' ); ?></a>
        </div>
    </div>
</section>
