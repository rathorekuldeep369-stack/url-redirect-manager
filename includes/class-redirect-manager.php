<?php
namespace URLRedirectManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Redirect_Manager {

	private $wpdb;
	private $table;
	private $validator;

	public function __construct( Validator $validator ) {
		global $wpdb;

		$this->wpdb      = $wpdb;
		$this->table     = Database::get_table_name();
		$this->validator = $validator;
	}

	/**
	 * All redirects, newest first.
	 */
	public function get_all() {
		return $this->wpdb->get_results( "SELECT * FROM {$this->table} ORDER BY id DESC" );
	}

	public function find_by_source( $source ) {
		return $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT * FROM {$this->table} WHERE source_url = %s LIMIT 1",
				$source
			)
		);
	}

	public function find_active_by_source( $source ) {
		return $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT * FROM {$this->table} WHERE source_url = %s AND is_active = 1 LIMIT 1",
				$source
			)
		);
	}

	/**
	 * Validate and save a new redirect.
	 *
	 * @return int|\WP_Error New row ID, or WP_Error on failure.
	 */
	public function add( $source, $target, $status_code ) {
		$data = $this->validator->validate( $source, $target, $status_code );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		if ( $this->find_by_source( $data['source_url'] ) ) {
			return new \WP_Error( 'duplicate', 'A redirect for this old URL already exists.' );
		}

		$inserted = $this->wpdb->insert( $this->table, $data, array( '%s', '%s', '%d' ) );

		if ( false === $inserted ) {
			return new \WP_Error( 'db_error', 'Could not save the redirect.' );
		}

		return (int) $this->wpdb->insert_id;
	}

	public function delete( $id ) {
		return false !== $this->wpdb->delete( $this->table, array( 'id' => absint( $id ) ), array( '%d' ) );
	}

	/**
	 * Switch a redirect between active and disabled.
	 */
	public function toggle( $id ) {
		return false !== $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE {$this->table} SET is_active = 1 - is_active WHERE id = %d",
				absint( $id )
			)
		);
	}

	public function increment_hits( $id ) {
		return false !== $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE {$this->table} SET hits = hits + 1 WHERE id = %d",
				absint( $id )
			)
		);
	}
}