<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_USER_LIST_LABEL; ?>

	</legend>
	<table border="0" cellspacing="3" cellpadding="2">
		<tr>
			<th style="border-right: 1px solid #000;text-align:left;"><?php echo USERNAME_LABEL; ?></th>
			<th style="border-left: 1px solid #000;border-right: 1px solid #000;text-align:left;"><?php echo GROUP_LABEL; ?></th>
			<th style="border-left: 1px solid #000;border-right: 1px solid #000;text-align:left;"><?php echo EDIT_LABEL; ?></th>
			<th style="border-left: 1px solid #000;text-align:left;"><?php echo REMOVE_LABEL; ?></th>
		</tr>
<?php
	// $users_result is provided by admin.php
	while ($users_row = $qls->SQL->fetch_array($users_result)) {
?>
		<tr>
			<td align="left"><?php echo stripslashes($users_row['username']); ?></td>
			<td align="left"><?php echo stripslashes($qls->group_id_to_name($users_row['group_id'])); ?></td>
			<td align="left">
				<a href="#" onclick="javascript:user_change('edit_user', '<?php echo $users_row['id']; ?>');" class="main"><?php echo EDIT_LABEL; ?></a>
			</td>
			<td align="left">
				<a href="#" onclick="javascript:user_change('remove_user','<?php echo $users_row['id']; ?>');" class="main"><?php echo REMOVE_LABEL; ?></a>
			</td>
		</tr>
<?php
	}
?>
	</table>
	<br />
	<?php $qls->Admin->pagination(); ?>
</fieldset>