<?php
/* DO NOT REMOVE */
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}
/*****************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

	<head>
		<title><?php echo ADMIN_PANEL_LABEL; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta name="author" content="Douglas Rennehan" />
		<link rel="stylesheet" type="text/css" href="html/tabbed_pane.css" />
		<script language="JavaScript" type="text/javascript">
		<!--;
			/**
			 * @var object sub_menu - It will contain the DIV element
			 */
			var sub_menu;

			/**
			 * @var object ajax - Contains the AJAX information
			 */
			var ajax;

			/**
			 * @var string current_selection - Contains the current tab
			 */
			var current_selection = "tab1";

			/**
			 * @var array links - Contains all the sub menu links
			 */
			var links = new Array();

			/**
			 * These all contain the sub menus for the tabs. You
			 * can change it around if necessary but it should be
			 * left the way it is :)
			 */
			links["tab1"] = new Array();
			links["tab1"][0] = '<a href="#" onclick="javascript:load_page(\'main\');" class="sub_menu_link"><?php echo MAIN_LABEL; ?></a>';
			links["tab1"][1] = '<a href="#" onclick="javascript:load_page(\'phpinfo\');" class="sub_menu_link"><?php echo PHPINFO_LABEL; ?></a>';
			links["tab1"][2] = '<a href="#" onclick="javascript:load_page(\'updates\');" class="sub_menu_link"><?php echo UPDATES_LABEL; ?></a>';
			links["tab1"][3] = '<a href="#" onclick="javascript:load_page(\'configuration\');" class="sub_menu_link"><?php echo CONFIGURATION_LABEL; ?></a>';

			links["tab2"] = new Array();
			links["tab2"][0] = '<a href="#" onclick="javascript:load_page(\'add_user\');" class="sub_menu_link"><?php echo ADD_LABEL; ?></a>';
			links["tab2"][1] = '<a href="#" onclick="javascript:load_page(\'user_list\');" class="sub_menu_link"><?php echo LIST_LABEL; ?></a>';
			links["tab2"][2] = '<a href="#" onclick="javascript:load_page(\'remove_user\');" class="sub_menu_link"><?php echo REMOVE_LABEL; ?></a>';
			links["tab2"][3] = '<a href="#" onclick="javascript:load_page(\'edit_user\');" class="sub_menu_link"><?php echo EDIT_LABEL; ?></a>';
			links["tab2"][4] = '<a href="#" onclick="javascript:load_page(\'list_activations\');" class="sub_menu_link"><?php echo PENDING_ACCOUNTS_LABEL; ?></a>';

			links["tab3"] = new Array();
			links["tab3"][0] = '<a href="#" onclick="javascript:load_page(\'add_group\');" class="sub_menu_link"><?php echo ADD_LABEL; ?></a>';
			links["tab3"][1] = '<a href="#" onclick="javascript:load_page(\'list_groups\');" class="sub_menu_link"><?php echo LIST_LABEL; ?></a>';
			links["tab3"][2] = '<a href="#" onclick="javascript:load_page(\'remove_group\');" class="sub_menu_link"><?php echo REMOVE_LABEL; ?></a>';
			links["tab3"][3] = '<a href="#" onclick="javascript:load_page(\'edit_group\');" class="sub_menu_link"><?php echo EDIT_LABEL; ?></a>';

			links["tab4"] = new Array();
			links["tab4"][0] = '<a href="#" onclick="javascript:load_page(\'add_page\');" class="sub_menu_link"><?php echo ADD_LABEL; ?></a>';
			links["tab4"][1] = '<a href="#" onclick="javascript:load_page(\'page_list\');" class="sub_menu_link"><?php echo LIST_LABEL; ?></a>';
			links["tab4"][2] = '<a href="#" onclick="javascript:load_page(\'remove_page\');" class="sub_menu_link"><?php echo REMOVE_LABEL; ?></a>';
			links["tab4"][3] = '<a href="#" onclick="javascript:load_page(\'edit_page\');" class="sub_menu_link"><?php echo EDIT_LABEL; ?></a>';

			links["tab5"] = new Array();
			links["tab5"][0] = '<a href="#" onclick="javascript:load_page(\'add_mask\');" class="sub_menu_link"><?php echo ADD_MASK_LABEL; ?></a>';
			links["tab5"][1] = '<a href="#" onclick="javascript:load_page(\'list_masks\');" class="sub_menu_link"><?php echo LIST_MASKS_LABEL; ?></a>';
			links["tab5"][2] = '<a href="#" onclick="javascript:load_page(\'remove_mask\');" class="sub_menu_link"><?php echo REMOVE_MASK_LABEL; ?></a>';
			links["tab5"][3] = '<a href="#" onclick="javascript:load_page(\'edit_mask\');" class="sub_menu_link"><?php echo EDIT_MASK_LABEL; ?></a>';

			links["tab6"] = new Array();
			links["tab6"][0] = '<a href="#" onclick="javascript:load_page(\'send_invite\');" class="sub_menu_link"><?php echo SEND_INVITE_LABEL; ?></a>';

			/**
			 * Opens a URL and reads the information
			 * @param string url - The URL to open
			 * @return void
			 */
			function run_ajax(url) {
			var ajax;
				try {
				ajax = new XMLHttpRequest();
				}
				catch (e) {
					try {
					ajax = new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch (e) {
						try {
						ajax = new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch (e) {
						alert("<?php echo BROWSER_NO_AJAX; ?>");
						}
					}
				}

			ajax.onreadystatechange = function() {
					if (ajax.readyState == 1) {
					document.getElementById("page_content").innerHTML = "<?php echo LOADING_LABEL; ?>...";
					}

					if (ajax.readyState == 4) {
					document.getElementById("page_content").innerHTML = ajax.responseText;
					}
				}

			ajax.open("GET", url, true);
			ajax.send(null);
			}

			/**
			 * Acts like submitting a form
			 * @param string page   - The area in the admin panel to open
			 * @param array  params - The params that need to be processed
			 * @return void
			 */
			function run_form(page, params) {
			var query_string = "";
			var current_param;
				for (var x in params) {
				current_param = params[x];
				query_string += "&" + current_param + "=" + encodeURIComponent(document.getElementById(current_param).value);
				}

			var url = "admincp.php?do=" + page + "&type=process" + query_string;
			run_ajax(url);
			}

			/**
			 * Loads a page in the admin panel
			 * @param string page - The area to open
			 * @return void
			 */
			function load_page(page) {
			var url = "admincp.php?do=" + page;
			run_ajax(url);
			}

			/**
			 * This is for the pagination system so it can open pages
			 * @param string page - The page number
			 * @return void
			 */
			function load_user_page(page) {
			var url = "admincp.php?do=user_list&page=" + page;
			run_ajax(url);
			}

			/**
			 * For the user list (editing and removing)
			 * @param string  page    - The area to open
			 * @param integer user_id - The user's ID
			 * @return void
			 */
			function user_change(page, user_id) {
			var url = "admincp.php?do=" + page + "&type=process&user_id=" + user_id;
			run_ajax(url);
			}

			/**
			 * For the page list (editing and removing)
			 * @param string  page    - The area to open
			 * @param integer page_id - The page ID
			 * @return void
			 */
			function page_change(page, page_id) {
			var url = "admincp.php?do=" + page + "&type=process&page_id=" + page_id;
			run_ajax(url);
			}

			/**
			 * For the mask list (editing and removing)
			 * @param string  page    - The area to open
			 * @param integer mask_id - The mask ID
			 * @return void
			 */
			function mask_change(page, mask_id) {
			var url = "admincp.php?do=" + page + "&type=process&mask_id=" + mask_id;
			run_ajax(url);
			}

			/**
			 * For the group list (editing and removing)
			 * @param string  page     - The area to open
			 * @param integer group_id - The group ID
			 * @return void
			 */
			function group_change(page, group_id) {
			var url = "admincp.php?do=" + page + "&type=process&group_id=" + group_id;
			run_ajax(url);
			}

			/**
			 * Runs the activation form
			 * @param integer user_id - The user ID to activate
			 * @return void
			 */
			function activate_user(user_id) {
			load_page("list_activations&type=process&user_id=" + user_id);
			}

			/**
			 * The event when a tab is clicked (opens the sub menu)
			 * @param integer tab_id - The tab ID
			 * @return void
			 */
			function click_tab(tab_id) {
			document.getElementById(current_selection).className = "tab_unselected";
			document.getElementById(tab_id).className = "tab_selected";
			current_selection = tab_id;
			sub_menu = document.getElementById("sub_menu");
			sub_menu.innerHTML = "";
				for (var x in links[tab_id]) {
				sub_menu.innerHTML += links[tab_id][x] + "&nbsp;&nbsp;";
				}
			}
			
			setTimeout("click_tab('tab1')", 1000);
		//-->
		</script>
	</head>

	<body>
		<table border="0" cellspacing="0" cellpadding="0" id="tabs">
			<tr>
				<td class="hidden">
					&nbsp;
				</td>
				<td id="tab1" class="tab_selected" onclick="javascript:click_tab('tab1');">
					<?php echo MAIN_LABEL; ?>
				</td>
				<td class="hidden">
					&nbsp;
				</td>
				<td id="tab2" class="tab_unselected" onclick="javascript:click_tab('tab2');">
					<?php echo USERS_LABEL; ?>
				</td>
				<td class="hidden">
					&nbsp;
				</td>
				<td id="tab3" class="tab_unselected" onclick="javascript:click_tab('tab3');">
					<?php echo GROUPS_LABEL; ?>
				</td>
				<td class="hidden">
					&nbsp;
				</td>
				<td id="tab4" class="tab_unselected" onclick="javascript:click_tab('tab4');">
					<?php echo PAGES_LABEL; ?>
				</td>
				<td class="hidden">
					&nbsp;
				</td>
				<td id="tab5" class="tab_unselected" onclick="javascript:click_tab('tab5');">
					<?php echo PERMISSIONS_LABEL; ?>
				</td>
				<td class="hidden">
					&nbsp;
				</td>
				<td id="tab6" class="tab_unselected" onclick="javascript:click_tab('tab6');">
					<?php echo INVITATIONS_LABEL; ?>
				</td>
				<td class="hidden">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="13" id="sub_menu">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="13">
					<hr noshade="noshade" width="100%" />
				</td>
			</tr>
			<tr>
				<td colspan="13" id="page_content">
					<?php echo ADMIN_PANEL_WELCOME; ?>
				</td>
			</tr>
		</table>
		<br />
		<div align="left">
			<?php printf(SYSTEM_INFORMATION, $qls->config['current_version'], $qls->SQL->database_type, $qls->SQL->get_client_info()); ?>
		</div>
	</body>

</html>