<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_MASK_LIST_LABEL; ?>

	</legend>
	<table border="0" cellspacing="3" cellpadding="2">
		<tr>
			<th style="border-right: 1px solid #000;text-align:left;"><?php echo MASK_NAME_LABEL; ?></th>
			<th style="border-left: 1px solid #000;border-right: 1px solid #000;text-align:left;"><?php echo EDIT_LABEL; ?></th>
			<th style="border-left: 1px solid #000;text-align:left;"><?php echo REMOVE_LABEL; ?></th>
		</tr>
<?php
	// $masks_result is provided by admin.php
	while ($masks_row = $qls->SQL->fetch_array($masks_result)) {
?>
		<tr>
			<td align="left"><?php echo stripslashes($masks_row['name']); ?></td>
			<td align="left">
				<a href="#" onclick="javascript:mask_change('edit_mask', '<?php echo $masks_row['id']; ?>');" class="main"><?php echo EDIT_LABEL; ?></a>
			</td>
			<td align="left">
				<a href="#" onclick="javascript:mask_change('remove_mask','<?php echo $masks_row['id']; ?>');" class="main"><?php echo REMOVE_LABEL; ?></a>
			</td>
		</tr>
<?php
	}
?>
	</table>
</fieldset>