<?php
/**
 * Template Name: Login Page
 * The template for displaying the login page
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

// Handle login form submission
if ( isset( $_POST['lyststyle_login_submit'] ) ) {
	// Verify nonce
	if ( ! isset( $_POST['lyststyle_login_nonce'] ) || ! wp_verify_nonce( $_POST['lyststyle_login_nonce'], 'lyststyle_login_action' ) ) {
		$error_message = __( 'Security verification failed. Please try again.', 'lyststyle-aggregator' );
	} else {
		$username = isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '';
		$password = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$remember = isset( $_POST['remember'] );

		if ( empty( $username ) || empty( $password ) ) {
			$error_message = __( 'Please enter both username/email and password.', 'lyststyle-aggregator' );
		} else {
			// Attempt login
			$credentials = array(
				'user_login'    => $username,
				'user_password' => $password,
				'remember'      => $remember,
			);

			$user = wp_signon( $credentials, is_ssl() );

			if ( is_wp_error( $user ) ) {
				$error_message = $user->get_error_message();
			} else {
				// Login successful - redirect to My Account page
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
?>

<main id="primary" class="site-main page-login">
	<div class="container">
		<div class="auth-wrapper">

			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Login', 'lyststyle-aggregator' ); ?></h1>
				<p class="page-description"><?php esc_html_e( 'Welcome back! Please login to your account.', 'lyststyle-aggregator' ); ?></p>
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

			<div class="auth-form-container">
				<form method="post" action="" class="auth-form login-form">
					<?php wp_nonce_field( 'lyststyle_login_action', 'lyststyle_login_nonce' ); ?>

					<div class="form-group">
						<label for="username"><?php esc_html_e( 'Username or Email', 'lyststyle-aggregator' ); ?> <span class="required">*</span></label>
						<input
							type="text"
							name="username"
							id="username"
							class="form-control"
							value="<?php echo isset( $_POST['username'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['username'] ) ) ) : ''; ?>"
							required
						>
					</div>

					<div class="form-group">
						<label for="password"><?php esc_html_e( 'Password', 'lyststyle-aggregator' ); ?> <span class="required">*</span></label>
						<input
							type="password"
							name="password"
							id="password"
							class="form-control"
							required
						>
					</div>

					<div class="form-group form-checkbox">
						<label>
							<input
								type="checkbox"
								name="remember"
								value="1"
								<?php checked( isset( $_POST['remember'] ) ); ?>
							>
							<?php esc_html_e( 'Remember me', 'lyststyle-aggregator' ); ?>
						</label>
					</div>

					<div class="form-actions">
						<button type="submit" name="lyststyle_login_submit" class="btn-primary btn-large">
							<?php esc_html_e( 'Login', 'lyststyle-aggregator' ); ?>
						</button>
					</div>

					<div class="form-footer">
						<p>
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'lyststyle-aggregator' ); ?></a>
						</p>
						<?php
						$register_url = lyststyle_get_page_url( 'lyststyle_register_page' );
						if ( $register_url ) :
						?>
						<p>
							<?php esc_html_e( "Don't have an account?", 'lyststyle-aggregator' ); ?>
							<a href="<?php echo esc_url( $register_url ); ?>"><?php esc_html_e( 'Register here', 'lyststyle-aggregator' ); ?></a>
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
	max-width: 500px;
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

.auth-form .form-checkbox label {
	font-weight: normal;
	display: flex;
	align-items: center;
	gap: 8px;
}

.auth-form .form-checkbox input[type="checkbox"] {
	margin: 0;
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
</style>

<?php
get_footer();
