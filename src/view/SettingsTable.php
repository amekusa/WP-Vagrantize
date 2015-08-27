<?php namespace amekusa\WPVagrantize\view ?>

<?php foreach ($data as $i => $iData) { ?>
<tr>
	<th scope="row"><?php echo esc_html($i) ?></th>
	<td>
		<?php if (is_bool($iData)) { ?>
		<input type="hidden" name="<?php echo esc_attr($i) ?>" value="false" />
		<label><input type="checkbox" name="<?php echo esc_attr($i) ?>" value="true"<?php if ($iData) { ?> checked<?php } ?> />true</label>
		<?php } else if (is_array($iData)) { ?>
		<?php   foreach ($iData as $j => $jData) { ?>
		<?php     if (is_string($j)) { // Associative array ?>
		<label><?php echo esc_html($j) ?> : <input class="large-text code" type="text" name="<?php echo esc_attr("{$i}[{$j}]") ?>" value="<?php echo esc_attr((string) $jData) ?>" /></label>
		<?php     } else { // Sequential array ?>
		<div class="extensible">
			<input class="large-text code" type="text" name="<?php echo esc_attr("{$i}[]") ?>" value="<?php echo esc_attr((string) $jData) ?>" />
		</div>
		<?php     } ?>
		<?php   } ?>
		<?php } else { ?>
		<?php   $value = (string) $iData ?>
		<?php   $nRows = substr_count($value, "\n") + 1 ?>
		<?php   if ($nRows < 2) { ?>
		<input class="large-text code" type="text" name="<?php echo esc_attr($i) ?>" value="<?php echo esc_attr($value) ?>" />
		<?php   } else { ?>
		<textarea class="large-text code" name="<?php echo esc_attr($i) ?>" rows="<?php echo $nRows ?>"><?php echo esc_html($value) ?></textarea>
		<?php   } ?>
		<?php } ?>
	</td>
</tr>
<?php } ?>
