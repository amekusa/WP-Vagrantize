<?php namespace amekusa\WPVagrantize\view?>

<div class="wrap">
	<h2>WP Vagrantize</h2>

	<h3><?php _e('Database') ?></h3>
	<form>
		<?php submit_button(__('Export'), 'primary', 'export-db', false)?>
	</form>

	<h3>ReWP <?php _e('Settings') ?></h3>
	<form>
		<table class="form-table">
			<tbody id="rewp-data-table">
				<tr>
					<td colspan="2"><?php _e('Loading …') ?></td>
				</tr>
				<tr>
					<th data-bind="key"></th>
					<td data-bind="value"></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">
						<?php submit_button(__('Save Changes'), 'primary', 'save-settings', false)?>
						<?php submit_button(__('Reset'), 'large', 'reset-settings', false)?>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>

	<h3><?php _e('Download and Provision!') ?></h3>
	<form>
		<?php submit_button(__('Download'), 'primary', 'download')?>
	</form>
</div>
