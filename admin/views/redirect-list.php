<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<h2>All redirects</h2>

<table class="widefat striped urm-table">
	<thead>
		<tr>
			<th>Old URL</th>
			<th>New URL</th>
			<th>Type</th>
			<th>Hits</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php if ( empty( $redirects ) ) : ?>
		<tr>
			<td colspan="6">No redirects yet.</td>
		</tr>
	<?php else : ?>
		<?php foreach ( $redirects as $row ) : ?>
			<?php
			$toggle_url = wp_nonce_url(
				admin_url( 'admin-post.php?action=urm_toggle_redirect&id=' . absint( $row->id ) ),
				'urm_toggle_' . absint( $row->id )
			);
			$delete_url = wp_nonce_url(
				admin_url( 'admin-post.php?action=urm_delete_redirect&id=' . absint( $row->id ) ),
				'urm_delete_' . absint( $row->id )
			);
			?>
			<tr>
				<td><?php echo esc_html( $row->source_url ); ?></td>
				<td><?php echo esc_html( $row->target_url ); ?></td>
				<td><?php echo esc_html( $row->status_code ); ?></td>
				<td><?php echo esc_html( $row->hits ); ?></td>
				<td>
					<span class="urm-badge <?php echo $row->is_active ? 'urm-active' : 'urm-disabled'; ?>">
						<?php echo $row->is_active ? 'Active' : 'Disabled'; ?>
					</span>
				</td>
				<td>
					<a href="<?php echo esc_url( $toggle_url ); ?>">
						<?php echo $row->is_active ? 'Disable' : 'Enable'; ?>
					</a>
					|
					<a href="<?php echo esc_url( $delete_url ); ?>" class="urm-delete">Delete</a>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
</table>