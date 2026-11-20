<?php
namespace URLRedirectManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {

	private $manager;

	public function __construct( Redirect_Manager $manager ) {
		$this->manager = $manager;
	}

	public function register() {
		add_action( 'template_redirect', array( $this, 'maybe_redirect' ), 1 );
	}

	public function maybe_redirect() {
		if ( is_admin() ) {
			return;
		}

		$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		$path    = Validator::normalize_path( $request );

		// If WordPress lives in a subfolder (e.g. /local), remove that part.
		$home_path = Validator::normalize_path( home_url() );
		if ( '/' !== $home_path && 0 === strpos( $path, $home_path ) ) {
			$path = '/' . ltrim( substr( $path, strlen( $home_path ) ), '/' );
		}

		$rule = $this->manager->find_active_by_source( $path );

		if ( ! $rule ) {
			return;
		}

		$target = $rule->target_url;

		// Relative target like /new-page: make it a full URL for this site.
		if ( ! wp_parse_url( $target, PHP_URL_HOST ) ) {
			// Loop protection: skip if the target points to the same path.
			if ( Validator::normalize_path( $target ) === $path ) {
				return;
			}
			$target = home_url( $target );
		}

		$this->manager->increment_hits( $rule->id );

		// wp_redirect (not wp_safe_redirect) because targets can be external sites.
		wp_redirect( esc_url_raw( $target ), (int) $rule->status_code );
		exit;
	}
}