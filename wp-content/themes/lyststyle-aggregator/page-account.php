<?php
/**
 * Template Name: My Account Page
 * The template for displaying the my account page
 *
 * @package Lyststyle_Aggregator
 */

// Require login
if ( ! is_user_logged_in() ) {
	$login_url = lyststyle_get_page_url( 'lyststyle_login_page' );
	if ( ! $login_url ) {
		$login_url = wp_login_url( get_permalink() );
	}
	wp_safe_redirect( $login_url );
	exit;
}

$current_user = wp_get_current_user();
$error_message = '';
$success_message = '';

// Handle preferences update
if ( isset( $_POST['lyststyle_update_preferences'] ) ) {
	// Verify nonce
	if ( ! isset( $_POST['lyststyle_preferences_nonce'] ) || ! wp_verify_nonce( $_POST['lyststyle_preferences_nonce'], 'lyststyle_update_preferences_action' ) ) {
		$error_message = __( 'Security verification failed. Please try again.', 'lyststyle-aggregator' );
	} else {
		// Save preferences
		$saved = lyststyle_save_user_preferences_from_request( $current_user->ID, $_POST );

		if ( $saved ) {
			$success_message = __( 'Your preferences have been updated successfully.', 'lyststyle-aggregator' );
		} else {
			$error_message = __( 'Failed to update preferences. Please try again.', 'lyststyle-aggregator' );
		}
	}
}

// Get user preferences
$preferences = lyststyle_get_user_preferences( $current_user->ID );

get_header();

// Get taxonomy terms for preferences
$categories = get_terms( array(
	'taxonomy'   => 'product_category',
	'hide_empty' => false,
) );

$brands = get_terms( array(
	'taxonomy'   => 'brand',
	'hide_empty' => false,
) );

$styles = get_terms( array(
	'taxonomy'   => 'product_tag',
	'hide_empty' => false,
) );

$colours = lyststyle_get_available_colours();
$price_bands = lyststyle_get_price_bands();
$occasions = lyststyle_get_occasions();
?>

<main id="primary" class="site-main page-account">
	<div class="container">

		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'My Account', 'lyststyle-aggregator' ); ?></h1>
			<p class="page-description"><?php printf( esc_html__( 'Welcome back, %s!', 'lyststyle-aggregator' ), esc_html( $current_user->display_name ) ); ?></p>
		</header>

		<?php if ( ! empty( $error_message ) ) : ?>
			<div class="alert alert-error" role="alert">
				<?php echo esc_html( $error_message ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $success_message ) ) : ?>
			<div class="alert alert-success" role="alert">
				<?php echo esc_html( $success_message ); ?>
			</div>
		<?php endif; ?>

		<div class="account-content">

			<!-- Profile Information -->
			<div class="account-section">
				<h2><?php esc_html_e( 'Profile Information', 'lyststyle-aggregator' ); ?></h2>
				<div class="profile-info">
					<p><strong><?php esc_html_e( 'Name:', 'lyststyle-aggregator' ); ?></strong> <?php echo esc_html( $current_user->display_name ); ?></p>
					<p><strong><?php esc_html_e( 'Email:', 'lyststyle-aggregator' ); ?></strong> <?php echo esc_html( $current_user->user_email ); ?></p>
				</div>
			</div>

			<!-- Quick Links -->
			<div class="account-section account-quick-links">
				<div class="quick-links">
					<?php
					$wishlist_url = lyststyle_get_page_url( 'lyststyle_wishlist_page' );
					if ( ! $wishlist_url ) {
						$wishlist_url = home_url( '/wishlist' );
					}
					?>
					<a href="<?php echo esc_url( $wishlist_url ); ?>" class="btn-secondary">
						<?php echo lyststyle_get_icon( 'heart' ); ?>
						<?php esc_html_e( 'View My Wishlist', 'lyststyle-aggregator' ); ?>
					</a>
					<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="btn-secondary">
						<?php esc_html_e( 'Log Out', 'lyststyle-aggregator' ); ?>
					</a>
				</div>
			</div>

			<!-- Preferences Form -->
			<div class="account-section">
				<h2><?php esc_html_e( 'Your Preferences', 'lyststyle-aggregator' ); ?></h2>
				<p class="section-description"><?php esc_html_e( 'Update your fashion preferences to get personalized recommendations.', 'lyststyle-aggregator' ); ?></p>

				<form method="post" action="" class="preferences-form">
					<?php wp_nonce_field( 'lyststyle_update_preferences_action', 'lyststyle_preferences_nonce' ); ?>

					<div class="form-group">
						<label for="gender"><?php esc_html_e( 'Gender', 'lyststyle-aggregator' ); ?></label>
						<select name="gender" id="gender" class="form-control">
							<option value=""><?php esc_html_e( '-- Select --', 'lyststyle-aggregator' ); ?></option>
							<option value="female" <?php selected( $preferences['gender'], 'female' ); ?>><?php esc_html_e( 'Female', 'lyststyle-aggregator' ); ?></option>
							<option value="male" <?php selected( $preferences['gender'], 'male' ); ?>><?php esc_html_e( 'Male', 'lyststyle-aggregator' ); ?></option>
							<option value="unisex" <?php selected( $preferences['gender'], 'unisex' ); ?>><?php esc_html_e( 'Unisex', 'lyststyle-aggregator' ); ?></option>
						</select>
					</div>

					<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
					<div class="form-group form-group-checkbox">
						<label><?php esc_html_e( 'Preferred Categories', 'lyststyle-aggregator' ); ?></label>
						<div class="checkbox-grid">
							<?php foreach ( $categories as $category ) : ?>
								<label class="checkbox-label">
									<input
										type="checkbox"
										name="categories[]"
										value="<?php echo esc_attr( $category->slug ); ?>"
										<?php checked( in_array( $category->slug, (array) $preferences['categories'], true ) ); ?>
									>
									<?php echo esc_html( $category->name ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) : ?>
					<div class="form-group form-group-checkbox">
						<label><?php esc_html_e( 'Preferred Brands', 'lyststyle-aggregator' ); ?></label>
						<div class="checkbox-grid">
							<?php foreach ( $brands as $brand ) : ?>
								<label class="checkbox-label">
									<input
										type="checkbox"
										name="brands[]"
										value="<?php echo esc_attr( $brand->slug ); ?>"
										<?php checked( in_array( $brand->slug, (array) $preferences['brands'], true ) ); ?>
									>
									<?php echo esc_html( $brand->name ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<div class="form-group">
						<label for="price_band"><?php esc_html_e( 'Price Band', 'lyststyle-aggregator' ); ?></label>
						<select name="price_band" id="price_band" class="form-control">
							<option value=""><?php esc_html_e( '-- Select --', 'lyststyle-aggregator' ); ?></option>
							<?php foreach ( $price_bands as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $preferences['price_band'], $value ); ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-row">
						<div class="form-group">
							<label for="price_min"><?php esc_html_e( 'Min Price (£)', 'lyststyle-aggregator' ); ?></label>
							<input
								type="number"
								name="price_min"
								id="price_min"
								class="form-control"
								min="0"
								step="1"
								value="<?php echo esc_attr( $preferences['price_min'] ); ?>"
							>
						</div>

						<div class="form-group">
							<label for="price_max"><?php esc_html_e( 'Max Price (£)', 'lyststyle-aggregator' ); ?></label>
							<input
								type="number"
								name="price_max"
								id="price_max"
								class="form-control"
								min="0"
								step="1"
								value="<?php echo esc_attr( $preferences['price_max'] ); ?>"
							>
						</div>
					</div>

					<?php if ( ! empty( $colours ) ) : ?>
					<div class="form-group form-group-checkbox">
						<label><?php esc_html_e( 'Colour Preferences', 'lyststyle-aggregator' ); ?></label>
						<div class="checkbox-grid">
							<?php foreach ( $colours as $colour ) : ?>
								<label class="checkbox-label">
									<input
										type="checkbox"
										name="colours[]"
										value="<?php echo esc_attr( $colour ); ?>"
										<?php checked( in_array( $colour, (array) $preferences['colours'], true ) ); ?>
									>
									<?php echo esc_html( $colour ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $styles ) && ! is_wp_error( $styles ) ) : ?>
					<div class="form-group form-group-checkbox">
						<label><?php esc_html_e( 'Styles', 'lyststyle-aggregator' ); ?></label>
						<div class="checkbox-grid">
							<?php foreach ( $styles as $style ) : ?>
								<label class="checkbox-label">
									<input
										type="checkbox"
										name="styles[]"
										value="<?php echo esc_attr( $style->slug ); ?>"
										<?php checked( in_array( $style->slug, (array) $preferences['styles'], true ) ); ?>
									>
									<?php echo esc_html( $style->name ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $occasions ) ) : ?>
					<div class="form-group form-group-checkbox">
						<label><?php esc_html_e( 'Occasions', 'lyststyle-aggregator' ); ?></label>
						<div class="checkbox-grid">
							<?php foreach ( $occasions as $value => $label ) : ?>
								<label class="checkbox-label">
									<input
										type="checkbox"
										name="occasions[]"
										value="<?php echo esc_attr( $value ); ?>"
										<?php checked( in_array( $value, (array) $preferences['occasions'], true ) ); ?>
									>
									<?php echo esc_html( $label ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<div class="form-row">
						<div class="form-group">
							<label for="shoe_size"><?php esc_html_e( 'Shoe Size', 'lyststyle-aggregator' ); ?></label>
							<input
								type="text"
								name="shoe_size"
								id="shoe_size"
								class="form-control"
								value="<?php echo esc_attr( $preferences['shoe_size'] ); ?>"
								placeholder="<?php esc_attr_e( 'e.g., UK 7', 'lyststyle-aggregator' ); ?>"
							>
						</div>

						<div class="form-group">
							<label for="clothing_size_top"><?php esc_html_e( 'Top Size', 'lyststyle-aggregator' ); ?></label>
							<input
								type="text"
								name="clothing_size_top"
								id="clothing_size_top"
								class="form-control"
								value="<?php echo esc_attr( $preferences['clothing_size_top'] ); ?>"
								placeholder="<?php esc_attr_e( 'e.g., M', 'lyststyle-aggregator' ); ?>"
							>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group">
							<label for="clothing_size_bottom"><?php esc_html_e( 'Bottom Size', 'lyststyle-aggregator' ); ?></label>
							<input
								type="text"
								name="clothing_size_bottom"
								id="clothing_size_bottom"
								class="form-control"
								value="<?php echo esc_attr( $preferences['clothing_size_bottom'] ); ?>"
								placeholder="<?php esc_attr_e( 'e.g., 32', 'lyststyle-aggregator' ); ?>"
							>
						</div>

						<div class="form-group">
							<label for="clothing_size_dress"><?php esc_html_e( 'Dress Size', 'lyststyle-aggregator' ); ?></label>
							<input
								type="text"
								name="clothing_size_dress"
								id="clothing_size_dress"
								class="form-control"
								value="<?php echo esc_attr( $preferences['clothing_size_dress'] ); ?>"
								placeholder="<?php esc_attr_e( 'e.g., 12', 'lyststyle-aggregator' ); ?>"
							>
						</div>
					</div>

					<div class="form-actions">
						<button type="submit" name="lyststyle_update_preferences" class="btn-primary btn-large">
							<?php esc_html_e( 'Update Preferences', 'lyststyle-aggregator' ); ?>
						</button>
					</div>
				</form>
			</div>

		</div>

	</div>
</main>

<style>
.page-account .page-header {
	margin-bottom: 40px;
}

.page-account .page-title {
	font-size: 2.5rem;
	margin-bottom: 10px;
}

.page-account .page-description {
	color: #666;
	font-size: 1.1rem;
}

.account-content {
	max-width: 900px;
	margin: 0 auto;
}

.account-section {
	background: #fff;
	padding: 30px;
	border-radius: 8px;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	margin-bottom: 30px;
}

.account-section h2 {
	font-size: 1.8rem;
	margin-bottom: 20px;
	padding-bottom: 15px;
	border-bottom: 2px solid #f0f0f0;
}

.account-section .section-description {
	color: #666;
	margin-bottom: 25px;
}

.profile-info p {
	margin: 10px 0;
	font-size: 1.1rem;
}

.profile-info strong {
	display: inline-block;
	min-width: 80px;
}

.account-quick-links .quick-links {
	display: flex;
	gap: 15px;
	flex-wrap: wrap;
}

.account-quick-links .btn-secondary {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 12px 24px;
	background: #f8f8f8;
	border: 1px solid #ddd;
	border-radius: 4px;
	color: #333;
	text-decoration: none;
	transition: all 0.3s;
}

.account-quick-links .btn-secondary:hover {
	background: #333;
	color: #fff;
	border-color: #333;
}

.preferences-form .form-group {
	margin-bottom: 25px;
}

.preferences-form .form-group label {
	display: block;
	font-weight: 600;
	margin-bottom: 8px;
	color: #333;
}

.preferences-form .form-control {
	width: 100%;
	padding: 12px 16px;
	border: 1px solid #ddd;
	border-radius: 4px;
	font-size: 1rem;
	transition: border-color 0.3s;
}

.preferences-form .form-control:focus {
	outline: none;
	border-color: var(--primary-color, #000);
}

.preferences-form .form-row {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20px;
}

.preferences-form .form-group-checkbox {
	margin-bottom: 30px;
}

.preferences-form .checkbox-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
	gap: 10px;
	margin-top: 10px;
}

.preferences-form .checkbox-label {
	display: flex;
	align-items: center;
	gap: 8px;
	font-weight: normal;
	margin: 0;
	cursor: pointer;
}

.preferences-form .checkbox-label input[type="checkbox"] {
	margin: 0;
	cursor: pointer;
}

.preferences-form .form-actions {
	margin-top: 30px;
	padding-top: 30px;
	border-top: 1px solid #eee;
}

.preferences-form .btn-large {
	padding: 14px 40px;
	font-size: 1rem;
	font-weight: 600;
}

.alert {
	padding: 15px 20px;
	border-radius: 4px;
	margin-bottom: 30px;
}

.alert-error {
	background-color: #fee;
	color: #c33;
	border: 1px solid #fcc;
}

.alert-success {
	background-color: #efe;
	color: #3c3;
	border: 1px solid #cfc;
}

@media (max-width: 768px) {
	.preferences-form .form-row {
		grid-template-columns: 1fr;
	}

	.preferences-form .checkbox-grid {
		grid-template-columns: 1fr;
	}

	.account-quick-links .quick-links {
		flex-direction: column;
	}

	.account-quick-links .btn-secondary {
		justify-content: center;
	}
}
</style>

<?php
get_footer();
