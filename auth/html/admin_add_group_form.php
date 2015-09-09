<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_ADD_GROUP_LABEL; ?>

	</legend>
	<form action="#" method="get">
		<table border="0">
			<tr>
				<td>
					<?php echo GROUP_NAME_LABEL; ?>

				</td>
				<td>
					<input type="text" id="name" name="name" maxlength="255" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo PERMISSION_MASK_LABEL; ?>

				</td>
				<td>
					<select name="mask_id" id="mask_id">
<?php
	// $masks_result is provided by admin.php
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
				<td>
					<?php echo GROUP_LEADER_LABEL; ?>

				</td>
				<td>
					<input type="text" id="leader" name="leader" maxlength="<?php echo $qls->config['max_username']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo IS_PUBLIC_GROUP_LABEL; ?>

				</td>
				<td>
					<select name="is_public" id="is_public">
						<option value="1"><?php echo YES_LABEL; ?></option>
						<option value="0"><?php echo NO_LABEL; ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo DAYS_UNTIL_ACCOUNTS_EXPIRE_LABEL; ?><br />
					<?php echo ACCOUNT_EXPIRE_EXPLAIN_LABEL; ?>

				</td>
				<td>
					<input type="text" id="expiration_date" name="expiration_date" maxlength="3" value="0" size="5" /> <?php echo DAYS_LABEL; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('add_group', new Array('name','mask_id','leader','is_public','expiration_date'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>