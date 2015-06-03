<?php namespace amekusa\WPVagrantize\view ?>

<div class="wrap">
	<h2>WP Vagrantize</h2>
	<p><?php _e('You can create your own Vagrant recipe to clone this blog into your local machine.') ?><br/><?php _e('Just follow the steps below.') ?></p>
	<ol class="step-by-step">
		<li><a href="#s-database"><?php _e('Export The Database') ?></a></li>
		<li><a href="#s-vm-settings"><?php _e('Customize VM settings') ?></a></li>
		<li><a href="#s-download"><?php _e('Download your recipe') ?></a></li>
		<li><a href="#s-create-vm"><?php _e('Create a VM from the recipe') ?></a></li>
	</ol>
	<hr/>

	<h3 id="s-database"><?php _e('Database') ?></h3>
	<form>
		<p class="submit">
			<?php submit_button(__('Export'), 'primary', 'export-db', false) ?>
			<i class="spinner"></i>
		</p>
	</form>
	<hr/>

	<h3 id="s-vm-settings"><?php _e('VM Settings') ?></h3>
	<form id="rewp-settings-form" action="" method="post">
		<table class="form-table">
			<tbody id="rewp-settings-table">
				<tr>
					<td colspan="2">
						<i class="spinner active"></i>
						<?php _e('Loading …') ?>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<?php submit_button(__('Save Changes'), 'primary', 'save', false) ?>
			<?php submit_button(__('Reset'), 'secondary', 'reset', false) ?>
			<i class="spinner"></i>
		</p>
	</form>
	<hr/>

	<h3 id="s-download"><?php _e('Download Your Recipe') ?></h3>
	<form>
		<p class="submit">
			<?php submit_button(__('Download'), 'primary', 'download', false) ?>
			<i class="spinner"></i>
		</p>
	</form>
	<hr/>

	<h3 id="s-create-vm"><?php _e('Create a VM') ?></h3>
	<ol>
		<li><?php _e('Extract the recipe into any directory you like') ?></li>
		<li>
			<p><?php _e('Open Terminal') ?></p>
			<pre class="code">
cd /path/to/your/recipe
vagrant up
			</pre>
		</li>
	</ol>
</div>
