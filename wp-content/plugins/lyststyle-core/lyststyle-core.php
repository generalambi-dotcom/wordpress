<?php
/**
 * Plugin Name: Lyststyle Core
 * Plugin URI: https://lyststyle.com
 * Description: Core business logic for Lyststyle fashion aggregator - custom post types, taxonomies, user preferences, tracking, and recommendations
 * Version: 1.0.0
 * Author: Lyststyle
 * Author URI: https://lyststyle.com
 * Text Domain: lyststyle-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'LYSTSTYLE_CORE_VERSION', '1.0.0' );
define( 'LYSTSTYLE_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'LYSTSTYLE_CORE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Lyststyle_Core Class
 */
class Lyststyle_Core {

	/**
	 * Singleton instance
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required files
	 */
	private function includes() {
		require_once LYSTSTYLE_CORE_PATH . 'inc/helpers.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/cpt-product.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/cpt-article.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/cpt-retailer.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/taxonomies.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/product-meta.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/user-preferences.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/events-tracking.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/recommendations.php';
		require_once LYSTSTYLE_CORE_PATH . 'inc/rest-api.php';
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Plugin activation
	 */
	public function activate() {
		// Create database tables
		lyststyle_create_events_tables();

		// Flush rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Load plugin textdomain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'lyststyle-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

// Initialize plugin
function lyststyle_core() {
	return Lyststyle_Core::get_instance();
}

lyststyle_core();
