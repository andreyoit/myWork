<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_GROUP_LIST_LABEL; ?>

	</legend>
	<table border="0" cellspacing="3" cellpadding="2">
		<tr>
			<th style="border-right: 1px solid #000;text-align:left;"><?php echo GROUP_NAME_LABEL; ?></th>
			<th style="border-left: 1px solid #000;border-right: 1px solid #000;text-align:left;"><?php echo LEADER_LABEL; ?></th>
			<th style="border-left: 1px solid #000;border-right: 1px solid #000;text-align:left;"><?php echo EDIT_LABEL; ?></th>
			<th style="border-left: 1px solid #000;text-align:left;"><?php echo REMOVE_LABEL; ?></th>
		</tr>
<?php
	// $groups_result is provided by admin.php
	while ($groups_row = $qls->SQL->fetch_array($groups_result)) {
?>
		<tr>
			<td align="left"><?php echo stripslashes($groups_row['name']); ?></td>
			<td align="left">
				<a href="#" onclick="javascript:user_change('edit_user', '<?php echo $groups_row['leader']; ?>');" class="main"><?php echo stripslashes($qls->id_to_username($groups_row['leader'])); ?></a>
			</td>
			<td align="left">
				<a href="#" onclick="javascript:group_change('edit_group', '<?php echo $groups_row['id']; ?>');" class="main"><?php echo EDIT_LABEL; ?></a>
			</td>
			<td align="left">
				<a href="#" onclick="javascript:group_change('remove_group','<?php echo $groups_row['id']; ?>');" class="main"><?php echo REMOVE_LABEL; ?></a>
			</td>
		</tr>
<?php
	}
?>
	</table>
</fieldset>