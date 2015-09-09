<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    groupcp.php
* @start   October 23rd, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.0.0
* @link    http://www.quadodo.net
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
define('QUADODO_IN_SYSTEM', true);
require_once('includes/header.php');

// Is the user logged in?
if ($qls->user_info['username'] != '') {
// This is for the group_splash.php page
$list = false;
$group_info = $qls->Group->fetch_group_info($_GET['id']);
	if ($group_info['id'] == '') {
	// Get all the groups
	$group_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}groups`");

	// Get the groups that the user is the leader of
	$result = $qls->SQL->query("SELECT `id`,`name` FROM `{$qls->config['sql_prefix']}groups` WHERE `leader`='{$qls->user_info['id']}'");
	$num_rows = $qls->SQL->num_rows($result);
		if ($num_rows != 0) {
			// This means they have access to all the groups
			if ($qls->user_info['auth_admin_add_group'] == 1 && $qls->user_info['auth_admin_list_groups'] == 1 && $qls->user_info['auth_admin_remove_group'] == 1 && $qls->user_info['auth_admin_edit_group'] == 1) {
			$result = &$group_result;
			}

		require_once('html/group_splash.php');
		}
		elseif ($qls->user_info['auth_admin_add_group'] == 1 && $qls->user_info['auth_admin_list_groups'] == 1 && $qls->user_info['auth_admin_remove_group'] == 1 && $qls->user_info['auth_admin_edit_group'] == 1) {
		$result = &$group_result;
		require_once('html/group_splash.php');
		}
		else {
		$list = true;
			if ($_GET['join'] == 1) {
				if ($qls->Group->join_group()) {
				$message = GROUP_JOIN_SUCCESS;
				}
				else {
				$message = $qls->Group->join_group_error;
				}
			}

			if ($_GET['leave'] == 1) {
				if ($qls->Group->leave_group()) {
				$message = GROUP_LEAVE_SUCCESS;
				}
				else {
				$message = $qls->Group->leave_group_error;
				}
			}

		// Get the groups that are public
		$result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}groups` WHERE `is_public`=1");
		require_once('html/group_splash.php');
		}
	}
	else {
		// Make sure they are allowed access
		if ($qls->user_info['id'] == $group_info['leader'] || ($qls->user_info['auth_admin_add_group'] == 1 && $qls->user_info['auth_admin_list_groups'] == 1 && $qls->user_info['auth_admin_remove_group'] == 1 && $qls->user_info['auth_admin_edit_group'] == 1) || $qls->user_info['id'] == 1) {
		// Some statistics
		$result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}users` WHERE `group_id`={$group_info['id']}");
		$num_rows = $qls->SQL->num_rows($result);
		$result2 = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}users`");
		$num_rows2 = $qls->SQL->num_rows($result2);
		$percent = round($num_rows / $num_rows2, 2) * 100 . '%';
			// What are we trying to do?
			switch ($_GET['do']) {
				default:
				require_once('html/group_splash.php');
				break;
				case 'main':
				printf(GROUP_PANEL_INFORMATION, $num_rows, $num_rows2, $percent);
				break;
				case 'user_list':
				$perpage = 20;
				// Grab the necessary variables and find out some info
				$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $qls->Security->make_safe($_GET['page']) : 1;
				$offset = ($page - 1) * $perpage;
					// Are we viewing the group members or the entire user list?
					switch ($_GET['area']) {
						case 'group':
						$area = $qls->Security->make_safe($_GET['area']);
						$users_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}users` WHERE `group_id`={$group_info['id']} ORDER BY `id` DESC LIMIT {$offset},{$perpage}");
						require_once('html/group_list_members.php');
						break;
						case 'all';
						$area = $qls->Security->make_safe($_GET['area']);
						$users_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}users` WHERE `group_id`<>{$group_info['id']} ORDER BY `id` DESC LIMIT {$offset},{$perpage}");
						require_once('html/group_list_users.php');
						break;
					}
				break;
				case 'add_user':
					if ($group_info['is_public'] == 0) {
						if ($_GET['type'] == 'process') {
							if ($qls->Group->add_user()) {
							printf(GROUP_ADD_USER_SUCCESS, htmlentities($_GET['username']));
							}
							else {
							printf($qls->Group->add_user_error . GROUP_ADD_USER_TRY_AGAIN, $group_info['id']);
							}
						}
						else {
						require_once('html/group_add_user_form.php');
						}
					}
					else {
					echo GROUP_ADD_NO_NEED;
					}
				break;
				case 'remove_user':
					if ($_GET['type'] == 'process') {
						// Which method are we using
						if (isset($_GET['user_id'])) {
						$user_id = $qls->Security->make_safe($_GET['user_id']);
						$username = $qls->id_to_username($_GET['user_id']);
						}
						else {
						$user_id = $qls->username_to_id($_GET['username']);
						$username = htmlentities($_GET['username']);
						}

						if ($qls->Group->remove_user()) {
						printf(GROUP_REMOVE_USER_SUCCESS, stripslashes($username));
						}
						else {
						printf($qls->Group->remove_user_error . GROUP_REMOVE_USER_TRY_AGAIN, $user_id, $group_info['id']);
						}
					}
					else {
					require_once('html/group_remove_user_form.php');
					}
				break;
			}
		}
		else {
		echo GROUP_CP_NO_AUTH;
		}
	}
}
else {
echo ADMIN_NOT_LOGGED;
}
?>