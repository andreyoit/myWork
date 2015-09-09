<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_SEND_INVITE_LABEL; ?>

	</legend>
	<div align="left">
		<?php echo ADMIN_SEND_INVITE_EXPLAIN; ?>
	</div>
	<br />
	<form action="#" method="get">
		<table border="0">
			<tr>
				<td>
					<?php echo TO_LABEL . '(' . EMAIL_LABEL . ')'; ?>

				</td>
				<td>
					<input type="text" id="to" name="to" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo REPLY_TO_LABEL . '(' . EMAIL_LABEL . ')'; ?>

				</td>
				<td>
					<input type="text" id="reply_to" name="reply_to" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo SUBJECT_LABEL; ?>

				</td>
				<td>
					<input type="text" id="subject" name="subject" maxlength="255" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo LINK_TO_REGISTER_PAGE_LABEL; ?>

				</td>
				<td>
					<input type="text" id="link" name="link" value="<?php echo $register_url; ?>" />
				</td>
			</tr>
			<tr>
				<td valign="top">
					<?php echo MESSAGE_LABEL; ?>

				</td>
				<td>
					<textarea id="message" name="message" style="width:400px;height:250px;"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('send_invite', new Array('to','reply_to','subject','link','message'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>