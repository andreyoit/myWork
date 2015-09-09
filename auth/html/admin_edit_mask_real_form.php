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
		<input type="hidden" id="type2" name="type2" value="process" />
		<input type="hidden" id="mask_id" name="mask_id" value="<?php echo $mask_id; ?>" />
		<table border="0">
			<tr>
				<td><?php echo MASK_NAME_LABEL; ?></td>
				<td><input type="text" id="new_mask_name" name="new_mask_name" maxlength="255" value="<?php echo stripslashes($row['name']); ?>" /></td>
			</tr>
		</table>
		<br />
		<table border="0" cellpadding="2" cellspacing="2">
			<tr>
				<td valign="top" style="border-right: 1px solid #000;">
					<table border="0">
						<tr>
							<th colspan="2" align="left"><?php echo ADMIN_PANEL_LABEL; ?></th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th><?php echo ALLOW_LABEL; ?>/<?php echo DENY_LABEL; ?></th>
						</tr>
						<tr>
							<td><?php echo ACCESS_LABEL; ?></td>
							<td>
								<select name="auth_admin" id="auth_admin">
									<option value="1"<?php if ($row['auth_admin'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo VIEW_PHPINFO_LABEL; ?></td>
							<td>
								<select name="auth_admin_phpinfo" id="auth_admin_phpinfo">
									<option value="1"<?php if ($row['auth_admin_phpinfo'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_phpinfo'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo EDIT_CONFIGURATION_LABEL; ?></td>
							<td>
								<select name="auth_admin_configuration" id="auth_admin_configuration">
									<option value="1"<?php if ($row['auth_admin_configuration'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_configuration'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo ADD_USERS_LABEL; ?></td>
							<td>
								<select name="auth_admin_add_user" id="auth_admin_add_user">
									<option value="1"<?php if ($row['auth_admin_add_user'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_add_user'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo VIEW_USER_LIST_LABEL; ?></td>
							<td>
								<select name="auth_admin_user_list" id="auth_admin_user_list">
									<option value="1"<?php if ($row['auth_admin_user_list'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_user_list'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo REMOVE_USERS_LABEL; ?></td>
							<td>
								<select name="auth_admin_remove_user" id="auth_admin_remove_user">
									<option value="1"<?php if ($row['auth_admin_remove_user'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_remove_user'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo EDIT_USERS_LABEL; ?></td>
							<td>
								<select name="auth_admin_edit_user" id="auth_admin_edit_user">
									<option value="1"<?php if ($row['auth_admin_edit_user'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_edit_user'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo ADD_PAGES_LABEL; ?></td>
							<td>
								<select name="auth_admin_add_page" id="auth_admin_add_page">
									<option value="1"<?php if ($row['auth_admin_add_page'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_add_page'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo VIEW_PAGE_LIST_LABEL; ?></td>
							<td>
								<select name="auth_admin_page_list" id="auth_admin_page_list">
									<option value="1"<?php if ($row['auth_admin_page_list'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_page_list'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo REMOVE_PAGES_LABEL; ?></td>
							<td>
								<select name="auth_admin_remove_page" id="auth_admin_remove_page">
									<option value="1"<?php if ($row['auth_admin_remove_page'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_remove_page'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo EDIT_PAGES_LABEL; ?></td>
							<td>
								<select name="auth_admin_edit_page" id="auth_admin_edit_page">
									<option value="1"<?php if ($row['auth_admin_edit_page'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_edit_page'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo ADD_MASKS_LABEL; ?></td>
							<td>
								<select name="auth_admin_add_mask" id="auth_admin_add_mask">
									<option value="1"<?php if ($row['auth_admin_add_mask'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_add_mask'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo VIEW_MASK_LIST_LABEL; ?></td>
							<td>
								<select name="auth_admin_list_masks" id="auth_admin_list_masks">
									<option value="1"<?php if ($row['auth_admin_list_masks'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_list_masks'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo REMOVE_MASKS_LABEL; ?></td>
							<td>
								<select name="auth_admin_remove_mask" id="auth_admin_remove_mask">
									<option value="1"<?php if ($row['auth_admin_remove_mask'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_remove_mask'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo EDIT_MASKS_LABEL; ?></td>
							<td>
								<select name="auth_admin_edit_mask" id="auth_admin_edit_mask">
									<option value="1"<?php if ($row['auth_admin_edit_mask'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_edit_mask'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo ADD_GROUPS_LABEL; ?></td>
							<td>
								<select name="auth_admin_add_group" id="auth_admin_add_group">
									<option value="1"<?php if ($row['auth_admin_add_group'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_add_group'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo VIEW_GROUP_LIST_LABEL; ?></td>
							<td>
								<select name="auth_admin_list_groups" id="auth_admin_list_groups">
									<option value="1"<?php if ($row['auth_admin_list_groups'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_list_groups'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo REMOVE_GROUPS_LABEL; ?></td>
							<td>
								<select name="auth_admin_remove_group" id="auth_admin_remove_group">
									<option value="1"<?php if ($row['auth_admin_remove_group'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_remove_group'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo EDIT_GROUPS_LABEL; ?></td>
							<td>
								<select name="auth_admin_edit_group" id="auth_admin_edit_group">
									<option value="1"<?php if ($row['auth_admin_edit_group'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_edit_group'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo ACTIVATE_ACCOUNTS_LABEL; ?></td>
							<td>
								<select name="auth_admin_activate_account" id="auth_admin_activate_account">
									<option value="1"<?php if ($row['auth_admin_activate_account'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_activate_account'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo SEND_INVITE_LABEL; ?></td>
							<td>
								<select name="auth_admin_send_invite" id="auth_admin_send_invite">
									<option value="1"<?php if ($row['auth_admin_send_invite'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_admin_send_invite'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
					</table>
				</td>
				<td valign="top" style="border-left: 1px solid #000;">
					<table border="0">
						<tr>
							<th colspan="2" align="left"><?php echo PAGES_LABEL; ?></th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th><?php echo ALLOW_LABEL; ?>/<?php echo DENY_LABEL; ?></th>
						</tr>
<?php
	// This is to store the string for the submit button
	$params = '';

	// $pages_result was provided by admin.php
	while ($pages_row = $qls->SQL->fetch_array($pages_result)) {
	$hash = sha1($pages_row['id']);
	$params .= ",'auth_{$hash}'";
?>
						<tr>
							<td><?php echo stripslashes($pages_row['name']); ?></td>
							<td>
								<select name="auth_<?php echo $hash; ?>" id="auth_<?php echo $hash; ?>">
									<option value="1"<?php if ($row['auth_' . $hash] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
									<option value="0"<?php if ($row['auth_' . $hash] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
								</select>
							</td>
						</tr>
<?php
	}
?>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left">
					<input type="button" onclick="javascript:run_form('edit_mask', new Array('type2','mask_id','new_mask_name','auth_admin','auth_admin_phpinfo','auth_admin_configuration','auth_admin_add_user','auth_admin_user_list','auth_admin_remove_user','auth_admin_edit_user','auth_admin_add_page','auth_admin_page_list','auth_admin_remove_page','auth_admin_edit_page','auth_admin_add_mask','auth_admin_list_masks','auth_admin_remove_mask','auth_admin_edit_mask','auth_admin_add_group','auth_admin_list_groups','auth_admin_remove_group','auth_admin_edit_group','auth_admin_activate_account','auth_admin_send_invite'<?php echo $params; ?>));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>