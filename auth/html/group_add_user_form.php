<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo GROUP_ADD_USER_LABEL; ?>

	</legend>
	<form action="#" method="get">
		<table border="0">
			<tr>
				<td>
					<?php echo USERNAME_LABEL; ?>

				</td>
				<td>
					<input type="text" id="username" name="username" maxlength="<?php echo $qls->config['max_username']; ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('add_user', new Array('username'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>