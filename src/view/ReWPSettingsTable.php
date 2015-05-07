<?php namespace amekusa\WPVagrantize\view ?>

<?php foreach ($data as $i => $iData) { ?>
<tr>
	<th><?php echo $i ?></th>
	<td>
		<?php if (is_bool($iData)) { ?>
		<label class="code"><input type="checkbox" name="<?php echo esc_attr($i) ?>" value="true"<?php if ($iData) { ?> checked<?php } ?> />true</label>
		<?php } else { ?>
		<?php 	$content = (string) $iData ?>
		<?php 	$nRows = substr_count($content, "\n") + 1 ?>
		<?php 	if ($nRows < 2) { ?>
		<input class="large-text code" type="text" name="<?php echo esc_attr($i) ?>" value="<?php echo esc_attr($content) ?>" />
		<?php 	} else { ?>
		<textarea class="large-text code" name="<?php echo esc_attr($i) ?>" rows="<?php echo $nRows ?>"><?php echo esc_html($content) ?></textarea>
		<?php 	} ?>
		<?php } ?>
	</td>
</tr>
<?php } ?>
