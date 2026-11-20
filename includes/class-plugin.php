<?php
namespace URLRedirectManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	/**
	 * Create the classes and connect them together.
	 */
	public function init() {
		$validator = new Validator();
		$manager   = new Redirect_Manager( $validator );

		$frontend = new Frontend( $manager );
		$frontend->register();

		if ( is_admin() ) {
			$admin = new Admin( $manager );
			$admin->register();
		}
	}
}