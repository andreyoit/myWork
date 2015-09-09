<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_EDIT_CONFIGURATION_LABEL; ?>

	</legend>
	<form action="#" method="get">
		<table border="0">
			<tr>
				<td>
					<?php echo COOKIE_PREFIX_LABEL; ?>

				</td>
				<td>
					<input type="text" id="cookie_prefix" name="cookie_prefix" maxlength="255" value="<?php echo $qls->config['cookie_prefix']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo MAX_USERNAME_LABEL; ?>

				</td>
				<td>
					<input type="text" id="max_username" name="max_username" maxlength="2" value="<?php echo $qls->config['max_username']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo MIN_USERNAME_LABEL; ?>

				</td>
				<td>
					<input type="text" id="min_username" name="min_username" maxlength="3" value="<?php echo $qls->config['min_username']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo MAX_PASSWORD_LABEL; ?>

				</td>
				<td>
					<input type="text" id="max_password" name="max_password" maxlength="3" value="<?php echo $qls->config['max_password']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo MIN_PASSWORD_LABEL; ?>

				</td>
				<td>
					<input type="text" id="min_password" name="min_password" maxlength="3" value="<?php echo $qls->config['min_password']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo COOKIE_PATH_LABEL; ?>

				</td>
				<td>
					<input type="text" id="cookie_path" name="cookie_path" maxlength="255" value="<?php echo $qls->config['cookie_path']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo COOKIE_SECURE_LABEL; ?>

				</td>
				<td>
					<select name="cookie_secure" id="cookie_secure">
						<option value="0"<?php if ($qls->config['cookie_secure'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
						<option value="1"<?php if ($qls->config['cookie_secure'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo COOKIE_LENGTH_LABEL; ?>

				</td>
				<td>
					<input type="text" id="cookie_length" name="cookie_length" maxlength="7" value="<?php echo $qls->config['cookie_length']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo COOKIE_DOMAIN_LABEL; ?>

				</td>
				<td>
					<input type="text" id="cookie_domain" name="cookie_domain" maxlength="255" value="<?php echo $qls->config['cookie_domain']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo MAX_TRIES_LABEL; ?>

				</td>
				<td>
					<input type="text" id="max_tries" name="max_tries" maxlength="2" value="<?php echo $qls->config['max_tries']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo USER_REGEX_LABEL; ?>

				</td>
				<td>
					<input type="text" id="user_regex" name="user_regex" maxlength="255" value="<?php echo $qls->config['user_regex']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo SECURITY_IMAGE_LABEL; ?>

				</td>
				<td>
					<select name="security_image" id="security_image">
						<option value="yes"<?php if ($qls->config['security_image'] == 'yes') { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
						<option value="no"<?php if ($qls->config['security_image'] == 'no') { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo ACTIVATION_TYPE_LABEL; ?>

				</td>
				<td>
					<select name="activation_type" id="activation_type">
						<option value="0"<?php if ($qls->config['activation_type'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_VALIDATION_LABEL; ?></option>
						<option value="1"<?php if ($qls->config['activation_type'] == 1) { ?> selected="selected"<?php } ?>><?php echo USER_VALIDATION_LABEL; ?></option>
						<option value="2"<?php if ($qls->config['activation_type'] == 2) { ?> selected="selected"<?php } ?>><?php echo ADMIN_VALIDATION_LABEL; ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo LOGIN_REDIRECT_URL_LABEL; ?>

				</td>
				<td>
					<input type="text" id="login_redirect" name="login_redirect" maxlength="255" value="<?php echo $qls->config['login_redirect']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo LOGOUT_REDIRECT_URL_LABEL; ?>

				</td>
				<td>
					<input type="text" id="logout_redirect" name="logout_redirect" maxlength="255" value="<?php echo $qls->config['logout_redirect']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo REDIRECT_TYPE_LABEL; ?>

				</td>
				<td>
					<select name="redirect_type" id="redirect_type">
						<option value="1"<?php if ($qls->config['redirect_type'] == 1) { ?> selected="selected"<?php } ?>>PHP (<?php echo RECOMMENDED_LABEL; ?>)</option>
						<option value="2"<?php if ($qls->config['redirect_type'] == 2) { ?> selected="selected"<?php } ?>>HTML meta</option>
						<option value="3"<?php if ($qls->config['redirect_type'] == 3) { ?> selected="selected"<?php } ?>>JavaScript</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo ONLINE_USERS_FORMAT_LABEL; ?>

				</td>
				<td>
					<input type="text" id="online_users_format" name="online_users_format" maxlength="255" value="<?php echo $qls->config['online_users_format']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo ONLINE_USERS_SEPARATOR_LABEL; ?>

				</td>
				<td>
					<input type="text" id="online_users_separator" name="online_users_separator" maxlength="255" value="<?php echo $qls->config['online_users_separator']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo MAX_UPLOAD_SIZE_LABEL; ?>

				</td>
				<td>
					<input type="text" id="max_upload_size" name="max_upload_size" maxlength="11" value="<?php echo $qls->config['max_upload_size']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo AUTH_REGISTRATION_LABEL; ?>

				</td>
				<td>
					<select name="auth_registration" id="auth_registration">
						<option value="1"<?php if ($qls->config['auth_registration'] == 1) { ?> selected="selected"<?php } ?>><?php echo YES_LABEL; ?></option>
						<option value="0"<?php if ($qls->config['auth_registration'] == 0) { ?> selected="selected"<?php } ?>><?php echo NO_LABEL; ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('configuration', new Array('cookie_prefix','max_username','min_username','max_password','min_password','cookie_path','cookie_secure','cookie_length','cookie_domain','max_tries','user_regex','security_image','activation_type','login_redirect','logout_redirect','redirect_type','online_users_format','online_users_separator','max_upload_size','auth_registration'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>