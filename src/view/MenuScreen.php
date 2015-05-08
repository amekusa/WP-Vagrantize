<?php namespace amekusa\WPVagrantize\view ?>

<div class="wrap">
	<h2>WP Vagrantize</h2>

	<h3><?php _e('Database') ?></h3>
	<form>
		<?php submit_button(__('Export'), 'primary', 'export-db', false) ?>
	</form>

	<h3>ReWP <?php _e('Settings') ?></h3>
	<form id="rewp-settings-form" action="" method="post">
		<table class="form-table">
			<tbody id="rewp-settings-table">
				<tr>
					<td colspan="2"><?php _e('Loading …') ?></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<?php submit_button(__('Save Changes'), 'primary', 'save', false) ?>
			<?php submit_button(__('Reset'), 'secondary', 'reset', false) ?>
		</p>
	</form>

	<h3><?php _e('Download and Provision!') ?></h3>
	<form>
		<?php submit_button(__('Download'), 'primary', 'download') ?>
	</form>
</div>
