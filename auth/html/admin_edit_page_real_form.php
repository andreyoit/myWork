<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_EDIT_PAGE_LABEL; ?>

	</legend>
	<form action="#" method="get">
		<input type="hidden" id="type2" name="type2" value="process" />
		<input type="hidden" id="page_id" name="page_id" value="<?php echo $row['id']; ?>" />
		<table border="0">
			<tr>
				<td>
					<?php echo NEW_PAGE_NAME_LABEL; ?>

				</td>
				<td>
					<input type="text" id="new_page_name" name="new_page_name" maxlength="255" value="<?php echo stripslashes($row['name']); ?>" />
				</td>
			</tr>
			<tr>
				<td valign="top">
					<?php echo NEW_PAGE_DATA_LABEL; ?>

				</td>
				<td>
					<textarea id="new_page_data" name="new_page_data" style="width:400px;height:250px;"><?php echo $file_data; ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('edit_page', new Array('type2','page_id','new_page_name','new_page_data'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>