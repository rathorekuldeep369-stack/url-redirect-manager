<?php
namespace URLRedirectManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Database {

	/**
	 * Full table name, e.g. wp_urm_redirects
	 */
	public static function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . 'urm_redirects';
	}

	/**
	 * Create the redirects table.
	 */
	public static function create_table() {
		global $wpdb;

		$table   = self::get_table_name();
		$charset = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			source_url VARCHAR(255) NOT NULL,
			target_url TEXT NOT NULL,
			status_code SMALLINT UNSIGNED NOT NULL DEFAULT 301,
			is_active TINYINT(1) NOT NULL DEFAULT 1,
			hits BIGINT UNSIGNED NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY source_url (source_url(191))
		) $charset;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}