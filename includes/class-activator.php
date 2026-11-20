<?php
namespace URLRedirectManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Activator {

	/**
	 * Runs once when the plugin is activated.
	 */
	public static function activate() {
		Database::create_table();
		add_option( 'urm_version', URM_VERSION );
	}
}