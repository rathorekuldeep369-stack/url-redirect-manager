<?php
namespace URLRedirectManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {

	const SLUG = 'url-redirect-manager';

	private $manager;

	public function __construct( Redirect_Manager $manager ) {
		$this->manager = $manager;
	}

	public function register() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_urm_add_redirect', array( $this, 'handle_add' ) );
		add_action( 'admin_post_urm_delete_redirect', array( $this, 'handle_delete' ) );
		add_action( 'admin_post_urm_toggle_redirect', array( $this, 'handle_toggle' ) );
	}

	public function add_menu() {
		add_menu_page(
			'URL Redirect Manager',
			'Redirects',
			'manage_options',
			self::SLUG,
			array( $this, 'render_page' ),
			'dashicons-randomize',
			80
		);
	}

	public function enqueue_assets( $hook ) {
		if ( 'toplevel_page_' . self::SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style( 'urm-admin', URM_PLUGIN_URL . 'admin/css/admin.css', array(), URM_VERSION );
		wp_enqueue_script( 'urm-admin', URM_PLUGIN_URL . 'assets/js/admin.js', array(), URM_VERSION, true );
	}

	/* ---------- Form handlers ---------- */

	public function handle_add() {
		$this->authorize();
		check_admin_referer( 'urm_add_redirect' );

		$source = isset( $_POST['source_url'] ) ? wp_unslash( $_POST['source_url'] ) : '';
		$target = isset( $_POST['target_url'] ) ? wp_unslash( $_POST['target_url'] ) : '';
		$code   = isset( $_POST['status_code'] ) ? wp_unslash( $_POST['status_code'] ) : 301;

		$result = $this->manager->add( $source, $target, $code );

		if ( is_wp_error( $result ) ) {
			$this->notify( 'error', implode( ' ', $result->get_error_messages() ) );
		} else {
			$this->notify( 'success', 'Redirect added.' );
		}

		$this->back();
	}

	public function handle_delete() {
		$this->authorize();

		$id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		check_admin_referer( 'urm_delete_' . $id );

		$this->manager->delete( $id );
		$this->notify( 'success', 'Redirect deleted.' );
		$this->back();
	}

	public function handle_toggle() {
		$this->authorize();

		$id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		check_admin_referer( 'urm_toggle_' . $id );

		$this->manager->toggle( $id );
		$this->notify( 'success', 'Redirect updated.' );
		$this->back();
	}

	/* ---------- Helpers ---------- */

	private function authorize() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You are not allowed to do this.' );
		}
	}

	private function notify( $type, $message ) {
		set_transient(
			'urm_notice_' . get_current_user_id(),
			array(
				'type'    => $type,
				'message' => $message,
			),
			60
		);
	}

	private function back() {
		wp_safe_redirect( admin_url( 'admin.php?page=' . self::SLUG ) );
		exit;
	}

	/* ---------- Page ---------- */

	public function render_page() {
		$this->authorize();

		$notice_key = 'urm_notice_' . get_current_user_id();
		$notice     = get_transient( $notice_key );
		delete_transient( $notice_key );

		$redirects = $this->manager->get_all();
		?>
		<div class="wrap urm-wrap">
			<h1>URL Redirect Manager</h1>

			<?php if ( $notice ) : ?>
				<div class="notice notice-<?php echo esc_attr( $notice['type'] ); ?> is-dismissible">
					<p><?php echo esc_html( $notice['message'] ); ?></p>
				</div>
			<?php endif; ?>

			<?php
			include URM_PLUGIN_DIR . 'admin/views/redirect-form.php';
			include URM_PLUGIN_DIR . 'admin/views/redirect-list.php';
			?>
		</div>
		<?php
	}
}