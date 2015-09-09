<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_ADD_PAGE_LABEL; ?>

	</legend>
	<h3><?php echo LOCAL_FILE_LABEL; ?></h3>
	<br />
	<form enctype="multipart/form-data" action="admincp.php?do=add_page&amp;type=process&amp;type2=process" method="post">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $qls->config['max_upload_size']; ?>" />
		<table border="0">
			<tr>
				<td>
					<input type="file" name="upload" />
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
	<br />
	<h3><?php echo CREATE_NEW_HERE_LABEL; ?></h3>
	<br />
	<form action="admincp.php?do=add_page&amp;type=process" method="post">
		<table border="0">
			<tr>
				<td>
					<?php echo NAME_LABEL; ?>
				</td>
				<td>
					<input type="text" name="name" maxlength="255" />
				</td>
			</tr>
			<tr>
				<td valign="top">
					<?php echo PAGE_DATA_LABEL; ?>
				</td>
				<td>
					<textarea name="data" style="width:400px;height:250px;"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>