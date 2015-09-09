<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo LOGIN_LABEL; ?>
	</legend>
	<form action="auth/login_process.php" method="post">
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
					<?php echo PASSWORD_LABEL; ?>

				</td>
				<td>
					<input type="password" name="password" maxlength="<?php echo $qls->config['max_password']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo REMEMBER_ME_LABEL; ?>

				</td>
				<td>
					<input type="checkbox" name="remember" value="1" />
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<input type="submit" value="<?php echo LOGIN_SUBMIT_LABEL; ?>" />
				</td>
			</tr>
		</table>

<?php
if (isset($_GET['f'])) {
?>
        <br />
        <span style="color:#ff524a;">
<?php
    switch ($_GET['f']) {
        default:
            break;
        case 0:
            echo LOGIN_NOT_ACTIVE_USER;
            break;
        case 1:
            echo LOGIN_USER_BLOCKED;
            break;
        case 2:
            echo LOGIN_PASSWORDS_NOT_MATCHED;
            break;
        case 3:
            echo LOGIN_NO_TRIES;
            break;
        case 4:
            echo LOGIN_USER_INFO_MISSING;
            break;
        case 5:
            echo LOGIN_NOT_ACTIVE_ADMIN;
            break;
    }
?>
        </span>
<?php
}
?>
	</form>
</fieldset>