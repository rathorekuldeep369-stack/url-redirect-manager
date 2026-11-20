<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<h2>Add new redirect</h2>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="urm_add_redirect">
	<?php wp_nonce_field( 'urm_add_redirect' ); ?>

	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="urm_source_url">Old URL (path)</label></th>
			<td>
				<input type="text" id="urm_source_url" name="source_url" class="regular-text" placeholder="/old-page" required>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="urm_target_url">New URL</label></th>
			<td>
				<input type="text" id="urm_target_url" name="target_url" class="regular-text" placeholder="/new-page or https://example.com" required>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="urm_status_code">Redirect type</label></th>
			<td>
				<select id="urm_status_code" name="status_code">
					<option value="301">301 - Permanent</option>
					<option value="302">302 - Temporary</option>
				</select>
			</td>
		</tr>
	</table>

	<?php submit_button( 'Add Redirect' ); ?>
</form>