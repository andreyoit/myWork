<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_EDIT_MASK_LABEL; ?>

	</legend>
	<form action="#" method="get">
		<input type="hidden" id="type" name="type" value="process" />
		<table border="0">
			<tr>
				<td>
					<?php echo MASK_NAME_LABEL; ?>

				</td>
				<td>
					<select name="mask_id" id="mask_id">
<?php
	// $masks_result was provided by admin.php
	while ($masks_row = $qls->SQL->fetch_array($masks_result)) {
?>
						<option value="<?php echo $masks_row['id']; ?>"><?php echo stripslashes($masks_row['name']); ?></option>
<?php
	}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('edit_mask', new Array('type','mask_id'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>