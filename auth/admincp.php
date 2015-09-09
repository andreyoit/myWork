<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    admincp.php
* @start   July 26th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 2.0.1
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

// Is the user logged in and an admin?
if ($qls->user_info['username'] != '') {
	if ($qls->user_info['auth_admin'] == 1) {
		// Find out which action we are doing
		switch ($_GET['do']) {
			case 'main':
                echo ADMIN_PANEL_WELCOME;
                break;
			case 'phpinfo':
				if ($qls->user_info['auth_admin_phpinfo'] == 1) {
				    @phpinfo();
				}
				else {
				    echo ADMIN_PHPINFO_NO_AUTH;
				}

			    break;
			case 'updates':
                require_once('html/admin_updates.php');
                break;
			case 'configuration':
				if ($qls->user_info['auth_admin_configuration'] == 1) {
					if ($_GET['type'] == 'process') {
						if ($qls->Admin->edit_configuration()) {
					    	echo ADMIN_CONFIG_SUCCESS;
						}
						else {
						    echo $qls->Admin->configuration_error . ADMIN_CONFIG_TRY_AGAIN;
						}
					}
					else {
					    require_once('html/admin_configuration_form.php');
					}
				}
				else {
				    echo ADMIN_CONFIG_NO_AUTH;
				}

			    break;
			case 'add_user':
				if ($qls->user_info['auth_admin_add_user'] == 1) {
					if ($_GET['type'] == 'process') {
						if ($qls->Admin->add_user()) {
						    echo ADMIN_ADD_USER_SUCCESS;
						}
						else {
						    echo $qls->Admin->add_user_error . ADMIN_ADD_USER_TRY_AGAIN;
						}
					}
					else {
                        // Get the groups and masks
                        $groups_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}groups`");
                        $masks_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}masks`");
                        require_once('html/admin_add_user_form.php');
					}
				}
				else {
				    echo ADMIN_ADD_USER_NO_AUTH;
				}

			    break;
			case 'user_list':
				if ($qls->user_info['auth_admin_user_list'] == 1) {
                    $perpage = 20;
                    // Grab the necessary variables and find out some info
                    $page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $qls->Security->make_safe($_GET['page']) : 1;
                    $offset = ($page - 1) * $perpage;
                    $users_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}users` ORDER BY `id` DESC LIMIT {$offset},{$perpage}");
                    require_once('html/admin_list_users.php');
				}
				else {
				    echo ADMIN_USER_LIST_NO_AUTH;
				}

			    break;
			case 'remove_user':
				if ($qls->user_info['auth_admin_remove_user'] == 1) {
					if ($_GET['type'] == 'process') {
						// What are we using currently?
						if (isset($_GET['user_id'])) {
                            $user_id = $qls->Security->make_safe($_GET['user_id']);
                            $username = stripslashes($qls->id_to_username($_GET['user_id']));
						}
						else {
                            $user_id = $qls->User->username_to_id($_GET['username']);
                            $username = htmlentities($_GET['username']);
						}

						if ($qls->Admin->remove_user()) {
						    printf(ADMIN_REMOVE_USER_SUCCESS, $username);
						}
						else {
						    printf($qls->Admin->remove_user_error . ADMIN_REMOVE_USER_TRY_AGAIN, $user_id);
						}
					}
					else {
					    require_once('html/admin_remove_user_form.php');
					}
				}
				else {
				    echo ADMIN_REMOVE_USER_NO_AUTH;
				}

			    break;
			case 'edit_user':
				if ($qls->user_info['auth_admin_edit_user'] == 1) {
					if ($_GET['type'] == 'process') {
						// Grab the user id and the rest of their information
						if (isset($_GET['user_id'])) {
						    $user_id = $qls->Security->make_safe($_GET['user_id']);
						}
						else {
						    $user_id = $qls->User->username_to_id($_GET['username']);
						}

                        $result = $qls->SQL->select('*',
                            'users',
                            array('id' =>
                                array(
                                    '=',
                                    $user_id
                                )
                            )
                        );
					    $row = $qls->SQL->fetch_array($result);

						if ($_GET['type2'] == 'process') {
							if ($qls->Admin->edit_user()) {
							    // Everything went well...
							    printf(ADMIN_EDIT_USER_SUCCESS, stripslashes($row['username']), $user_id);
							}
							else {
						    	printf($qls->Admin->edit_user_error . ADMIN_EDIT_USER_TRY_AGAIN, $user_id);
							}
						}
						else {
                            // Get the groups and masks
                            $groups_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}groups`");
                            $masks_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}masks`");
                            require_once('html/admin_edit_user_real_form.php');
						}
					}
					else {
					    require_once('html/admin_edit_user_form.php');
					}
				}
				else {
				    echo ADMIN_EDIT_USER_NO_AUTH;
				}

			    break;
			case 'list_activations':
				if ($qls->user_info['auth_admin_activate_account'] == 1) {
					if ($qls->config['activation_type'] == 1 || $qls->config['activation_type'] == 2) {
						if ($_GET['type'] == 'process') {
							if (isset($_GET['user_id'])) {
							    $user_id = htmlentities($_GET['user_id']);
							    $username = stripslashes($qls->User->id_to_username($_GET['user_id']));
                            }
							else {
							    $user_id = $qls->User->username_to_id($_GET['username']);
							    $username = htmlentities($_GET['username']);
                            }

							if ($qls->Admin->activate_account()) {
							    printf(ADMIN_ACTIVATE_USER_SUCCESS, $username);
							}
							else {
							    printf($qls->Admin->activate_user_error . ADMIN_ACTIVATE_USER_TRY_AGAIN, $user_id);
							}
						}

                        // Display the activations even if we processed and output above
                        $result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}users` WHERE `active`='no' ORDER BY `id` DESC");
                        require_once('html/admin_list_activations.php');
					}
					else {
					    echo ADMIN_ACTIVATE_NO_NEED;
					}
				}
				else {
			    	echo ADMIN_ACTIVATE_USER_NO_AUTH;
				}

			    break;
			case 'add_page':
				if ($qls->user_info['auth_admin_add_page'] == 1) {
				// Add page is not using AJAX! It will go to a new page
					if ($_GET['type'] == 'process') {
					    $upload = ($_GET['type2'] == 'process') ? true : false;

						if ($qls->Admin->add_page($upload)) {
                            $page_name = ($upload) ? trim($_FILES['upload']['name']) : trim($_POST['name']);
                            echo ADMIN_ADD_PAGE_SUCCESS;
						}
						else {
						    echo $qls->Admin->add_page_error . ADMIN_ADD_PAGE_TRY_AGAIN;
						}
					}
					else {
					    require_once('html/admin_add_page_form.php');
					}
				}
				else {
				    echo ADMIN_ADD_PAGE_NO_AUTH;
				}

			    break;
			case 'page_list':
				if ($qls->user_info['auth_admin_page_list'] == 1) {
                    $pages_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}pages`");
                    require_once('html/admin_list_pages.php');
				}
				else {
				    echo ADMIN_PAGE_LIST_NO_AUTH;
				}

		    	break;
			case 'remove_page':
				if ($qls->user_info['auth_admin_remove_page'] == 1) {
					if ($_GET['type'] == 'process') {
						if (isset($_GET['page_id'])) {
                            $page_id = htmlentities($_GET['page_id']);
                            $page_name = $qls->page_id_to_name($_GET['page_id']);
						}
						else {
                            $page_id = $qls->page_name_to_id($_GET['page_name']);
                            $page_name = htmlentities($_GET['page_name']);
						}

						if ($qls->Admin->remove_page()) {
						    printf(ADMIN_REMOVE_PAGE_SUCCESS, $page_name);
						}
						else {
						    printf($qls->Admin->remove_page_error . ADMIN_REMOVE_PAGE_TRY_AGAIN, $page_id);
						}
					}
					else {
                        // Grab this information for the form
                        $pages_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}pages`");
                        require_once('html/admin_remove_page_form.php');
					}
				}
				else {
				    echo ADMIN_REMOVE_PAGE_NO_AUTH;
				}

			    break;
			case 'edit_page':
				if ($qls->user_info['auth_admin_edit_page'] == 1) {
					if ($_GET['type'] == 'process') {
						if (isset($_GET['page_id'])) {
                            $page_id = $qls->Security->make_safe($_GET['page_id']);
                            $page_name = $qls->page_id_to_name($_GET['page_id']);
						}
						else {
                            $page_id = $qls->page_name_to_id($_GET['page_name']);
                            $page_name = trim($_GET['page_name']);
						}

                        // Grab the page information for the form
                        $result = $qls->SQL->select('*',
                            'pages',
                            array('id' =>
                                array(
                                    '=',
                                    $page_id
                                )
                            )
                        );
                        $row = $qls->SQL->fetch_array($result);

						if ($_GET['type2'] == 'process') {
							if ($qls->Admin->edit_page()) {
							    printf(ADMIN_EDIT_PAGE_SUCCESS, stripslashes($row['name']), $page_id);
							}
							else {
							    printf($qls->Admin->edit_page_error . ADMIN_EDIT_PAGE_TRY_AGAIN, $page_id);
                            }
						}
						else {
                            $file_data = $qls->fetch_file_data($page_name);

							// Try to read from the file for the form
							if ($file_data === false) {
							    printf($qls->file_data_error, $page_name);
							}
							else {
							    require_once('html/admin_edit_page_real_form.php');
							}
						}
					}
					else {
                        // Grab this information for the form
                        $pages_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}pages`");
                        require_once('html/admin_edit_page_form.php');
					}
				}
				else {
				    echo ADMIN_EDIT_PAGE_NO_AUTH;
				}

		    	break;
			case 'add_mask':
				if ($qls->user_info['auth_admin_add_mask'] == 1) {
					if ($_GET['type'] == 'process') {
						if ($qls->Admin->add_mask()) {
						    echo ADMIN_ADD_MASK_SUCCESS;
						}
						else {
						    echo $qls->Admin->add_mask_error . ADMIN_ADD_MASK_TRY_AGAIN;
						}
					}
					else {
                        // Get for the form
                        $pages_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}pages`");
                        require_once('html/admin_add_mask_form.php');
					}
				}
				else {
				    echo ADMIN_ADD_MASK_NO_AUTH;
				}

		    	break;
			case 'list_masks':
				if ($qls->user_info['auth_admin_list_masks'] == 1) {
                    $masks_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}masks`");
                    require_once('html/admin_list_masks.php');
				}
				else {
				    echo ADMIN_LIST_MASKS_NO_AUTH;
				}

			    break;
			case 'remove_mask':
				if ($qls->user_info['auth_admin_remove_mask'] == 1) {
					if ($_GET['type'] == 'process') {
						if ($qls->Admin->remove_mask()) {
						    echo ADMIN_REMOVE_MASK_SUCCESS;
						}
						else {
							// What method are we using right now?
							if (isset($_GET['mask_id'])) {
							    $mask_id = htmlentities($_GET['mask_id']);
							}
							else {
							    $mask_id = $qls->mask_name_to_id($_GET['mask_name']);
							}

						    printf($qls->Admin->remove_mask_error . ADMIN_REMOVE_MASK_TRY_AGAIN, $mask_id);
						}
					}
					else {
                        // Get for the form
                        $masks_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}masks`");
                        require_once('html/admin_remove_mask_form.php');
					}
				}
				else {
				    echo ADMIN_REMOVE_MASK_NO_AUTH;
				}

			    break;
			case 'edit_mask':
				if ($qls->user_info['auth_admin_edit_mask'] == 1) {
					if ($_GET['type'] == 'process') {
						if (isset($_GET['mask_id'])) {
						    $mask_id = $qls->Security->make_safe($_GET['mask_id']);
						}
						else {
						    $mask_id = $qls->mask_name_to_id($_GET['mask_id']);
						}

                        // Grab the information from the database
                        $result = $qls->SQL->select('*',
                            'masks',
                            array('id' =>
                                array(
                                    '=',
                                    $mask_id
                                )
                            )
                        );
					    $row = $qls->SQL->fetch_array($result);

						if ($_GET['type2'] == 'process') {
							if ($qls->Admin->edit_mask()) {
						    	printf(ADMIN_EDIT_MASK_SUCCESS, stripslashes($row['name']), $mask_id);
							}
							else {
						    	printf($qls->Admin->edit_mask_error . ADMIN_EDIT_MASK_TRY_AGAIN, $mask_id);
							}
						}
						else {
                            // Get the pages
                            $pages_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}pages`");
                            require_once('html/admin_edit_mask_real_form.php');
						}
					}
					else {
                        // Get this information for the form
                        $masks_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}masks`");
                        require_once('html/admin_edit_mask_form.php');
					}
				}
				else {
				    echo ADMIN_EDIT_MASK_NO_AUTH;
				}

			    break;
			case 'add_group':
				if ($qls->user_info['auth_admin_add_group'] == 1) {
					if ($_GET['type'] == 'process') {
						if ($qls->Admin->add_group()) {
						    echo ADMIN_ADD_GROUP_SUCCESS;
						}
						else {
						    echo $qls->Admin->add_group_error . ADMIN_ADD_GROUP_TRY_AGAIN;
						}
					}
					else {
                        // Mask information for the form
                        $masks_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}masks`");
                        require_once('html/admin_add_group_form.php');
					}
				}
				else {
			    	echo ADMIN_ADD_GROUP_NO_AUTH;
				}

			    break;
			case 'list_groups':
				if ($qls->user_info['auth_admin_list_groups'] == 1) {
                    $groups_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}groups`");
                    require_once('html/admin_list_groups.php');
				}
				else {
				    echo ADMIN_LIST_GROUPS_NO_AUTH;
				}

		    	break;
			case 'remove_group':
				if ($qls->user_info['auth_admin_remove_group'] == 1) {
					if ($_GET['type'] == 'process') {
						// Which one are we using?
						if (isset($_GET['group_id'])) {
						    $group_id = htmlentities($_GET['group_id']);
						    $group_name = stripslashes($qls->group_id_to_name($_GET['group_id']));
						}
						else {
						    $group_id = $qls->group_name_to_id($_GET['group_name']);
						    $group_name = htmlentities($_GET['group_name']);
						}

						if ($qls->Admin->remove_group()) {
						    printf(ADMIN_REMOVE_GROUP_SUCCESS, $group_name);
						}
						else {
						    printf($qls->Admin->remove_group_error . ADMIN_REMOVE_GROUP_TRY_AGAIN, $group_id);
						}
					}
					else {
                        // Get the group information for the form
                        $groups_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}groups`");
                        require_once('html/admin_remove_group_form.php');
					}
				}
				else {
				    echo ADMIN_REMOVE_GROUP_NO_AUTH;
				}

			    break;
			case 'edit_group':
				if ($qls->user_info['auth_admin_edit_group'] == 1) {
					if ($_GET['type'] == 'process') {
						if (isset($_GET['group_id'])) {
						    $group_id = $qls->Security->make_safe($_GET['group_id']);
						}
						else {
						    $group_id = $qls->group_name_to_id($_GET['group_name']);
						}

                        // Grab the stuff from the database
                        $result = $qls->SQL->select('*',
                            'groups',
                            array('id' =>
                                array(
                                    '=',
                                    $group_id
                                )
                            )
                        );
					    $row = $qls->SQL->fetch_array($result);

						if ($_GET['type2'] == 'process') {
							if ($qls->Admin->edit_group()) {
							    printf(ADMIN_EDIT_GROUP_SUCCESS, stripslashes($row['name']), $group_id);
							}
							else {
							    printf($qls->Admin->edit_group_error . ADMIN_EDIT_GROUP_TRY_AGAIN, $group_id);
							}
						}
						else {
                            // Get the mask information for the form
                            $masks_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}masks`");
                            require_once('html/admin_edit_group_real_form.php');
						}
					}
					else {
                        // Get the group information for the form
                        $groups_result = $qls->SQL->query("SELECT * FROM `{$qls->config['sql_prefix']}groups`");
                        require_once('html/admin_edit_group_form.php');
					}
				}
				else {
				    echo ADMIN_EDIT_GROUP_NO_AUTH;
				}

		    	break;
			case 'send_invite':
				if ($qls->user_info['auth_admin_send_invite'] == 1) {
					if ($_GET['type'] == 'process') {
						if ($qls->Admin->send_invite()) {
					    	echo ADMIN_SEND_INVITE_SUCCESS;
						}
						else {
						    echo $qls->Admin->send_invite_error . ADMIN_SEND_INVITE_TRY_AGAIN;
						}
					}
					else {
					    $site_url = $qls->config['cookie_domain'] . $qls->config['cookie_path'];

						// This is the current URL to the register.php page
						if (substr($qls->config['cookie_domain'], 0, 1) == '.') {
							if (substr($qls->config['cookie_path'], -1) == '/') {
							    $register_url = "http://www{$site_url}register.php";
							}
							else {
							    $register_url = "http://www{$site_url}/register.php";
							}
						}
						else {
							if (substr($qls->config['cookie_path'], -1) == '/') {
							    $register_url = "http://{$site_url}register.php";
							}
							else {
							    $register_url = "http://{$site_url}/register.php";
							}
						}

					    require_once('html/admin_send_invite_form.php');
					}
				}
				else{
				    echo ADMIN_SEND_INVITE_NO_AUTH;
				}

			    break;
			default:
                require_once('html/admin_panel.php');
                break;
		}
	}
	else {
	    echo ADMIN_NOT_ADMIN;
	}
}
else {
    echo ADMIN_NOT_LOGGED;
}