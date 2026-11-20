<?php
/**
 * Runs when the plugin is deleted from the Plugins screen.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$table = $wpdb->prefix . 'urm_redirects';
$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

delete_option( 'urm_version' );