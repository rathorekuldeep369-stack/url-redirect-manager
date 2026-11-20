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
		// The database table will be created here in feature/database.
		add_option( 'urm_version', URM_VERSION );
	}
}