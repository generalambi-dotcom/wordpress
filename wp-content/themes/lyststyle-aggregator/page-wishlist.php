<?php
/**
 * Template Name: Wishlist Page
 * The template for displaying the wishlist page
 *
 * @package Lyststyle_Aggregator
 */

get_header();

$is_logged_in = is_user_logged_in();
$wishlist_products = array();

// Get wishlist for logged-in users
if ( $is_logged_in ) {
	$user_id = get_current_user_id();
	$wishlist_ids = get_user_meta( $user_id, 'lyststyle_wishlist', true );

	if ( ! is_array( $wishlist_ids ) ) {
		$wishlist_ids = array();
	}

	if ( ! empty( $wishlist_ids ) ) {
		$wishlist_products = get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post__in'       => $wishlist_ids,
			'orderby'        => 'post__in',
		) );
	}
}
?>

<main id="primary" class="site-main page-wishlist">
	<div class="container">

		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'My Wishlist', 'lyststyle-aggregator' ); ?></h1>
			<p class="page-description"><?php esc_html_e( 'Your saved favorite items', 'lyststyle-aggregator' ); ?></p>
		</header>

		<?php if ( $is_logged_in ) : ?>
			<!-- Logged-in user: Server-side rendered wishlist -->
			<?php if ( ! empty( $wishlist_products ) ) : ?>
				<div class="wishlist-products products-grid" data-wishlist-container>
					<?php foreach ( $wishlist_products as $product ) : ?>
						<?php
						$product_id = $product->ID;
						$price = lyststyle_get_product_price( $product_id );
						$currency = get_post_meta( $product_id, '_product_currency', true );
						if ( ! $currency ) {
							$currency = 'GBP';
						}
						$brand = lyststyle_get_product_brand( $product_id );
						$thumbnail = get_the_post_thumbnail_url( $product_id, 'product-thumbnail' );
						?>
						<div class="product-card" data-product-id="<?php echo esc_attr( $product_id ); ?>">
							<div class="product-image-wrapper">
								<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
									<?php if ( $thumbnail ) : ?>
										<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( get_the_title( $product_id ) ); ?>" class="product-image">
									<?php else : ?>
										<div class="product-image-placeholder"></div>
									<?php endif; ?>
								</a>
								<button class="wishlist-btn remove-from-wishlist active" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Remove from wishlist', 'lyststyle-aggregator' ); ?>">
									<?php echo lyststyle_get_icon( 'heart-filled' ); ?>
								</button>
							</div>
							<div class="product-info">
								<?php if ( $brand ) : ?>
									<div class="product-brand"><?php echo esc_html( $brand ); ?></div>
								<?php endif; ?>
								<h3 class="product-title">
									<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
										<?php echo esc_html( get_the_title( $product_id ) ); ?>
									</a>
								</h3>
								<div class="product-price">
									<?php echo esc_html( lyststyle_format_price( $price, $currency ) ); ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<!-- Empty wishlist state -->
				<div class="wishlist-empty">
					<div class="empty-icon"><?php echo lyststyle_get_icon( 'heart' ); ?></div>
					<h2><?php esc_html_e( 'Your wishlist is empty', 'lyststyle-aggregator' ); ?></h2>
					<p><?php esc_html_e( 'Start adding your favorite items by clicking the heart icon on any product.', 'lyststyle-aggregator' ); ?></p>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="btn-primary">
						<?php esc_html_e( 'Browse Products', 'lyststyle-aggregator' ); ?>
					</a>
				</div>
			<?php endif; ?>

		<?php else : ?>
			<!-- Not logged-in user: JavaScript-managed wishlist from localStorage -->
			<div class="wishlist-container">
				<div class="wishlist-loading">
					<p><?php esc_html_e( 'Loading your wishlist...', 'lyststyle-aggregator' ); ?></p>
				</div>

				<div class="wishlist-products products-grid" data-wishlist-container style="display: none;"></div>

				<div class="wishlist-empty" style="display: none;">
					<div class="empty-icon"><?php echo lyststyle_get_icon( 'heart' ); ?></div>
					<h2><?php esc_html_e( 'Your wishlist is empty', 'lyststyle-aggregator' ); ?></h2>
					<p><?php esc_html_e( 'Start adding your favorite items by clicking the heart icon on any product.', 'lyststyle-aggregator' ); ?></p>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="btn-primary">
						<?php esc_html_e( 'Browse Products', 'lyststyle-aggregator' ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>

	</div>
</main>

<style>
.page-wishlist .page-header {
	text-align: center;
	margin-bottom: 40px;
}

.page-wishlist .page-title {
	font-size: 2.5rem;
	margin-bottom: 10px;
}

.page-wishlist .page-description {
	color: #666;
	font-size: 1rem;
}

.wishlist-loading {
	text-align: center;
	padding: 60px 20px;
	color: #666;
}

.wishlist-empty {
	text-align: center;
	padding: 80px 20px;
	max-width: 500px;
	margin: 0 auto;
}

.wishlist-empty .empty-icon {
	font-size: 4rem;
	color: #ddd;
	margin-bottom: 20px;
}

.wishlist-empty .empty-icon svg {
	width: 80px;
	height: 80px;
}

.wishlist-empty h2 {
	font-size: 2rem;
	margin-bottom: 15px;
	color: #333;
}

.wishlist-empty p {
	color: #666;
	font-size: 1.1rem;
	margin-bottom: 30px;
}

.wishlist-empty .btn-primary {
	display: inline-block;
	padding: 14px 40px;
	font-size: 1rem;
	font-weight: 600;
}

.wishlist-products.products-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
	gap: 30px;
	margin-top: 20px;
}

.product-card {
	background: #fff;
	border-radius: 8px;
	overflow: hidden;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
	transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
	transform: translateY(-5px);
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.product-image-wrapper {
	position: relative;
	overflow: hidden;
	aspect-ratio: 3/4;
	background: #f8f8f8;
}

.product-image {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.3s;
}

.product-card:hover .product-image {
	transform: scale(1.05);
}

.product-image-placeholder {
	width: 100%;
	height: 100%;
	background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
}

.wishlist-btn {
	position: absolute;
	top: 12px;
	right: 12px;
	background: rgba(255, 255, 255, 0.9);
	border: none;
	border-radius: 50%;
	width: 40px;
	height: 40px;
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: all 0.3s;
	color: #666;
	z-index: 2;
}

.wishlist-btn:hover,
.wishlist-btn.active {
	background: #fff;
	color: var(--accent-color, #FF6B6B);
}

.wishlist-btn svg {
	width: 20px;
	height: 20px;
}

.product-info {
	padding: 20px;
}

.product-brand {
	font-size: 0.85rem;
	color: #666;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	margin-bottom: 8px;
}

.product-title {
	font-size: 1.1rem;
	margin: 0 0 12px;
	font-weight: 600;
}

.product-title a {
	color: #333;
	text-decoration: none;
	transition: color 0.3s;
}

.product-title a:hover {
	color: var(--accent-color, #FF6B6B);
}

.product-price {
	font-size: 1.2rem;
	font-weight: 700;
	color: #000;
}

@media (max-width: 768px) {
	.wishlist-products.products-grid {
		grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
		gap: 20px;
	}

	.product-info {
		padding: 15px;
	}

	.product-title {
		font-size: 1rem;
	}

	.product-price {
		font-size: 1.1rem;
	}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	<?php if ( ! $is_logged_in ) : ?>
	// For non-logged-in users, load wishlist from localStorage
	const loadWishlistFromLocalStorage = function() {
		const wishlistContainer = document.querySelector('[data-wishlist-container]');
		const loadingElement = document.querySelector('.wishlist-loading');
		const emptyElement = document.querySelector('.wishlist-empty');

		if (!wishlistContainer) return;

		// Get wishlist from localStorage
		const wishlist = JSON.parse(localStorage.getItem('lyststyle_wishlist') || '[]');

		loadingElement.style.display = 'none';

		if (wishlist.length === 0) {
			emptyElement.style.display = 'block';
			return;
		}

		// Fetch product details via AJAX
		fetch(lyststyleData.ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: new URLSearchParams({
				action: 'lyststyle_get_wishlist',
				nonce: lyststyleData.nonce,
				product_ids: JSON.stringify(wishlist)
			})
		})
		.then(response => response.json())
		.then(data => {
			if (data.success && data.data.products && data.data.products.length > 0) {
				wishlistContainer.innerHTML = '';
				data.data.products.forEach(product => {
					const productCard = createProductCard(product);
					wishlistContainer.appendChild(productCard);
				});
				wishlistContainer.style.display = 'grid';
			} else {
				emptyElement.style.display = 'block';
			}
		})
		.catch(error => {
			console.error('Error loading wishlist:', error);
			emptyElement.style.display = 'block';
		});
	};

	const createProductCard = function(product) {
		const card = document.createElement('div');
		card.className = 'product-card';
		card.setAttribute('data-product-id', product.id);

		const imageUrl = product.image || '';
		const imageHTML = imageUrl
			? `<img src="${imageUrl}" alt="${product.title}" class="product-image">`
			: '<div class="product-image-placeholder"></div>';

		card.innerHTML = `
			<div class="product-image-wrapper">
				<a href="${product.url}">
					${imageHTML}
				</a>
				<button class="wishlist-btn remove-from-wishlist active" data-product-id="${product.id}" aria-label="Remove from wishlist">
					<?php echo addslashes( lyststyle_get_icon( 'heart-filled' ) ); ?>
				</button>
			</div>
			<div class="product-info">
				${product.brand ? `<div class="product-brand">${product.brand}</div>` : ''}
				<h3 class="product-title">
					<a href="${product.url}">${product.title}</a>
				</h3>
				<div class="product-price">
					${product.currency === 'GBP' ? '£' : product.currency}${parseFloat(product.price).toFixed(2)}
				</div>
			</div>
		`;

		return card;
	};

	loadWishlistFromLocalStorage();
	<?php endif; ?>

	// Handle wishlist removal (both logged-in and not logged-in)
	document.addEventListener('click', function(e) {
		if (e.target.closest('.remove-from-wishlist')) {
			const button = e.target.closest('.remove-from-wishlist');
			const productId = parseInt(button.getAttribute('data-product-id'));
			const productCard = button.closest('.product-card');

			<?php if ( $is_logged_in ) : ?>
			// For logged-in users, remove via AJAX
			fetch(lyststyleData.ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'lyststyle_remove_from_wishlist',
					nonce: lyststyleData.nonce,
					product_id: productId
				})
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					productCard.remove();

					// Check if wishlist is now empty
					const remainingProducts = document.querySelectorAll('.product-card');
					if (remainingProducts.length === 0) {
						document.querySelector('.wishlist-products').style.display = 'none';
						const emptyState = document.querySelector('.wishlist-empty');
						if (emptyState) {
							emptyState.style.display = 'block';
						}
					}
				}
			});
			<?php else : ?>
			// For non-logged-in users, remove from localStorage
			let wishlist = JSON.parse(localStorage.getItem('lyststyle_wishlist') || '[]');
			wishlist = wishlist.filter(id => id !== productId);
			localStorage.setItem('lyststyle_wishlist', JSON.stringify(wishlist));

			productCard.remove();

			// Check if wishlist is now empty
			const remainingProducts = document.querySelectorAll('.product-card');
			if (remainingProducts.length === 0) {
				document.querySelector('.wishlist-products').style.display = 'none';
				document.querySelector('.wishlist-empty').style.display = 'block';
			}
			<?php endif; ?>
		}
	});
});
</script>

<?php
get_footer();
