<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo REGISTER_LABEL; ?>
	</legend>
	<form action="register.php<?php if (isset($_GET['code'])) { ?>?code=<?php echo htmlentities($_GET['code']); } ?>" method="post">
		<input type="hidden" name="process" value="true" />
		<input type="hidden" name="random_id" value="<?php echo $random_id; ?>" />
		<table>
			<tr>
				<td>
					<?php echo USERNAME_LABEL; ?>

				</td>
				<td>
					<input type="text" name="username" maxlength="<?php echo $qls->config['max_username']; ?>" value="<?php if (isset($_SESSION[$qls->config['cookie_prefix'] . 'registration_username'])) { echo $_SESSION[$qls->config['cookie_prefix'] . 'registration_username']; } ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo PASSWORD_LABEL; ?>

				</td>
				<td>
					<input type="password" name="password" maxlength="<?php echo $qls->config['max_password']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo PASSWORD_CONFIRM_LABEL; ?>

				</td>
				<td>
					<input type="password" name="password_c" maxlength="<?php echo $qls->config['max_password']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo EMAIL_LABEL; ?>

				</td>
				<td>
					<input type="text" name="email" maxlength="100" value="<?php if (isset($_SESSION[$qls->config['cookie_prefix'] . 'registration_email'])) { echo $_SESSION[$qls->config['cookie_prefix'] . 'registration_email']; } ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo EMAIL_CONFIRM_LABEL; ?>

				</td>
				<td>
					<input type="text" name="email_c" maxlength="100" value="<?php if (isset($_SESSION[$qls->config['cookie_prefix'] . 'registration_email_confirm'])) { echo $_SESSION[$qls->config['cookie_prefix'] . 'registration_email_confirm']; } ?>" />
				</td>
			</tr>
<?php
/* START SECURITY IMAGE */
if ($qls->config['security_image'] == 'yes') {
?>
			<tr>
				<td colspan="2" align="center">
					<img src="security_image.php?id=<?php echo $random_id; ?>" border="0" alt="Security Image" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo SECURITY_CODE_LABEL; ?>
				</td>
				<td>
					<input type="text" name="security_code" maxlength="8" />
				</td>
			</tr>
<?php
}
/* END SECURITY IMAGE */
?>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<input type="submit" value="<?php echo REGISTER_SUBMIT_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>