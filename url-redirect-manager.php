
<?php
/**
 * Plugin Name: URL Redirect Manager
 * Plugin URI: https://example.com/
 * Description: Manage 301 and 302 redirects from the WordPress admin dashboard.
 * Version: 1.2.3
 * Author: Kuldeep Singh
 * Text Domain: url-redirect-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Plugin constants.
 */
define( 'URM_VERSION', '1.0.0' );
define( 'URM_PLUGIN_FILE', __FILE__ );
define( 'URM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'URM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include required classes.
 */
require_once URM_PLUGIN_DIR . 'includes/class-activator.php';
require_once URM_PLUGIN_DIR . 'includes/class-plugin.php';
require_once URM_PLUGIN_DIR . 'includes/class-database.php';

/**
 * Register activation hook.
 */
register_activation_hook(
    __FILE__,
    array( '\URLRedirectManager\Activator', 'activate' )
);

/**
 * Initialize the plugin.
 */
add_action( 'plugins_loaded', function() {

    $plugin = new \URLRedirectManager\Plugin();

    $plugin->init();

} );
