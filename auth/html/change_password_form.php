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
	<form action="change_password.php?code=<?php echo htmlentities($_GET['code']); ?>" method="post">
		<input type="hidden" name="process" value="true" />
		<table>
			<tr>
				<td>
					<?php echo PASSWORD_LABEL; ?>

				</td>
				<td>
					<input type="password" name="new_password" maxlength="<?php echo $qls->config['max_password']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo PASSWORD_CONFIRM_LABEL; ?>

				</td>
				<td>
					<input type="password" name="new_password_confirm" maxlength="<?php echo $qls->config['max_password']; ?>" />
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