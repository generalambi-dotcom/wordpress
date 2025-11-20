<?php
/**
 * Template part for displaying member stat cards
 *
 * @package AI_Outils
 *
 * Expected variables:
 * $value - The stat value (string or number)
 * $label - The stat label (string)
 * $color - Optional color for the value (default: primary)
 */

if ( ! isset( $value ) || ! isset( $label ) ) {
    return;
}

$color = isset( $color ) ? $color : 'var(--color-primary)';
?>

<div class="stat-card">
    <span class="stat-value" style="color: <?php echo esc_attr( $color ); ?>">
        <?php echo esc_html( $value ); ?>
    </span>
    <span class="stat-label">
        <?php echo esc_html( $label ); ?>
    </span>
</div>
