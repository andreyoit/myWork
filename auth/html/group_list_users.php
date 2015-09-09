<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo GROUP_USER_LIST_LABEL; ?>

	</legend>
<?php
$users_count = $qls->SQL->num_rows($users_result);
	if ($users_count == 0) {
	echo GROUP_USER_LIST_NO_USERS;
	}
	else {
?>
	<table border="0" cellspacing="3" cellpadding="2">
		<tr>
			<th style="border-right: 1px solid #000;text-align:left;"><?php echo USERNAME_LABEL; ?></th>

			<th style="border-left: 1px solid #000;text-align:left;"><?php echo ADD_LABEL; ?></th>
		</tr>
<?php
		// $users_result is provided by groupcp.php
		while ($users_row = $qls->SQL->fetch_array($users_result)) {
?>
		<tr>
			<td align="left"><?php echo stripslashes($users_row['username']); ?></td>
			<td align="left">
				<a href="#" onclick="javascript:user_change('add_user','<?php echo $users_row['id']; ?>');" class="main"><?php echo ADD_LABEL; ?></a>
			</td>
		</tr>
<?php
		}
?>
	</table>
	<br />
	<?php $qls->Group->pagination(); ?>
<?php
	}
?>
</fieldset>