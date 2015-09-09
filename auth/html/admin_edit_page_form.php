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
		<input type="hidden" id="type" name="type" value="process" />
		<table border="0">
			<tr>
				<td>
					<?php echo PAGE_NAME_LABEL; ?>

				</td>
				<td>
					<select name="page_id" id="page_id">
<?php
	// $pages_result was provided by admin.php
	while ($pages_row = $qls->SQL->fetch_array($pages_result)) {
?>
						<option value="<?php echo $pages_row['id']; ?>"><?php echo stripslashes($pages_row['name']); ?></option>
<?php
	}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" onclick="javascript:run_form('edit_page', new Array('type','page_id'));" value="<?php echo GO_LABEL; ?>" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>