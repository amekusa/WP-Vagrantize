<?php namespace amekusa\WPVagrantize\view ?>

<div id="wp-vagrantize-menu-root" class="wrap">
	<h2>WP Vagrantize</h2>
	<p>
		<?php _e('In this page, You can make a Vagrant provisioner to ‘Reproduce’ this blog into your local machine.', 'wp-vagrantize') ?><br/>
		<?php _e('Follow the steps below.', 'wp-vagrantize') ?>
	</p>
	<ol>
		<li><a href="#s-export"><?php _e('Export The Database', 'wp-vagrantize') ?></a></li>
		<li><a href="#s-customize"><?php _e('Customize Settings', 'wp-vagrantize') ?></a></li>
		<li><a href="#s-download"><?php _e('Download Your Recipe', 'wp-vagrantize') ?></a></li>
		<li><a href="#s-create"><?php _e('Create A VM From The Provisioner', 'wp-vagrantize') ?></a></li>
	</ol>
	<hr/>

	<h3 id="s-export"><?php _e('Export The Database', 'wp-vagrantize') ?></h3>
	<form id="export-form" action="" method="post">
		<p class="submit">
			<?php submit_button(__('Export'), 'primary', 'export', false) ?>
			<i class="spinner"></i>
		</p>
	</form>
	<hr/>

	<h3 id="s-customize"><?php _e('Customize Settings', 'wp-vagrantize') ?></h3>
	<form id="customize-form" action="" method="post">
		<table class="form-table">
			<tbody id="settings-table">
				<tr>
					<td colspan="2">
						<i class="spinner active"></i>
						<?php _e('Loading …', 'wp-vagrantize') ?>
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

	<h3 id="s-download"><?php _e('Download Provisioner', 'wp-vagrantize') ?></h3>
	<form id="download-form" action="" method="post">
		<p class="submit">
			<?php submit_button(__('Download'), 'primary', 'download', false) ?>
			<i class="spinner"></i>
		</p>
	</form>
	<hr/>

	<h3 id="s-create"><?php _e('Create A VM From The Provisioner', 'wp-vagrantize') ?></h3>
	<ol>
		<li><?php _e('Extract the recipe into any directory you like', 'wp-vagrantize') ?></li>
		<li><p><?php _e('Open Terminal.app or cmd.exe', 'wp-vagrantize') ?></p></li>
		<li>
			<p><?php _e('Type following commands', 'wp-vagrantize') ?></p>
			<pre class="code">
cd /path/to/your/recipe
vagrant up
			</pre>
			<p><?php _e('Then massive console logs start to flowing', 'wp-vagrantize') ?></p>
		</li>
		<li>
			<p>
				<?php _e('If the operation finished without an error, all done!', 'wp-vagrantize') ?><br/>
				Go to the address where you specified as <var>ip</var> or <var>hostname</var> (‘hosts’ setting required) in <a href="#s-customize">‘Customize’</a> section.
			</p>
		</li>
	</ol>
</div>
