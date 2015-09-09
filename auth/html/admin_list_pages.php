<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<fieldset>
	<legend>
		<?php echo ADMIN_PAGE_LIST_LABEL; ?>

	</legend>
	<table border="0" cellspacing="3" cellpadding="2">
		<tr>
			<th style="border-right:1px solid #000;text-align:left;"><?php echo PAGE_NAME_LABEL; ?></th>
			<th style="border-left:1px solid #000;border-right: 1px solid #000;text-align:left;"><?php echo EDIT_LABEL; ?></th>
			<th style="border-left:1px solid #000;text-align:left"><?php echo REMOVE_LABEL; ?></th>
		</tr>
<?php
	// $pages_result is provided by admin.php
	while ($pages_row = $qls->SQL->fetch_array($pages_result)) {
?>
		<tr>
			<td align="left"><?php echo stripslashes($pages_row['name']); ?></td>
			<td align="left">
				<a href="#" onclick="javascript:page_change('edit_page', '<?php echo $pages_row['id']; ?>');" class="main"><?php echo EDIT_LABEL; ?></a>
			</td>
			<td align="left">
				<a href="#" onclick="javascript:page_change('remove_page','<?php echo $pages_row['id']; ?>');" class="main"><?php echo REMOVE_LABEL; ?></a>
			</td>
		</tr>
<?php
	}
?>
	</table>
</fieldset>