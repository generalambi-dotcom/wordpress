<?php
/**
 * Template Name: Register Page
 * The template for displaying the registration page
 *
 * @package Lyststyle_Aggregator
 */

// Redirect if already logged in
if ( is_user_logged_in() ) {
	$redirect_url = lyststyle_get_page_url( 'lyststyle_account_page' );
	if ( ! $redirect_url ) {
		$redirect_url = home_url( '/my-account' );
	}
	wp_safe_redirect( $redirect_url );
	exit;
}

$error_message = '';
$success_message = '';

// Handle registration form submission
if ( isset( $_POST['lyststyle_register_submit'] ) ) {
	// Verify nonce
	if ( ! isset( $_POST['lyststyle_register_nonce'] ) || ! wp_verify_nonce( $_POST['lyststyle_register_nonce'], 'lyststyle_register_action' ) ) {
		$error_message = __( 'Security verification failed. Please try again.', 'lyststyle-aggregator' );
	} else {
		$first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
		$last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
		$email      = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$password   = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$password_confirm = isset( $_POST['password_confirm'] ) ? $_POST['password_confirm'] : '';

		// Validation
		if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $password ) ) {
			$error_message = __( 'Please fill in all required fields.', 'lyststyle-aggregator' );
		} elseif ( ! is_email( $email ) ) {
			$error_message = __( 'Please enter a valid email address.', 'lyststyle-aggregator' );
		} elseif ( email_exists( $email ) ) {
			$error_message = __( 'An account with this email already exists.', 'lyststyle-aggregator' );
		} elseif ( strlen( $password ) < 8 ) {
			$error_message = __( 'Password must be at least 8 characters long.', 'lyststyle-aggregator' );
		} elseif ( $password !== $password_confirm ) {
			$error_message = __( 'Passwords do not match.', 'lyststyle-aggregator' );
		} else {
			// Create username from email
			$username = sanitize_user( current( explode( '@', $email ) ), true );

			// Ensure username is unique
			$username_base = $username;
			$suffix = 1;
			while ( username_exists( $username ) ) {
				$username = $username_base . $suffix;
				$suffix++;
			}

			// Create user
			$user_id = wp_create_user( $username, $password, $email );

			if ( is_wp_error( $user_id ) ) {
				$error_message = $user_id->get_error_message();
			} else {
				// Update user meta
				wp_update_user( array(
					'ID'         => $user_id,
					'first_name' => $first_name,
					'last_name'  => $last_name,
					'display_name' => $first_name . ' ' . $last_name,
				) );

				// Save preferences
				lyststyle_save_user_preferences_from_request( $user_id, $_POST );

				// Log user in
				wp_set_current_user( $user_id );
				wp_set_auth_cookie( $user_id );

				// Redirect to My Account page
				$redirect_url = lyststyle_get_page_url( 'lyststyle_account_page' );
				if ( ! $redirect_url ) {
					$redirect_url = home_url( '/my-account' );
				}
				wp_safe_redirect( $redirect_url );
				exit;
			}
		}
	}
}

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

<main id="primary" class="site-main page-register">
	<div class="container">
		<div class="auth-wrapper">

			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Create Account', 'lyststyle-aggregator' ); ?></h1>
				<p class="page-description"><?php esc_html_e( 'Join us and discover your perfect fashion matches.', 'lyststyle-aggregator' ); ?></p>
			</header>

			<?php if ( ! empty( $error_message ) ) : ?>
				<div class="alert alert-error" role="alert">
					<?php echo esc_html( $error_message ); ?>
				</div>
			<?php endif; ?>

			<div class="auth-form-container">
				<form method="post" action="" class="auth-form register-form">
					<?php wp_nonce_field( 'lyststyle_register_action', 'lyststyle_register_nonce' ); ?>

					<h2><?php esc_html_e( 'Account Information', 'lyststyle-aggregator' ); ?></h2>

					<div class="form-row">
						<div class="form-group">
							<label for="first_name"><?php esc_html_e( 'First Name', 'lyststyle-aggregator' ); ?> <span class="required">*</span></label>
							<input
								type="text"
								name="first_name"
								id="first_name"
								class="form-control"
								value="<?php echo isset( $_POST['first_name'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) ) : ''; ?>"
								required
							>
						</div>

						<div class="form-group">
							<label for="last_name"><?php esc_html_e( 'Last Name', 'lyststyle-aggregator' ); ?> <span class="required">*</span></label>
							<input
								type="text"
								name="last_name"
								id="last_name"
								class="form-control"
								value="<?php echo isset( $_POST['last_name'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) ) : ''; ?>"
								required
							>
						</div>
					</div>

					<div class="form-group">
						<label for="email"><?php esc_html_e( 'Email', 'lyststyle-aggregator' ); ?> <span class="required">*</span></label>
						<input
							type="email"
							name="email"
							id="email"
							class="form-control"
							value="<?php echo isset( $_POST['email'] ) ? esc_attr( sanitize_email( wp_unslash( $_POST['email'] ) ) ) : ''; ?>"
							required
						>
					</div>

					<div class="form-row">
						<div class="form-group">
							<label for="password"><?php esc_html_e( 'Password', 'lyststyle-aggregator' ); ?> <span class="required">*</span></label>
							<input
								type="password"
								name="password"
								id="password"
								class="form-control"
								required
							>
							<small><?php esc_html_e( 'Minimum 8 characters', 'lyststyle-aggregator' ); ?></small>
						</div>

						<div class="form-group">
							<label for="password_confirm"><?php esc_html_e( 'Confirm Password', 'lyststyle-aggregator' ); ?> <span class="required">*</span></label>
							<input
								type="password"
								name="password_confirm"
								id="password_confirm"
								class="form-control"
								required
							>
						</div>
					</div>

					<h2><?php esc_html_e( 'Your Preferences', 'lyststyle-aggregator' ); ?></h2>
					<p class="section-description"><?php esc_html_e( 'Help us personalize your experience by sharing your fashion preferences.', 'lyststyle-aggregator' ); ?></p>

					<div class="form-group">
						<label for="gender"><?php esc_html_e( 'Gender', 'lyststyle-aggregator' ); ?></label>
						<select name="gender" id="gender" class="form-control">
							<option value=""><?php esc_html_e( '-- Select --', 'lyststyle-aggregator' ); ?></option>
							<option value="female" <?php selected( isset( $_POST['gender'] ) && 'female' === $_POST['gender'] ); ?>><?php esc_html_e( 'Female', 'lyststyle-aggregator' ); ?></option>
							<option value="male" <?php selected( isset( $_POST['gender'] ) && 'male' === $_POST['gender'] ); ?>><?php esc_html_e( 'Male', 'lyststyle-aggregator' ); ?></option>
							<option value="unisex" <?php selected( isset( $_POST['gender'] ) && 'unisex' === $_POST['gender'] ); ?>><?php esc_html_e( 'Unisex', 'lyststyle-aggregator' ); ?></option>
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
										<?php checked( isset( $_POST['categories'] ) && in_array( $category->slug, (array) $_POST['categories'], true ) ); ?>
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
										<?php checked( isset( $_POST['brands'] ) && in_array( $brand->slug, (array) $_POST['brands'], true ) ); ?>
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
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( isset( $_POST['price_band'] ) && $value === $_POST['price_band'] ); ?>>
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
								value="<?php echo isset( $_POST['price_min'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['price_min'] ) ) ) : ''; ?>"
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
								value="<?php echo isset( $_POST['price_max'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['price_max'] ) ) ) : ''; ?>"
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
										<?php checked( isset( $_POST['colours'] ) && in_array( $colour, (array) $_POST['colours'], true ) ); ?>
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
										<?php checked( isset( $_POST['styles'] ) && in_array( $style->slug, (array) $_POST['styles'], true ) ); ?>
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
										<?php checked( isset( $_POST['occasions'] ) && in_array( $value, (array) $_POST['occasions'], true ) ); ?>
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
								value="<?php echo isset( $_POST['shoe_size'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['shoe_size'] ) ) ) : ''; ?>"
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
								value="<?php echo isset( $_POST['clothing_size_top'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['clothing_size_top'] ) ) ) : ''; ?>"
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
								value="<?php echo isset( $_POST['clothing_size_bottom'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['clothing_size_bottom'] ) ) ) : ''; ?>"
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
								value="<?php echo isset( $_POST['clothing_size_dress'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['clothing_size_dress'] ) ) ) : ''; ?>"
								placeholder="<?php esc_attr_e( 'e.g., 12', 'lyststyle-aggregator' ); ?>"
							>
						</div>
					</div>

					<div class="form-actions">
						<button type="submit" name="lyststyle_register_submit" class="btn-primary btn-large">
							<?php esc_html_e( 'Create Account', 'lyststyle-aggregator' ); ?>
						</button>
					</div>

					<div class="form-footer">
						<?php
						$login_url = lyststyle_get_page_url( 'lyststyle_login_page' );
						if ( $login_url ) :
						?>
						<p>
							<?php esc_html_e( 'Already have an account?', 'lyststyle-aggregator' ); ?>
							<a href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'Login here', 'lyststyle-aggregator' ); ?></a>
						</p>
						<?php endif; ?>
					</div>
				</form>
			</div>

		</div>
	</div>
</main>

<style>
.auth-wrapper {
	max-width: 800px;
	margin: 60px auto;
}

.page-header {
	text-align: center;
	margin-bottom: 40px;
}

.page-header .page-title {
	font-size: 2.5rem;
	margin-bottom: 10px;
}

.page-header .page-description {
	color: #666;
	font-size: 1rem;
}

.auth-form-container {
	background: #fff;
	padding: 40px;
	border-radius: 8px;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.auth-form h2 {
	font-size: 1.5rem;
	margin: 30px 0 20px;
	padding-top: 30px;
	border-top: 1px solid #eee;
}

.auth-form h2:first-of-type {
	margin-top: 0;
	padding-top: 0;
	border-top: none;
}

.auth-form .section-description {
	color: #666;
	font-size: 0.9rem;
	margin-bottom: 20px;
}

.auth-form .form-group {
	margin-bottom: 20px;
}

.auth-form .form-group label {
	display: block;
	font-weight: 600;
	margin-bottom: 8px;
	color: #333;
}

.auth-form .form-group .required {
	color: #e74c3c;
}

.auth-form .form-control {
	width: 100%;
	padding: 12px 16px;
	border: 1px solid #ddd;
	border-radius: 4px;
	font-size: 1rem;
	transition: border-color 0.3s;
}

.auth-form .form-control:focus {
	outline: none;
	border-color: var(--primary-color, #000);
}

.auth-form .form-group small {
	display: block;
	margin-top: 5px;
	color: #666;
	font-size: 0.85rem;
}

.auth-form .form-row {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20px;
}

.auth-form .form-group-checkbox {
	margin-bottom: 25px;
}

.auth-form .checkbox-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
	gap: 10px;
	margin-top: 10px;
}

.auth-form .checkbox-label {
	display: flex;
	align-items: center;
	gap: 8px;
	font-weight: normal;
	margin: 0;
	cursor: pointer;
}

.auth-form .checkbox-label input[type="checkbox"] {
	margin: 0;
	cursor: pointer;
}

.auth-form .form-actions {
	margin-top: 30px;
}

.auth-form .btn-large {
	width: 100%;
	padding: 14px;
	font-size: 1rem;
	font-weight: 600;
}

.auth-form .form-footer {
	margin-top: 20px;
	text-align: center;
	font-size: 0.9rem;
}

.auth-form .form-footer p {
	margin: 10px 0;
}

.auth-form .form-footer a {
	color: var(--primary-color, #000);
	text-decoration: underline;
}

.alert {
	padding: 15px 20px;
	border-radius: 4px;
	margin-bottom: 20px;
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
	.auth-form .form-row {
		grid-template-columns: 1fr;
	}

	.auth-form .checkbox-grid {
		grid-template-columns: 1fr;
	}
}
</style>

<?php
get_footer();
