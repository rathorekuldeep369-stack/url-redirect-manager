<?php
namespace URLRedirectManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Validator {

	/**
	 * Clean and validate the redirect data.
	 *
	 * @return array|\WP_Error Clean data on success, WP_Error on failure.
	 */
	public function validate( $source, $target, $status_code ) {
		$source      = self::normalize_path( $source );
		$target      = esc_url_raw( trim( (string) $target ) );
		$status_code = (int) $status_code;

		$errors = new \WP_Error();

		if ( '/' === $source ) {
			$errors->add( 'invalid_source', 'Old URL is required and cannot be the homepage.' );
		}

		if ( '' === $target ) {
			$errors->add( 'invalid_target', 'New URL is required.' );
		}

		if ( ! in_array( $status_code, array( 301, 302 ), true ) ) {
			$errors->add( 'invalid_status', 'Redirect type must be 301 or 302.' );
		}

		// A relative target that points to itself would cause a redirect loop.
		if ( '' !== $target && ! wp_parse_url( $target, PHP_URL_HOST ) && self::normalize_path( $target ) === $source ) {
			$errors->add( 'redirect_loop', 'Old URL and new URL cannot be the same.' );
		}

		if ( $errors->has_errors() ) {
			return $errors;
		}

		return array(
			'source_url'  => $source,
			'target_url'  => $target,
			'status_code' => $status_code,
		);
	}

	/**
	 * "https://site.com/Old-Page/?a=1" becomes "/old-page"
	 */
	public static function normalize_path( $url ) {
		$path = (string) wp_parse_url( sanitize_text_field( (string) $url ), PHP_URL_PATH );
		return '/' . strtolower( trim( $path, '/' ) );
	}
}