<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_ACTIVATE_USER_LABEL; ?>

	</legend>
	<table border="0" cellspacing="3" cellpadding="2">
		<tr>
			<th style="border-right:1px solid #000;text-align:left;"><?php echo USERNAME_LABEL; ?></th>
			<th style="border-left:1px solid #000;text-align:left;"><?php echo ACTIVATION_LINK_LABEL; ?></th>
		</tr>
<?php
	while ($row = $qls->SQL->fetch_array($result)) {
?>
		<tr>
			<td><?php echo stripslashes($row['username']); ?></td>
			<td>
				<a href="#" onclick="javascript:activate_user('<?php echo $row['id']; ?>');" class="activate">
					<?php echo ACTIVATION_LINK_LABEL; ?>
				</a>
			</td>
		</tr>
<?php
	}
?>
	</table>
</fieldset>