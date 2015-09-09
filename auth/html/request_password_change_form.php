<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo CHANGE_PASSWORD_LABEL; ?>
	</legend>
	<form action="change_password.php" method="post">
		<input type="hidden" name="process" value="true" />
		<table>
			<tr>
				<td>
					<?php echo USERNAME_LABEL; ?>

				</td>
				<td>
					<input type="text" name="username" maxlength="<?php echo $qls->config['max_username']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<input type="submit" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>