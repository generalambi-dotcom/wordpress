<?php
/**
 * User Preferences System
 *
 * @package Lyststyle_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get user preferences
 *
 * @param int $user_id User ID.
 * @return array Array of user preferences with defaults.
 */
function lyststyle_get_user_preferences( $user_id ) {
	$defaults = array(
		'pref_gender'               => 'all',
		'pref_product_categories'   => array(),
		'pref_brands'               => array(),
		'pref_price_band'           => 'mid',
		'pref_price_min'            => 0,
		'pref_price_max'            => 999999,
		'pref_colours'              => array(),
		'pref_styles'               => array(),
		'pref_occasions'            => array(),
		'pref_shoe_size'            => '',
		'pref_clothing_size_top'    => '',
		'pref_clothing_size_bottom' => '',
		'pref_retailers'            => array(),
	);

	$preferences = array();

	foreach ( $defaults as $key => $default ) {
		$value = get_user_meta( $user_id, $key, true );

		if ( is_array( $default ) ) {
			$preferences[ $key ] = ! empty( $value ) && is_array( $value ) ? $value : $default;
		} else {
			$preferences[ $key ] = ! empty( $value ) ? $value : $default;
		}
	}

	return $preferences;
}

/**
 * Save user preferences from request array
 *
 * @param int   $user_id User ID.
 * @param array $request Request data array.
 * @return bool Success status.
 */
function lyststyle_save_user_preferences_from_request( $user_id, $request ) {
	$fields = array(
		'pref_gender'               => 'sanitize_text_field',
		'pref_product_categories'   => 'array_map_int',
		'pref_brands'               => 'array_map_int',
		'pref_price_band'           => 'sanitize_text_field',
		'pref_price_min'            => 'intval',
		'pref_price_max'            => 'intval',
		'pref_colours'              => 'array_map_text',
		'pref_styles'               => 'array_map_text',
		'pref_occasions'            => 'array_map_text',
		'pref_shoe_size'            => 'sanitize_text_field',
		'pref_clothing_size_top'    => 'sanitize_text_field',
		'pref_clothing_size_bottom' => 'sanitize_text_field',
		'pref_retailers'            => 'array_map_int',
	);

	foreach ( $fields as $field => $sanitize_callback ) {
		if ( isset( $request[ $field ] ) ) {
			$value = $request[ $field ];

			// Sanitize based on callback
			switch ( $sanitize_callback ) {
				case 'array_map_int':
					$value = is_array( $value ) ? array_map( 'intval', $value ) : array();
					break;
				case 'array_map_text':
					$value = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : array();
					break;
				case 'intval':
					$value = intval( $value );
					break;
				case 'sanitize_text_field':
				default:
					$value = sanitize_text_field( $value );
					break;
			}

			update_user_meta( $user_id, $field, $value );
		}
	}

	return true;
}

/**
 * Add user preference fields to admin profile
 */
function lyststyle_add_user_profile_fields( $user ) {
	$prefs = lyststyle_get_user_preferences( $user->ID );
	$categories = get_terms( array( 'taxonomy' => 'product_category', 'hide_empty' => false ) );
	$brands = get_terms( array( 'taxonomy' => 'brand', 'hide_empty' => false ) );
	$styles = get_terms( array( 'taxonomy' => 'product_tag', 'hide_empty' => false ) );
	$colours = lyststyle_get_available_colours();
	$occasions = lyststyle_get_available_occasions();
	$price_bands = lyststyle_get_price_bands();
	?>
	<h2><?php esc_html_e( 'Lyststyle Preferences', 'lyststyle-core' ); ?></h2>
	<table class="form-table">
		<tr>
			<th><label for="pref_gender"><?php esc_html_e( 'Gender', 'lyststyle-core' ); ?></label></th>
			<td>
				<select name="pref_gender" id="pref_gender">
					<option value="all" <?php selected( $prefs['pref_gender'], 'all' ); ?>><?php esc_html_e( 'All', 'lyststyle-core' ); ?></option>
					<option value="women" <?php selected( $prefs['pref_gender'], 'women' ); ?>><?php esc_html_e( 'Women', 'lyststyle-core' ); ?></option>
					<option value="men" <?php selected( $prefs['pref_gender'], 'men' ); ?>><?php esc_html_e( 'Men', 'lyststyle-core' ); ?></option>
					<option value="unisex" <?php selected( $prefs['pref_gender'], 'unisex' ); ?>><?php esc_html_e( 'Unisex', 'lyststyle-core' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e( 'Product Categories', 'lyststyle-core' ); ?></label></th>
			<td>
				<?php if ( ! empty( $categories ) ) : ?>
					<?php foreach ( $categories as $category ) : ?>
						<label style="display: block;">
							<input type="checkbox" name="pref_product_categories[]" value="<?php echo esc_attr( $category->term_id ); ?>" <?php checked( in_array( $category->term_id, $prefs['pref_product_categories'] ) ); ?> />
							<?php echo esc_html( $category->name ); ?>
						</label>
					<?php endforeach; ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e( 'Brands', 'lyststyle-core' ); ?></label></th>
			<td>
				<?php if ( ! empty( $brands ) ) : ?>
					<div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
						<?php foreach ( $brands as $brand ) : ?>
							<label style="display: block;">
								<input type="checkbox" name="pref_brands[]" value="<?php echo esc_attr( $brand->term_id ); ?>" <?php checked( in_array( $brand->term_id, $prefs['pref_brands'] ) ); ?> />
								<?php echo esc_html( $brand->name ); ?>
							</label>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="pref_price_band"><?php esc_html_e( 'Price Band', 'lyststyle-core' ); ?></label></th>
			<td>
				<select name="pref_price_band" id="pref_price_band">
					<?php foreach ( $price_bands as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $prefs['pref_price_band'], $key ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e( 'Custom Price Range', 'lyststyle-core' ); ?></label></th>
			<td>
				<label><?php esc_html_e( 'Min:', 'lyststyle-core' ); ?> <input type="number" name="pref_price_min" value="<?php echo esc_attr( $prefs['pref_price_min'] ); ?>" class="small-text" /></label>
				<label><?php esc_html_e( 'Max:', 'lyststyle-core' ); ?> <input type="number" name="pref_price_max" value="<?php echo esc_attr( $prefs['pref_price_max'] ); ?>" class="small-text" /></label>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e( 'Colours', 'lyststyle-core' ); ?></label></th>
			<td>
				<?php foreach ( $colours as $key => $label ) : ?>
					<label style="display: inline-block; margin-right: 15px;">
						<input type="checkbox" name="pref_colours[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $prefs['pref_colours'] ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e( 'Styles', 'lyststyle-core' ); ?></label></th>
			<td>
				<?php if ( ! empty( $styles ) ) : ?>
					<?php foreach ( $styles as $style ) : ?>
						<label style="display: inline-block; margin-right: 15px;">
							<input type="checkbox" name="pref_styles[]" value="<?php echo esc_attr( $style->slug ); ?>" <?php checked( in_array( $style->slug, $prefs['pref_styles'] ) ); ?> />
							<?php echo esc_html( $style->name ); ?>
						</label>
					<?php endforeach; ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e( 'Occasions', 'lyststyle-core' ); ?></label></th>
			<td>
				<?php foreach ( $occasions as $key => $label ) : ?>
					<label style="display: inline-block; margin-right: 15px;">
						<input type="checkbox" name="pref_occasions[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $prefs['pref_occasions'] ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		<tr>
			<th><label for="pref_shoe_size"><?php esc_html_e( 'Shoe Size', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="text" name="pref_shoe_size" id="pref_shoe_size" value="<?php echo esc_attr( $prefs['pref_shoe_size'] ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="pref_clothing_size_top"><?php esc_html_e( 'Clothing Size (Top)', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="text" name="pref_clothing_size_top" id="pref_clothing_size_top" value="<?php echo esc_attr( $prefs['pref_clothing_size_top'] ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="pref_clothing_size_bottom"><?php esc_html_e( 'Clothing Size (Bottom)', 'lyststyle-core' ); ?></label></th>
			<td>
				<input type="text" name="pref_clothing_size_bottom" id="pref_clothing_size_bottom" value="<?php echo esc_attr( $prefs['pref_clothing_size_bottom'] ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'lyststyle_add_user_profile_fields' );
add_action( 'edit_user_profile', 'lyststyle_add_user_profile_fields' );

/**
 * Save user preference fields from admin profile
 */
function lyststyle_save_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	lyststyle_save_user_preferences_from_request( $user_id, $_POST );
}
add_action( 'personal_options_update', 'lyststyle_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'lyststyle_save_user_profile_fields' );
