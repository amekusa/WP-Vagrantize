<?php namespace amekusa\WPVagrantize\view ?>

<div class="wrap">
	<h2>WP Vagrantize</h2>
	<p><?php _e('You can create your own Vagrant recipe to clone this blog into your local machine.') ?><br/><?php _e('Just follow the steps below.') ?></p>
	<ol>
		<li><a href="#s-export"><?php _e('Export The Database') ?></a></li>
		<li><a href="#s-customize"><?php _e('Customize Settings') ?></a></li>
		<li><a href="#s-download"><?php _e('Download Your Recipe') ?></a></li>
		<li><a href="#s-create"><?php _e('Create A VM From The Recipe') ?></a></li>
	</ol>
	<hr/>

	<h3 id="s-export"><?php _e('Export The Database') ?></h3>
	<form id="export-form" action="" method="post">
		<p class="submit">
			<?php submit_button(__('Export'), 'primary', 'export-db', false) ?>
			<i class="spinner"></i>
		</p>
	</form>
	<hr/>

	<h3 id="s-customize"><?php _e('Customize Settings') ?></h3>
	<form id="customize-form" action="" method="post">
		<table class="form-table">
			<tbody id="settings-table">
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

	<h3 id="s-create"><?php _e('Create A VM From The Recipe') ?></h3>
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
