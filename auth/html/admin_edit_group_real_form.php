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
		<input type="hidden" id="type2" name="type2" value="process" />
		<input type="hidden" id="group_id" name="group_id" value="<?php echo $group_id; ?>" />
		<table border="0">
			<tr>
				<td>
					<?php echo GROUP_NAME_LABEL; ?>

				</td>
				<td>
					<input type="text" id="new_name" name="new_name" maxlength="255" value="<?php echo stripslashes($row['name']); ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo PERMISSION_MASK_LABEL; ?>

				</td>
				<td>
					<select name="new_mask" id="new_mask">
<?php
	// $masks_result is provided by admin.php
	while ($masks_row = $qls->SQL->fetch_array($masks_result)) {
		if ($row['mask_id'] == $masks_row['id']) {
?>
						<option value="<?php echo $masks_row['id']; ?>" selected="selected"><?php echo stripslashes($masks_row['name']); ?></option>
<?php
		}
		else {
?>
						<option value="<?php echo $masks_row['id']; ?>"><?php echo stripslashes($masks_row['name']); ?></option>
<?php
		}
	}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo GROUP_LEADER_LABEL; ?>

				</td>
				<td>
					<input type="text" id="new_leader" name="new_leader" maxlength="<?php echo $qls->config['max_username']; ?>" value="<?php echo stripslashes($qls->id_to_username($row['leader'])); ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo IS_PUBLIC_GROUP_LABEL; ?>

				</td>
				<td>
					<select name="new_is_public" id="new_is_public">
						<option value="1"<?php if ($row['is_public'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
						<option value="0"<?php if ($row['is_public'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo DAYS_UNTIL_ACCOUNTS_EXPIRE_LABEL; ?><br />
					<?php echo ACCOUNT_EXPIRE_EXPLAIN_LABEL; ?>

				</td>
				<td>
					<input type="text" id="new_expiration_date" name="new_expiration_date" maxlength="3" value="<?php echo $row['expiration_date']; ?>" size="5" /> <?php echo DAYS_LABEL; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('edit_group', new Array('type2','group_id','new_name','new_mask','new_leader','new_is_public','new_expiration_date'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>