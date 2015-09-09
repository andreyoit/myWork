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
		<title><?php echo GROUP_PANEL_LABEL; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta name="author" content="Douglas Rennehan" />
		<link rel="stylesheet" type="text/css" href="html/tabbed_pane.css" />
		<script language="JavaScript" type="text/javascript">
		<!--;
			/*** *** *** *** *** ***
			* @package Quadodo Login Script
			* @file    group_splash.php
			* @start   October 27th, 2007
			* @author  Douglas Rennehan
			* @license http://www.opensource.org/licenses/gpl-license.php
			* @version 1.0.0
			* @link    http://webhelp.pcriot.com
			*** *** *** *** *** ***
			* This program is free software; you can redistribute it and/or modify
			* it under the terms of the GNU General Public License as published by
			* the Free Software Foundation; either version 2 of the License, or
			* (at your option) any later version.
			*
			* This program is distributed in the hope that it will be useful,
			* but WITHOUT ANY WARRANTY; without even the implied warranty of
			* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
			* GNU General Public License for more details.
			*
			* You should have received a copy of the GNU General Public License
			* along with this program; if not, write to the Free Software
			* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
			*** *** *** *** *** ***
			* Comments are always before the code they are commenting.
			*** *** *** *** *** ***/

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
			links["tab1"][1] = '<a href="groupcp.php?" class="sub_menu_link"><?php echo GROUP_SPLASH_PAGE_BACK; ?></a>';

			links["tab2"] = new Array();
			links["tab2"][0] = '<a href="#" onclick="javascript:load_page(\'add_user\');" class="sub_menu_link"><?php echo ADD_LABEL; ?></a>';
			links["tab2"][1] = '<a href="#" onclick="javascript:load_page(\'remove_user\');" class="sub_menu_link"><?php echo REMOVE_LABEL; ?></a>';
			links["tab2"][2] = '<a href="#" onclick="javascript:load_page(\'user_list&area=group\');" class="sub_menu_link"><?php echo MEMBER_LIST_LABEL; ?></a>';
			links["tab2"][3] = '<a href="#" onclick="javascript:load_page(\'user_list&area=all\');" class="sub_menu_link"><?php echo USER_LIST_LABEL; ?></a>';

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
			 * @param string page   - The area in the group panel to open
			 * @param array  params - The params that need to be processed
			 * @return void
			 */
			function run_form(page, params) {
			var query_string = "";
			var current_param;
				for (var x in params) {
				current_param = params[x];
				query_string += "&" + current_param + "=" + encodeURI(document.getElementById(current_param).value);
				}

			var url = "groupcp.php?do=" + page + "&id=<?php echo $group_info['id']; ?>&type=process" + query_string;
			run_ajax(url);
			}

			/**
			 * Loads a page in the group panel
			 * @param string  page     - The area to open
			 * @return void
			 */
			function load_page(page) {
			var url = "groupcp.php?do=" + page + "&id=<?php echo $group_info['id']; ?>";
			run_ajax(url);
			}

			/**
			 * This is for the pagination system so it can open pages
			 * @param string page - The page number
			 * @param string area - The area (group or all)
			 * @return void
			 */
			function load_user_page(page, area) {
			var url = "groupcp.php?do=user_list&id=<?php echo $group_info['id']; ?>&area=" + area + "&page=" + page;
			run_ajax(url);
			}

			/**
			 * For the user list (editing and removing)
			 * @param string  page     - The area to open
			 * @param integer user_id  - The user's ID
			 * @return void
			 */
			function user_change(page, user_id) {
			var url = "groupcp.php?do=" + page + "&id=<?php echo $group_info['id']; ?>&type=process&user_id=" + user_id;
			run_ajax(url);
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

			<?php if (is_numeric($_GET['id'])) { ?>
			setTimeout("click_tab('tab1')", 1000);
			<?php } ?>
		//-->
		</script>
	</head>

	<body>
		<table border="0" cellspacing="0" cellpadding="0" id="tabs">
			<tr>
				<td class="hidden" width="6%">
					&nbsp;
				</td>
				<td id="tab1" class="tab_selected" onclick="javascript:click_tab('tab1');" width="40%">
					<?php echo MAIN_LABEL; ?>
				</td>
				<td class="hidden" width="6%">
					&nbsp;
				</td>
				<td id="tab2" class="tab_unselected"<?php if (is_numeric($_GET['id'])) { ?> onclick="javascript:click_tab('tab2');"<?php } ?> width="40%">
					<?php if (is_numeric($_GET['id'])) { echo USERS_LABEL; } else { ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
				</td>
				<td class="hidden" width="7%">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="5" id="sub_menu">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<hr noshade="noshade" width="100%" />
				</td>
			</tr>
			<tr>
				<td colspan="5" id="page_content">
<?php
	if ($list === true) {
		if (isset($message)) {
		echo $message . '<br /><br />';
		}
?>
					<?php printf(YOUR_CURRENT_GROUP_LABEL, stripslashes($qls->group_id_to_name($qls->user_info['group_id']))); ?>
					<br />
					<br />
					<br />
					<form action="#" method="get">
						<input type="hidden" name="join" value="1" />
						<table border="0">
							<tr>
								<td><?php echo GROUP_LABEL; ?></td>
								<td>
									<select name="group_id">
<?php
		while ($row = $qls->SQL->fetch_array($result)) {
?>
										<option value="<?php echo $row['id']; ?>"><?php echo stripslashes($row['name']); ?></option>
<?php
		}
?>
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<input type="submit" value="<?php echo JOIN_THIS_GROUP_LABEL; ?>" />
								</td>
							</tr>
						</table>
					</form>
<?php
	}
	else {
		if (!isset($_GET['id'])) {
		echo GROUP_PANEL_WELCOME . BR_BR;
?>
					<form action="#" method="get">
						<table border="0">
							<tr>
								<td><?php echo GROUP_LABEL; ?></td>
								<td>
									<select name="id">
<?php
			while ($row = $qls->SQL->fetch_array($result)) {
?>
							<option value="<?php echo $row['id']; ?>"><?php echo stripslashes($row['name']); ?></option>
<?php
			}
?>
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<input type="submit" value="<?php echo GO_LABEL; ?>" />
								</td>
							</tr>
						</table>
					</form>
<?php
		}
		else {
		printf(GROUP_PANEL_INFORMATION, $num_rows, $num_rows2, $percent);
		}
	}
?>
				</td>
			</tr>
		</table>
		<br />
		<div align="left">
			<?php printf(POWERED_BY, $qls->config['current_version']); ?>
		</div>
	</body>

</html>