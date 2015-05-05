<?php namespace amekusa\WPVagrantize\view ?>
<?php ?>
<?php foreach ($data as $i => $iVal) { ?>
<tr>
	<th><?php echo $i ?></th>
	<td>
		<?php
		$content = (string) $iVal;
		$nRows = substr_count($content, "\n") + 1;
		?>
		<?php if ($nRows < 2) { ?>
		<input class="large-text code" type="text" value="<?php echo esc_attr($content) ?>" />
		<?php } else { ?>
		<textarea class="large-text code" rows="<?php echo $nRows ?>"><?php echo esc_html($content) ?></textarea>
		<?php } ?>
	</td>
</tr>
<?php } ?>
