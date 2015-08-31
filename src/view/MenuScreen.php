<?php namespace amekusa\WPVagrantize\view ?>

<div id="wp-vagrantize-menu-root" class="wrap">
	<h2>WP Vagrantize</h2>
	<p>
		<?php _e('In this page, You can make a Vagrant provisioner to ‘Reproduce’ this blog into your local machine.', 'wp-vagrantize') ?><br/>
		<?php _e('Follow the steps below.', 'wp-vagrantize') ?>
	</p>
	<ol>
		<li><a href="#s-customize"><?php _e('Customize Settings', 'wp-vagrantize') ?></a></li>
		<li><a href="#s-download"><?php _e('Download A Provisioner', 'wp-vagrantize') ?></a></li>
		<li><a href="#s-vagrant-up"><?php _e('Vagrant Up', 'wp-vagrantize') ?></a></li>
	</ol>
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

	<h3 id="s-download"><?php _e('Download A Provisioner', 'wp-vagrantize') ?></h3>
	<p>
		If <var>import_sql</var> is <var>true</var> (default) in the settings above, <strong>a database will be exported</strong> into a downloaded file.<br/>
		The exported database will be imported into a virtual machine automatically when you provision.
	</p>
	<form id="download-form" action="" method="post">
		<p class="submit">
			<?php submit_button(__('Download'), 'primary', 'download', false) ?>
			<i class="spinner"></i>
		</p>
	</form>
	<hr/>

	<h3 id="s-vagrant-up"><?php _e('Vagrant Up', 'wp-vagrantize') ?></h3>
	<ol>
		<li><?php _e('Extract the provisioner into your working directory', 'wp-vagrantize') ?></li>
		<li><p><?php _e('Open Terminal.app or cmd.exe', 'wp-vagrantize') ?></p></li>
		<li>
			<p><?php _e('Type the following commands', 'wp-vagrantize') ?></p>
			<pre class="code">
cd /path/to/provisioner
vagrant up
			</pre>
			<p><?php _e('Then the provision starts and You’ll see massive console logs…', 'wp-vagrantize') ?></p>
		</li>
		<li>
			<p>
				<?php _e('If the operation finished without an error, all done!', 'wp-vagrantize') ?><br/>
				Go to the address where you specified as <var>ip</var> or <var>hostname</var> (hosts setting required) in <a href="#s-customize">Customize</a> section.
			</p>
		</li>
	</ol>
	<hr/>

	<h3>For more details, see:</h3>
	<ul>
		<li><a href="https://github.com/amekusa/ReWP">github.com/amekusa/<strong>ReWP</strong></a></li>
		<li><a href="https://github.com/vccw-team/vccw">github.com/vccw-team/<strong>VCCW</strong></a></li>
		<li><a href="https://docs.vagrantup.com/v2/getting-started/index.html">Getting Started - <strong>Vagrant</strong> Documentation</a></li>
	</ul>
</div>
