<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_EDIT_GROUP_LABEL; ?>

	</legend>
	<form action="#" method="get">
		<input type="hidden" id="type" name="type" value="process" />
		<table border="0">
			<tr>
				<td>
					<?php echo GROUP_NAME_LABEL; ?>

				</td>
				<td>
					<select name="group_id" id="group_id">
<?php
	// $groups_result was provided by admin.php
	while ($groups_row = $qls->SQL->fetch_array($groups_result)) {
?>
						<option value="<?php echo $groups_row['id']; ?>"><?php echo stripslashes($groups_row['name']); ?></option>
<?php
	}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('edit_group', new Array('type','group_id'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>