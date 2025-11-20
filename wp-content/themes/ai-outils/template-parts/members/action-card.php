<?php
/**
 * Template part for displaying member action cards
 *
 * @package AI_Outils
 *
 * Expected variables:
 * $title - The card title (string)
 * $description - The card description (string)
 * $button_text - The button text (string)
 * $button_url - The button URL (string)
 * $button_style - Optional button style class (default: btn-primary)
 */

if ( ! isset( $title ) || ! isset( $description ) || ! isset( $button_text ) || ! isset( $button_url ) ) {
    return;
}

$button_style = isset( $button_style ) ? $button_style : 'btn-primary';
?>

<div class="card action-card">
    <h3><?php echo esc_html( $title ); ?></h3>
    <p><?php echo esc_html( $description ); ?></p>
    <a href="<?php echo esc_url( $button_url ); ?>" class="btn <?php echo esc_attr( $button_style ); ?>">
        <?php echo esc_html( $button_text ); ?>
    </a>
</div>
