<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    Admin.class.php
* @start   July 26th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.2.4
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
if (!defined('QUADODO_IN_SYSTEM')) {
exit;	
}

/**
 * Contains all the functions needed to run the admin panel
 */
class Admin {

/**
 * @var object $qls - Contains everything else
 */
var $qls;

/**
 * @var array $permissions - List of permissions for the masks
 */
var $permissions = array(
	'admin',
	'admin_phpinfo',
	'admin_configuration',
	'admin_add_user',
	'admin_user_list',
	'admin_remove_user',
	'admin_edit_user',
	'admin_add_page',
	'admin_page_list',
	'admin_remove_page',
	'admin_edit_page',
	'admin_add_mask',
	'admin_list_masks',
	'admin_remove_mask',
	'admin_edit_mask',
	'admin_add_group',
	'admin_list_groups',
	'admin_remove_group',
	'admin_edit_group',
	'admin_activate_account',
	'admin_send_invite'
);

	/** 
	 * Contructs the class
	 * @param object $qls - Contains all classes
	 * @return void
	 */
	function Admin(&$qls) {
	    $this->qls = &$qls;
	}

	/**
	 * Inserts registration data into the database (taken from User.class.php)
	 * @param string  $username - The user's username
	 * @param string  $password - The user's password
	 * @param string  $email    - The user's email address
	 * @param integer $mask_id  - The permission mask
	 * @param integer $group_id - Their group id
	 * @return void but will output error if found
	 */
	function insert_registration_data($username, $password, $mask_id, $group_id, $email) {
        // Generate activation code
        $generated_code = $this->qls->User->generate_activation_code($username, $password, $email);

        // All the columns that should be in the users table
        $columns = array(
            'username',
            'password',
            'code',
            'active',
            'last_login',
            'last_session',
            'blocked',
            'tries',
            'last_try',
            'email',
            'mask_id',
            'group_id'
        );

        // All the values that go with the columns
        $values = array(
            $username,
            $this->qls->User->generate_password_hash($password, $generated_code),
            $generated_code,
            'no',
            0,
            '',
            'no',
            0,
            0,
            $email,
            $mask_id,
            $group_id
        );

		// Is activation required?
		if ($this->qls->config['activation_type'] == 0) {
		    $values[3] = 'yes';
		}
		elseif ($this->qls->config['activation_type'] == 1) {
            $headers = "From: {$email}\r\n";

            // Grab the activation link
            $activation_link = $this->qls->generate_activation_link($generated_code, $username);
            @mail($email, ACTIVATION_SUBJECT, ACTIVATION_BODY . "\n\n{$activation_link}", $headers);
		}

	    $this->qls->SQL->insert('users', $columns, $values);
	}

	/**
	 * This will add a user (taken from User.class.php)
	 * @return true on success, false on error
	 */
	function add_user() {
		if ($this->qls->user_info['auth_admin_add_user'] == 1) {
            $username = (isset($_GET['username']) && $this->qls->User->validate_username($_GET['username'])) ? $this->qls->Security->make_safe($_GET['username']) : false;
            $password = (isset($_GET['password']) && $this->qls->User->validate_password($_GET['password'])) ? $this->qls->Security->make_safe($_GET['password']) : false;
            $confirm_password = (isset($_GET['password_c']) && $_GET['password_c'] == $password) ? true : false;
            $email = (isset($_GET['email']) && strlen($_GET['email']) > 6 && strlen($_GET['email']) < 256 && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) ? $this->qls->Security->make_safe($_GET['email']) : false;
            $confirm_email = (isset($_GET['email_c']) && $_GET['email_c'] == $email) ? true : false;
            $mask_id = (isset($_GET['mask_id']) && is_numeric($_GET['mask_id']) && $_GET['mask_id'] > -1) ? $this->qls->Security->make_safe($_GET['mask_id']) : false;
            $group_id = (isset($_GET['group_id']) && is_numeric($_GET['group_id']) && $_GET['group_id'] > -1) ? $this->qls->Security->make_safe($_GET['group_id']) : false;

			if ($username === false) {
                $this->add_user_error = REGISTER_USERNAME_ERROR;
                return false;
			}

			if ($this->qls->User->check_username_existence($username)) {
                $this->add_user_error = REGISTER_USERNAME_EXISTS;
                return false;
			}

			if ($password === false || $confirm_password === false) {
                $this->add_user_error = REGISTER_PASSWORD_ERROR;
                return false;
			}

			if ($email === false || $confirm_email === false) {
                $this->add_user_error = REGISTER_EMAIL_ERROR;
                return false;
			}

			if ($mask_id === false) {
                $this->add_user_error = ADMIN_MASK_ID_ERROR;
                return false;
			}

			if ($group_id === false) {
                $this->add_user_error = ADMIN_GROUP_ID_ERROR;
                return false;
			}

            $this->insert_registration_data($username, $password, $mask_id, $group_id, $email);
            return true;
		}
		else {
            $this->add_user_error = ADMIN_ADD_USER_NO_AUTH;
            return false;
		}
	}

	/**
	 * Removes a user from the database
	 * @return true on success, false on failure
	 */
	function remove_user() {
		if ($this->qls->user_info['auth_admin_remove_user'] == 1) {
			// Check which method we are using
			if (isset($_GET['user_id'])) {
			    $user_id = $this->qls->Security->make_safe($_GET['user_id']);
			}
			else {
			    $user_id = $this->qls->username_to_id($_GET['username']);
			}

            $result = $this->qls->SQL->select('id',
                'users',
                array('id' =>
                    array(
                        '=',
                        $user_id
                    )
                )
            );
            $row = $this->qls->SQL->fetch_array($result);
			if ($row[0] != '') {
				// Can't delete themselves or the 1st user
				if ($user_id != $this->qls->user_info['id'] && $user_id != 1) {
                    $this->qls->SQL->delete('users',
                        array('id' =>
                            array(
                                '=',
                                $user_id
                            )
                        )
                    );
                    return true;
				}
				else {
				    $this->remove_user_error = ADMIN_CANT_REMOVE_SELF;
				    return false;
				}
			}
			else {
			    $this->remove_user_error = ADMIN_USER_DOESNT_EXIST;
			    return false;
			}
		}
		else {
		    $this->remove_user_error = ADMIN_REMOVE_USER_NO_AUTH;
		    return false;
		}
	}

	/**
	 * Inserts the editing user info into the database
	 * @param string  $user_id      - Current user ID
	 * @param string  $new_username - New Username
	 * @param integer $new_mask_id  - The new mask ID
	 * @param integer $new_group_id - The new group ID
	 * @param string  $new_email    - New Email Address
	 * @param string  $new_banned   - Are they banned or not (yes or no)
	 * @return void but will output SQL error if found
	 */
	function insert_new_user_data($user_id, $new_username, $new_mask_id, $new_group_id, $new_email, $new_banned) {
		if ($new_email == 'one_in_database') {
            // Update the default fields but keep same email
            $this->qls->SQL->update('users',
                array(
                    'username' => $new_username,
                    'blocked' => $new_banned,
                    'mask_id' => $new_mask_id,
                    'group_id' => $new_group_id
                ),
                array('id' =>
                    array(
                        '=',
                        $user_id
                    )
                )
            );
		}
		else {
            // Update the default fields
            $this->qls->SQL->update('users',
                array(
                    'username' => $new_username,
                    'email' => $new_email,
                    'blocked' => $new_banned,
                    'mask_id' => $new_mask_id,
                    'group_id' => $new_group_id
                ),
                array('id' =>
                    array(
                        '=',
                        $user_id
                    )
                )
            );
		}
	}

	/**
	 * Edits a user
	 * @return true on success, false on failure
	 */
	function edit_user() {
		// Check what method we are using
		if (isset($_GET['user_id'])) {
		    $user_id = $this->qls->Security->make_safe($_GET['user_id']);
		}
		else {
		    $user_id = $this->qls->username_to_id($_GET['username']);
		}

        // Check to see if the user exists
        $result = $this->qls->SQL->select('*',
            'users',
            array('id' =>
                array(
                    '=',
                    $user_id
                )
            )
        );
        $row = $this->qls->SQL->fetch_array($result);

        // Get new items
        $new_username = (isset($_GET['new_username']) && $this->qls->User->validate_username($_GET['new_username'])) ? $this->qls->Security->make_safe($_GET['new_username']) : false;
        $new_email = (isset($_GET['new_email']) && filter_var($_GET['new_email'], FILTER_VALIDATE_EMAIL)) ? $this->qls->Security->make_safe($_GET['new_email']) : false;
        $new_banned = ($_GET['new_banned'] == 0 || $_GET['new_banned'] == 1) ? $this->qls->Security->make_safe($_GET['new_banned']) : false;
        $new_mask_id = (isset($_GET['new_mask_id']) && is_numeric($_GET['new_mask_id']) && $_GET['new_mask_id'] > -1) ? $this->qls->Security->make_safe($_GET['new_mask_id']) : false;
        $new_group_id = (isset($_GET['new_group_id']) && is_numeric($_GET['new_group_id']) && $_GET['new_group_id'] > -1) ? $this->qls->Security->make_safe($_GET['new_group_id']) : false;

		// Check all the input
		if ($row['id'] == '') {
            $this->edit_user_error = ADMIN_USER_DOESNT_EXIST;
            return false;
		}

		if ($this->qls->User->check_username_existence($new_username) && $new_username != $row['username']) {
            $this->edit_user_error = REGISTER_USERNAME_EXISTS;
            return false;
		}

		if ($new_username === false) {
            $this->edit_user_error = REGISTER_USERNAME_ERROR;
            return false;
		}

		if ($new_username === false) {
            $this->edit_user_error = REGISTER_USERNAME_ERROR;
            return false;
        }

		if ($new_email === false && $_GET['new_email'] != '') {
            $this->edit_user_error = REGISTER_EMAIL_ERROR;
            return false;
		}
		elseif ($new_email === false && $_GET['new_email'] == '') {
		    $new_email = 'one_in_database';
		}

		if ($new_banned === false) {
            $this->edit_user_error = ADMIN_EDIT_USER_BANNED_ERROR;
            return false;
		}

		if ($new_banned == 1) {
			if ($user_id == $this->qls->user_info['id'] || $user_id == 1) {
			    $this->edit_user_error = ADMIN_USERNAME_YOUR_OWN;
			    return false;
			}

		    $new_banned = 'yes';
		}
		else {
		    $new_banned = 'no';
		}

		if ($new_mask_id === false) {
		    $this->edit_user_error = ADMIN_MASK_ID_ERROR;
		    return false;
		}

		if ($new_group_id === false) {
		    $this->edit_user_error = ADMIN_GROUP_ID_ERROR;
		    return false;
		}

	    $this->insert_new_user_data($user_id, $new_username, $new_mask_id, $new_group_id, $new_email, $new_banned);
	    return true;
	}

	/**
	 * Activates a user account
	 * @return true on success, false on failure
	 */
	function activate_account() {
		if ($this->qls->user_info['auth_admin_activate_account'] == 1) {
		    // Check which method we are using (ID or username)
			if (isset($_GET['user_id'])) {
			    $user_id = $this->qls->Security->make_safe($_GET['user_id']);
			}
			else {
			    $user_id = $this->qls->User->username_to_id($_GET['username']);
			}

			if ($user_id > 0) {
                $this->qls->SQL->update('users',
                    array(
                        'active' => 'yes',
                        'activation_time' => time()
                    ),
                    array('id' =>
                        array(
                            '=',
                            $user_id
                        )
                    )
                );

                $user_result = $this->qls->SQL->query("SELECT `email`,`username` FROM `{$this->qls->config['sql_prefix']}users` WHERE `id`={$user_id}");
                $user_row = $this->qls->SQL->fetch_row($user_result);

                $headers = "From: {$user_row[0]}\r\n";
                // Email stuff...

                if (substr($this->qls->config['cookie_domain'], 0, 1) == '.') {
                    $site_link = "www{$this->qls->config['cookie_domain']}";
                }
                else {
                    $site_link = $this->qls->config['cookie_domain'];
                }

                if (substr($this->qls->config['cookie_path'], -1) == '/') {
                    $login_link = "http://{$site_link}{$this->qls->config['cookie_path']}login.php";
                }
                else {
                    $login_link = "http://{$site_link}{$this->qls->config['cookie_path']}/login.php";
                }


                @mail($user_row[0], ACTIVATION_SUBJECT, sprintf(ACTIVATION_BODY_SUCCESS, stripslashes($user_row[1]), $site_link, $login_link), $headers);
                return true;
			}
			else {
                $this->activate_user_error = LOGIN_USER_INFO_MISSING;
                return false;
			}
		}
		else {
            $this->activate_user_error = ADMIN_ACTIVATE_USER_NO_AUTH;
            return false;
		}
	}

	/**
	 * Adds a page into the database/uploads a page
	 * @param boolean $upload - Are we uploading a file?
	 * @return true on success, false on failure
	 */
	function add_page($upload) {
		if ($this->qls->user_info['auth_admin_add_page'] == 1) {
			if ($upload === true) {
			    $file_name = $this->qls->Security->make_safe(trim($_FILES['upload']['name']));

				if ($this->qls->Upload->upload_file()) {
                    // Insert the page information to the database
                    $this->qls->SQL->insert('pages',
                        array(
                            'name',
                            'hits'
                        ),
                        array(
                            $file_name,
                            0
                        )
                    );

				    // Add to the permissions table
                    $this->qls->SQL->query("ALTER TABLE `{$this->qls->config['sql_prefix']}masks` ADD `auth_" . sha1($this->qls->page_name_to_id($file_name)) . "` int DEFAULT '0' NOT NULL");
                    return true;
				}
				else {
                    @unlink($this->qls->main_directory . '/' . $file_name);
                    return false;
				}
			}
			else {
                $page_name = (isset($_POST['name']) && strlen($_POST['name']) <= 255 && strlen($_POST['name']) > 0) ? $this->qls->Security->make_safe(trim($_POST['name'])) : time() . '.php';
                $page_data = $_POST['data'];
                $page_size = strlen($page_data);
                $page_extension = strtolower(substr($page_name, -4));
                $new_file_location = $this->qls->main_directory . '/' . $page_name;

                // We aren't going to create the page, just insert into database
                if ($page_size == 0) {
                    $this->qls->SQL->insert('pages',
                        array(
                            'name',
                            'hits'
                        ),
                        array(
                            $page_name,
                            0
                        )
                    );

                    // Add to the masks table
                    $this->qls->SQL->query("ALTER TABLE `{$this->qls->config['sql_prefix']}masks` ADD `auth_" . sha1($this->qls->page_name_to_id($page_name)) . "` int DEFAULT '0' NOT NULL");
                    return true;
				}
				else {
					if (!file_exists($new_file_location)) {
						// Is the page data less than 1MB?
						if ($page_size <= 1048576) {
							if ($page_extension == '.php') {
								// The file doesn't exist, so it will attempt to create
								if ($file_handle = fopen($new_file_location, 'w')) {
									if (fwrite($file_handle, $page_data)) {
                                        fclose($file_handle);
                                        $this->qls->SQL->insert('pages',
                                            array(
                                                'name',
                                                'hits'
                                            ),
                                            array(
                                                $page_name,
                                                0
                                            )
                                        );

                                        // Adds it to the permissions list
                                        $this->qls->SQL->query("ALTER TABLE `{$this->qls->config['sql_prefix']}masks` ADD `auth_" . sha1($this->qls->page_name_to_id($page_name)) . "` int DEFAULT '0' NOT NULL");
                                        return true;
									}
									else {
                                        $this->add_page_error = FILE_NOT_CREATED;
                                        return false;
									}
								}
								else {
                                    $this->add_page_error = FILE_NOT_CREATED;
                                    return false;
								}
							}
							else {
                                $this->add_page_error = FILE_EXTENSION_ERROR;
                                return false;
							}
						}
						else {
                            $this->add_page_error = FILE_TOO_BIG;
                            return false;
						}
					}
					else {
                        $this->add_page_error = FILE_EXISTS;
                        return false;
					}
				}
			}
		}
		else {
            $this->add_page_error = ADMIN_ADD_PAGE_NO_AUTH;
            return false;
		}
	}

	/**
	 * Removes a page from the database/main directory
	 * @return true on success, false on failure
	 */
	function remove_page() {
		if ($this->qls->user_info['auth_admin_remove_page'] == 1) {
			if (isset($_GET['page_id'])) {
                $page_id = $this->qls->Security->make_safe($_GET['page_id']);
                $page_name = stripslashes($this->qls->page_id_to_name($_GET['page_id']));
			}
			else {
                $page_id = $this->qls->page_name_to_id($_GET['page_name']);
                $page_name = $_GET['page_name'];
			}
	
			if ($page_id > 0) {
                $this->qls->SQL->delete('pages',
                    array('id' =>
                        array(
                            '=',
                            $page_id
                        )
                    )
                );
                $this->qls->SQL->alter('masks',
                        'drop',
                        'auth_' . sha1($page_id)
                );

				if (unlink($this->qls->main_directory . '/' . $page_name)) {
				    return true;
				}
				else {
                    $this->remove_page_error = ADMIN_FILE_NOT_DELETED;
                    return false;
				}
			}
			else {
                $this->remove_page_error = ADMIN_PAGE_DOESNT_EXIST;
                return false;
			}
		}
		else {
            $this->remove_page_error = ADMIN_REMOVE_PAGE_NO_AUTH;
            return false;
		}
	}

	/**
	 * Edits a page
	 * @return true on success, false on failure
	 */
	function edit_page() {
		if ($this->qls->user_info['auth_admin_edit_page'] == 1) {
			// Check what method we are using
			if (isset($_GET['page_id'])) {
                $page_id = $this->qls->Security->make_safe($_GET['page_id']);
                $page_name = stripslashes($this->qls->page_id_to_name($_GET['page_id']));
			}
			else {
                $page_id = $this->qls->page_name_to_id($_GET['page_name']);
                $page_name = $_GET['page_name'];
			}
	
			if ($page_id > 0) {
                $page_data = $_GET['new_page_data'];
                $new_page_name = (isset($_GET['new_page_name']) && strlen($_GET['new_page_name']) <= 255 && strlen($_GET['new_page_name']) > 0) ? $_GET['new_page_name'] : time() . '.php';
                $old_page_location = $this->qls->main_directory . '/' . $page_name;
                $new_page_location = $this->qls->main_directory . '/' . $new_page_name;
                $new_page_extension = strtolower(substr($new_page_name, -4));

				if ($new_page_name == '' || $new_page_name == $page_name) {
					// Can we open it for writing?
					if (is_writable($old_page_location)) {
						// Attempt to open the file
						if ($file_handle = fopen($old_page_location, 'w')) {
							if (fwrite($file_handle, $page_data)) {
							    fclose($file_handle);
							    return true;
							}
							else {
							    $this->edit_page_error = ADMIN_FILE_NOT_UPDATED;
							    return false;
							}
						}
						else {
						    $this->edit_page_error = ADMIN_FILE_NOT_OPENED;
						    return false;
						}
					}
					else {
					    $this->edit_page_error = ADMIN_FILE_NOT_WRITABLE;
					    return false;
					}
				}
				else {
					if (!file_exists($new_page_location)) {
						if ($new_page_extension == '.php') {
							// Try to create the file
							if ($file_handle = fopen($new_page_location, 'w')) {
								if (fwrite($file_handle, $page_data)) {
                                    fclose($file_handle);
                                    // Try to delete the old file and update database
                                    unlink($old_page_location);
                                    $this->qls->SQL->update('pages',
                                        array(
                                            'name' => $this->qls->Security->make_safe($new_page_name)
                                        ),
                                        array('id' =>
                                            array(
                                                '=',
                                                $page_id
                                            )
                                        )
                                    );
                                    return true;
								}
								else {
                                    $this->edit_page_error = FILE_NOT_CREATED;
                                    return false;
								}
							}
							else {
                                $this->edit_page_error = FILE_NOT_CREATED;
                                return false;
							}
						}
						else {
						    $this->edit_page_error = FILE_EXTENSION_ERROR;
						    return false;
						}
					}
					else {
					    $this->edit_page_error = FILE_EXISTS;
					    return false;
					}
				}
			}
			else {
			    $this->edit_page_error = ADMIN_PAGE_DOESNT_EXIST;
			    return false;
			}
		}
		else {
		    $this->edit_page_error = ADMIN_EDIT_PAGE_NO_AUTH;
		    return false;
		}
	}

	/**
	 * Checks a mask variable.. to save some coding
	 * @param string $variable - The variable to check
	 * @return true on success, false on failure
	 */
	function check_mask_var($variable) {
		if ($variable == 1 || $variable == 0) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * Adds a permission mask
	 * @return true on success, false on failure
	 */
	function add_mask() {
		if ($this->qls->user_info['auth_admin_add_mask'] == 1) {
            $mask_name = (isset($_GET['mask_name']) && strlen($_GET['mask_name']) <= 255 && strlen($_GET['mask_name']) > 0) ? $this->qls->Security->make_safe($_GET['mask_name']) : false;

            // See if this mask name is taken
            $check_mask = $this->qls->mask_name_to_id($mask_name);

			if ($check_mask != '') {
			    $this->add_mask_error = ADMIN_MASK_NAME_TAKEN;
			    return false;
			}

			// Check the mask name
			if ($mask_name === false) {
			    $this->add_mask_error = ADMIN_MASK_NAME_ERROR;
			    return false;
			}

            // Grab all the pages outta the database (we need them later)
            $pages_result = $this->qls->SQL->query("SELECT * FROM `{$this->qls->config['sql_prefix']}pages`");

            // The prefix for all the stuff
            $prefix = 'auth_';
            $values = array($mask_name);

			// Loop through all the pages and add them to the list
			while ($pages_row = $this->qls->SQL->fetch_array($pages_result)) {
			    $this->permissions[] = sha1($pages_row['id']);
			}

		    $count = count($this->permissions);

			// Go through each one and check it
			for ($x = 0; $x < $count; $x++) {
			$this->permissions[$x] = $prefix . $this->permissions[$x];
				if ($this->check_mask_var($_GET[$this->permissions[$x]])) {
				    $values[] = $_GET[$this->permissions[$x]];
				    continue;
				}
				else {
				    $this->add_mask_error = ADMIN_MASK_SELECTION_ERROR;
				    return false;
				}
			}

            array_unshift($this->permissions, 'name');
            $this->qls->SQL->insert('masks',
                $this->permissions,
                $values
            );
            return true;
		}
		else {
            $this->qls->SQL->output_error();
            return false;
		}
	}

	/**
	 * Removes a permission mask
	 * @return true on success, false on failure
	 */
	function remove_mask() {
		if ($this->qls->user_info['auth_admin_remove_mask'] == 1) {
			// Check which method we are using
			if (isset($_GET['mask_id'])) {
			    $mask_id = $this->qls->Security->make_safe($_GET['mask_id']);
			}
			else {
			    $mask_id = $this->qls->mask_name_to_id($_GET['mask_id']);
			}

			if ($mask_id == 1 || $mask_id == 2) {
			    $this->remove_mask_error = ADMIN_CANT_REMOVE_THOSE;
			    return false;
			}

            // Make sure the mask exists
            $check_mask = $this->qls->SQL->select('id',
                'masks',
                array('id' =>
                    array(
                        '=',
                        $mask_id
                    )
                )
            );
            $check_mask = $this->qls->SQL->fetch_array($check_mask);

            // Set to default mask
            $this->qls->SQL->update('users',
                array('mask_id' => 2),
                array('mask_id' =>
                    array(
                        '=',
                        $mask_id
                    )
                )
            );

            // Set to default mask
            $this->qls->SQL->update('groups',
                array('mask_id' => 2),
                array('mask_id' =>
                    array(
                        '=',
                        $mask_id
                    )
                )
            );

			// Does the mask even exist?
			if ($check_mask['id'] != '') {
                $this->qls->SQL->delete('masks',
                    array('id' =>
                        array(
                            '=',
                            $mask_id
                        )
                    )
                );
			    return true;
			}
			else {
                $this->remove_mask_error = ADMIN_MASK_DOESNT_EXIST;
                return false;
			}
		}
		else {
            $this->remove_mask_error = ADMIN_REMOVE_MASK_NO_AUTH;
            return false;
		}
	}

	/**
	 * Edits a mask, very similar to add 0.o
	 * @return true on success, false on failure
	 */
	function edit_mask() {
		if ($this->qls->user_info['auth_admin_edit_mask'] == 1) {
            $mask_id = $this->qls->Security->make_safe($_GET['mask_id']);
            $new_mask_name = $this->qls->Security->make_safe($_GET['new_mask_name']);

            // See if the mask exists
            $result = $this->qls->SQL->select('id',
                'masks',
                array('id' =>
                    array(
                        '=',
                        $mask_id
                    )
                )
            );
            $row = $this->qls->SQL->fetch_array($result);

			if ($row[0] == '') {
                $this->edit_mask_error = ADMIN_MASK_DOESNT_EXIST;
                return false;
			}

            // See if this mask name is taken
            $check_mask = $this->qls->mask_name_to_id($new_mask_name);

			if ($check_mask != '' && $check_mask != $mask_id) {
                $this->edit_mask_error = ADMIN_MASK_NAME_TAKEN;
                return false;
			}

            // Grab all the pages outta the database (we need them later)
            $pages_result = $this->qls->SQL->query("SELECT * FROM `{$this->qls->config['sql_prefix']}pages`");

            // The prefix for all the stuff
            $prefix = 'auth_';
            $values = array();

			// Loop through all the pages and add them to the list
			while ($pages_row = $this->qls->SQL->fetch_array($pages_result)) {
			    $this->permissions[] = sha1($pages_row['id']);
			}

		    $count = count($this->permissions);
			// Go through each one and check it
			for ($x = 0; $x < $count; $x++) {
			    $this->permissions[$x] = $prefix . $this->permissions[$x];

				if ($this->check_mask_var($_GET[$this->permissions[$x]])) {
				    $values[] = $_GET[$this->permissions[$x]];
				    continue;
				}
				else {
				    $this->edit_mask_error = ADMIN_MASK_SELECTION_ERROR;
				    return false;
				}
			}

		    $set = array('name' => $new_mask_name);

			// Loop through the $list and $values to make an associative array
			for ($y = 0; $y < $count; $y++) {
			    $set[$this->permissions[$y]] = $values[$y];
			}

            $this->qls->SQL->update('masks',
                $set,
                array('id' =>
                    array(
                        '=',
                        $mask_id
                    )
                )
            );
            return true;
		}
		else {
            $this->edit_mask_error = ADMIN_EDIT_MASK_NO_AUTH;
            return false;
		}
	}

	/**
	 * Adds a group
	 * @return true on success, false on failure
	 */
	function add_group() {
		if ($this->qls->user_info['auth_admin_add_group'] == 1) {
            // Check the variables
            $name = (isset($_GET['name']) && strlen($_GET['name']) <= 255 && strlen($_GET['name']) > 3) ? $this->qls->Security->make_safe($_GET['name']) : false;
            $mask_id = (isset($_GET['mask_id']) && is_numeric($_GET['mask_id']) && $_GET['mask_id'] > 0) ? $this->qls->Security->make_safe($_GET['mask_id']) : false;
            $leader = (isset($_GET['leader']) && $this->qls->User->validate_username($_GET['leader'])) ? $this->qls->Security->make_safe($_GET['leader']) : false;
            $is_public = ($_GET['is_public'] == 1 || $_GET['is_public'] == 0) ? $this->qls->Security->make_safe($_GET['is_public']) : false;
            $expiration_date = ($_GET['expiration_date'] <= 999 && is_numeric($_GET['expiration_date'])) ? $this->qls->Security->make_safe($_GET['expiration_date']) : 0;

            // See if anything is returned
            $check_mask = $this->qls->mask_id_to_name($mask_id);
            $check_leader = $this->qls->username_to_id($leader);

            // Check if the group already exists
            $check_group = $this->qls->SQL->select('id',
                'groups',
                array('name' =>
                    array(
                        '=',
                        $name
                    )
                )
            );
            $check_group = $this->qls->SQL->fetch_array($check_group);

			if ($check_group[0] != '') {
			    $this->add_group_error = ADMIN_GROUP_ALREADY_EXISTS;
			    return false;
			}

			if ($name === false) {
			    $this->add_group_error = ADMIN_GROUP_NAME_ERROR;
			    return false;
			}

			if ($mask_id === false) {
			    $this->add_group_error = ADMIN_MASK_ID_ERROR;
			    return false;
			}

			if ($leader === false) {
			    $this->add_group_error = ADMIN_LEADER_ID_ERROR;
			    return false;
			}

			if ($is_public === false) {
			    $this->add_group_error = ADMIN_IS_PUBLIC_ERROR;
			    return false;
			}

			if ($check_mask === '') {
			    $this->add_group_error = ADMIN_MASK_DOESNT_EXIST;
			    return false;
			}

			if ($check_leader === '') {
			    $this->add_group_error = LOGIN_USER_INFO_MISSING;
			    return false;
			}

            $this->qls->SQL->insert('groups',
                array(
                    'name',
                    'mask_id',
                    'leader',
                    'is_public',
                    'expiration_date'
                ),
                array(
                    $name,
                    $mask_id,
                    $check_leader,
                    $is_public,
                    $expiration_date
                )
            );
            return true;
		}
		else {
            $this->add_group_error = ADMIN_ADD_GROUP_NO_AUTH;
            return false;
		}
	}

	/**
	 * Removes a group
	 * @return true on success, false on failure
	 */
	function remove_group() {
		// Check which method we are using
		if (isset($_GET['group_id'])) {
		    $group_id = $this->qls->Security->make_safe($_GET['group_id']);
		}
		else {
		    $group_id = $this->qls->group_name_to_id($_GET['group_name']);
		}

		if ($group_id == 1 || $group_id == 2) {
		    $this->remove_group_error = ADMIN_CANT_REMOVE_THOSE;
		    return false;
		}

        $group_info = $this->qls->SQL->select('*',
            'groups',
            array('id' =>
                array(
                    '=',
                    $group_id
                )
            )
        );
        $group_info = $this->qls->SQL->fetch_array($group_info);

		if ($group_info['id'] != '') {
			// Is the user allowed to remove this group?
			if ($this->qls->user_info['auth_admin_remove_group'] == 1 || $this->qls->user_info['id'] == $group_info['leader']) {
                // Set to default
                $this->qls->SQL->update('users',
                    array('group_id' => 2),
                    array('group_id' =>
                        array(
                            '=',
                            $group_id
                        )
                    )
                );

                // Delete the group
                $this->qls->SQL->delete('groups',
                    array('id' =>
                        array(
                            '=',
                            $group_id
                        )
                    )
                );
                return true;
			}
			else {
                $this->remove_group_error = ADMIN_REMOVE_GROUP_NO_AUTH;
                return false;
			}
		}
		else {
            $this->remove_group_error = ADMIN_GROUP_DOESNT_EXIST;
            return false;
		}
	}

	/**
	 * Edits a group
	 * @return true on success, false on failure
	 */
	function edit_group() {
		if ($this->qls->user_info['auth_admin_edit_group'] == 1) {
			// Check which method we plan on using
			if (isset($_GET['group_id'])) {
			$group_id = $this->qls->Security->make_safe($_GET['group_id']);
			}
			else {
			$group_id = $this->qls->group_id_to_name($_GET['group_id']);
			}

            $new_name = (isset($_GET['new_name']) && strlen($_GET['new_name']) <= 255 && strlen($_GET['new_name']) > 0) ? $this->qls->Security->make_safe($_GET['new_name']) : false;
            $new_mask = (isset($_GET['new_mask']) && is_numeric($_GET['new_mask']) && $_GET['new_mask'] > 0) ? $this->qls->Security->make_safe($_GET['new_mask']) : false;
            $new_leader = (isset($_GET['new_leader']) && $this->qls->User->validate_username($_GET['new_leader'])) ? $this->qls->Security->make_safe($_GET['new_leader']) : false;
            $new_is_public = ($_GET['new_is_public'] == 1 || $_GET['new_is_public'] == 0) ? $this->qls->Security->make_safe($_GET['new_is_public']) : false;
            $new_expiration_date = ($_GET['new_expiration_date'] <= 999 && is_numeric($_GET['new_expiration_date'])) ? $this->qls->Security->make_safe($_GET['new_expiration_date']) : 0;
            // Check if the group name already exists
            $check_group = $this->qls->SQL->select('*',
                'groups',
                array('name' =>
                    array(
                        '=',
                        $new_name
                    )
                )
            );
            $check_group = $this->qls->SQL->fetch_array($check_group);

			if ($check_group['name'] == $new_name && $check_group['id'] != $group_id) {
			$this->edit_group_error = ADMIN_GROUP_NAME_EXISTANT;
			return false;
			}

            // Check to make sure the leader and mask are ok
            $check_mask = $this->qls->mask_id_to_name($new_mask);
            $check_leader = $this->qls->username_to_id($new_leader);

			if ($new_name === false) {
                $this->edit_group_error = ADMIN_GROUP_NAME_ERROR;
                return false;
			}

			if ($new_mask === false || $check_mask == '') {
                $this->edit_group_error = ADMIN_MASK_ID_ERROR;
                return false;
			}

			if ($new_leader === false || $check_leader == '') {
                $this->edit_group_error = ADMIN_LEADER_ID_ERROR;
                return false;
			}

			if ($new_is_public === false) {
                $this->edit_group_error = ADMIN_IS_PUBLIC_ERROR;
                return false;
			}

            // Update the database
            $this->qls->SQL->update('groups',
                array(
                    'name' => $new_name,
                    'mask_id' => $new_mask,
                    'leader' => $check_leader,
                    'is_public' => $new_is_public,
                    'expiration_date' => $new_expiration_date
                ),
                array('id' =>
                    array(
                        '=',
                        $group_id
                    )
                )
            );
            return true;
		}
		else {
            $this->edit_group_error = ADMIN_EDIT_GROUP_NO_AUTH;
            return false;
		}
	}

	/**
	 * Sends an invitation to someone so they can register
	 * @return true on success, false on failure
	 */
	function send_invite() {
		if ($this->qls->user_info['auth_admin_send_invite'] == 1) {
            $to = (isset($_GET['to']) && strlen($_GET['to']) > 6 && filter_var($_GET['to'], FILTER_VALIDATE_EMAIL)) ? $_GET['to'] : false;
            $reply_to = (isset($_GET['reply_to']) && strlen($_GET['reply_to']) > 6 && filter_var($_GET['reply_to'], FILTER_VALIDATE_EMAIL)) ? $_GET['reply_to'] : false;
            $subject = (isset($_GET['subject'])) ? trim($_GET['subject']) : false;
            $linkage = (isset($_GET['link'])) ? $_GET['link'] : false;
            $message = (isset($_GET['message'])) ? wordwrap($_GET['message'], 70, "\n", true) : false;

			if ($to === false) {
                $this->send_invite_error = ADMIN_TO_ERROR;
                return false;
			}

			if ($reply_to === false) {
                $this->send_invite_error = ADMIN_REPLY_TO_ERROR;
                return false;
			}

			if ($subject === false) {
                $this->send_invite_error = ADMIN_SUBJECT_ERROR;
                return false;
			}

			if ($linkage === false) {
                $this->send_invite_error = ADMIN_LINKAGE_ERROR;
                return false;
			}

			if ($message === false) {
                $this->send_invite_error = ADMIN_MESSAGE_ERROR;
                return false;
			}

            // Secret code so they can register
            $code = sha1(md5($this->qls->config['sql_prefix']) . time() . $_SERVER['REMOTE_ADDR']);
            $linkage .= '?code=' . $code;
            $headers = "From: <{$reply_to}>\r\nReply-To: <{$reply_to}>";

			if (mail($to, $subject, $linkage . "\n\n" . $message, $headers)) {
                // Insert into database
                $this->qls->SQL->insert('invitations',
                    array(
                        'used',
                        'code'
                    ),
                    array(
                        0,
                        $code
                    )
                );

			    return true;
			}
			else {
                $this->send_invite_error = ADMIN_CANT_SEND_MAIL;
                return false;
			}
		}
		else {
            $this->send_invite_error = ADMIN_SEND_INVITE_NO_AUTH;
            return false;
		}
	}

	/**
	 * Edits the configuration
	 * @return true on success, false on failure
	 */
	function edit_configuration() {
		if ($this->qls->user_info['auth_admin_configuration'] == 1) {
            // List of the current configuration items, for updating database
            $list = array(
                'cookie_prefix',
                'max_username',
                'min_username',
                'max_password',
                'min_password',
                'cookie_path',
                'cookie_secure',
                'cookie_length',
                'cookie_domain',
                'max_tries',
                'user_regex',
                'security_image',
                'activation_type',
                'logout_redirect',
                'max_upload_size',
                'auth_registration',
                'login_redirect',
                'redirect_type',
                'online_users_format',
                'online_users_separator'
            );

            // Meh lot's of stuff to go through 0_0
            $cookie_prefix = (preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]{0,254}$/", $_GET['cookie_prefix']) || $_GET['cookie_prefix'] == '') ? $this->qls->Security->make_safe($_GET['cookie_prefix'], false) : false;
            $max_username = (is_numeric($_GET['max_username']) && strlen($_GET['max_username']) < 3 && strlen($_GET['max_username']) > 0) ? $this->qls->Security->make_safe($_GET['max_username']) : false;
            $min_username = (is_numeric($_GET['min_username']) && strlen($_GET['min_username']) < 3 && strlen($_GET['min_username']) > 0) ? $this->qls->Security->make_safe($_GET['min_username']) : false;
            $max_password = (is_numeric($_GET['max_password']) && strlen($_GET['max_password']) < 3 && strlen($_GET['max_password']) > 0) ? $this->qls->Security->make_safe($_GET['max_password']) : false;
            $min_password = (is_numeric($_GET['min_password']) && strlen($_GET['min_password']) < 3 && strlen($_GET['min_password']) > 0) ? $this->qls->Security->make_safe($_GET['min_password']) : false;
            $cookie_path = (preg_match('/^\/.*?$/', $_GET['cookie_path'])) ? $this->qls->Security->make_safe($_GET['cookie_path']) : false;
            $cookie_secure = ($_GET['cookie_secure'] == 0 || $_GET['cookie_secure'] == 1) ? $this->qls->Security->make_safe($_GET['cookie_secure']) : false;
            $cookie_length = (is_numeric($_GET['cookie_length']) && strlen($_GET['cookie_length']) < 8 && strlen($_GET['cookie_length']) > 0) ? $this->qls->Security->make_safe($_GET['cookie_length']) : false;
            $cookie_domain = (isset($_GET['cookie_domain'])) ? $this->qls->Security->make_safe($_GET['cookie_domain']) : false;
            $max_tries = (is_numeric($_GET['max_tries']) && strlen($_GET['max_tries']) < 3 && strlen($_GET['max_tries']) > 0) ? $this->qls->Security->make_safe($_GET['max_tries']) : false;
            $user_regex = (isset($_GET['user_regex']) && strlen($_GET['user_regex']) < 256) ? $this->qls->Security->make_safe($_GET['user_regex'], false) : false;
            $security_image = ($_GET['security_image'] == 'yes' || $_GET['security_image'] == 'no') ? $this->qls->Security->make_safe($_GET['security_image']) : false;
            $activation_type = ($_GET['activation_type'] == 0 || $_GET['activation_type'] == 1 || $_GET['activation_type'] == 2) ? $this->qls->Security->make_safe($_GET['activation_type']) : false;
            $login_redirect = (isset($_GET['login_redirect']) && strlen($_GET['login_redirect']) < 256 && strlen($_GET['login_redirect']) > 0) ? $this->qls->Security->make_safe($_GET['login_redirect'], false) : false;
            $logout_redirect = (isset($_GET['logout_redirect']) && strlen($_GET['logout_redirect']) < 256 && strlen($_GET['logout_redirect']) > 0) ? $this->qls->Security->make_safe($_GET['logout_redirect'], false) : false;
            $redirect_type = ($_GET['redirect_type'] == '1' || $_GET['redirect_type'] == '2' || $_GET['redirect_type'] == '3') ? $this->qls->Security->make_safe($_GET['redirect_type']) : '1';
            $online_users_format = (isset($_GET['online_users_format']) && strlen($_GET['online_users_format']) <= 255) ? $this->qls->Security->make_safe($_GET['online_users_format'], false) : '{username}';
            $online_users_separator = (isset($_GET['online_users_separator']) && strlen($_GET['online_users_separator']) <= 255) ? $this->qls->Security->make_safe($_GET['online_users_separator'], false) : ',';
            $max_upload_size = (isset($_GET['max_upload_size']) && is_numeric($_GET['max_upload_size']) && $_GET['max_upload_size'] > 0) ? $this->qls->Security->make_safe($_GET['max_upload_size']) : false;
            $auth_registration = ($_GET['auth_registration'] == 1 || $_GET['auth_registration'] == 0) ? $this->qls->Security->make_safe($_GET['auth_registration']) : false;

			// Go through each one and see if it's false
			if ($cookie_prefix === false) {
                $this->configuration_error = ADMIN_COOKIE_PREFIX_ERROR;
                return false;
			}

			if ($max_username === false) {
                $this->configuration_error = ADMIN_MAX_USERNAME_ERROR;
                return false;
			}

			if ($min_username === false) {
                $this->configuration_error = ADMIN_MIN_USERNAME_ERROR;
                return false;
			}

			if ($max_password === false) {
                $this->configuration_error = ADMIN_MAX_PASSWORD_ERROR;
                return false;
			}

			if ($min_password === false) {
                $this->configuration_error = ADMIN_MIN_PASSWORD_ERROR;
                return false;
			}

			if ($cookie_path === false) {
                $this->configuration_error = ADMIN_COOKIE_PATH_ERROR;
                return false;
			}

			if ($cookie_secure === false) {
                $this->configuration_error = ADMIN_COOKIE_SECURE_ERROR;
                return false;
			}

			if ($cookie_length === false) {
                $this->configuration_error = ADMIN_COOKIE_LENGTH_ERROR;
                return false;
			}

			if ($cookie_domain === false) {
                $this->configuration_error = ADMIN_COOKIE_DOMAIN_ERROR;
                return false;
			}

			if ($max_tries === false) {
                $this->configuration_error = ADMIN_MAX_TRIES_ERROR;
                return false;
			}

			if ($user_regex === false) {
                $this->configuration_error = ADMIN_USER_REGEX_ERROR;
                return false;
			}

			if ($security_image === false) {
                $this->configuration_error = ADMIN_SECURITY_IMAGE_ERROR;
                return false;
			}

			if ($activation_type === false) {
                $this->configuration_error = ADMIN_ACTIVATION_TYPE_ERROR;
                return false;
			}

			if ($login_redirect === false) {
                $this->configuration_error = ADMIN_LOGIN_REDIRECT_ERROR;
                return false;
			}

			if ($logout_redirect === false) {
                $this->configuration_error = ADMIN_LOGOUT_REDIRECT_ERROR;
                return false;
			}

			if ($max_upload_size === false) {
                $this->configuration_error = ADMIN_MAX_UPLOAD_SIZE_CONFIG_ERROR;
                return false;
			}

			if ($auth_registration === false) {
                $this->configuration_error = ADMIN_AUTH_REGISTRATION_CONFIG_ERROR;
                return false;
			}

			// Go through the list and update the database
			foreach ($list as $value) {
                $this->qls->SQL->update('config',
                    array(
                        'value' => ${$value}
                    ),
                    array('name' =>
                        array(
                            '=',
                            $value
                        )
                    )
                );
			}

		    return true;
		}
		else {
            $this->configuration_error = ADMIN_CONFIG_NO_AUTH;
            return false;
        }
	}

	/**
	 * Creates and outputs pagination
	 * @return void
	 */
	function pagination() {
        // Get all users
        $users = $this->qls->SQL->query("SELECT * FROM `{$this->qls->config['sql_prefix']}users` ORDER BY `id` DESC");
        $num_rows = $this->qls->SQL->num_rows($users);
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $this->qls->Security->make_safe($_GET['page']) : 1;

        // Find some things about what we need to select
        $perpage = 20;
        $offset = ($page - 1) * $perpage;
        $num_pages = ceil($num_rows / $perpage);

		if ($num_pages == 1) {
		    echo PAGINATION_ONE_PAGE;
		}
		else {
            echo PAGINATION_START;
            // Last page
            $prev_page = $page - 1;

			if ($prev_page > 0) {
			    $prev_text = sprintf(PAGINATION_PREV_PAGE, $prev_page, FIRST_LABEL, PREV_LABEL);
			}
			else {
			    $prev_text = '';
			}

		    echo $prev_text;

            // Finds the 3 before and 3 after
            $low_num = $page - 3;
            $high_num = $page + 3;

			if ($low_num < 1) {
			    $low_num = 1;
			}

			// If it's greater it should be the total
			if ($high_num > $num_pages) {
			    $high_num = $num_pages;
			}

			// Loop through them
			for ($x = $low_num; $x < ($high_num + 1); $x++) {
				if ($x == $page) {
				    $bold = true;
				}
				else {
				    $bold = false;
				}

				if ($bold === true) {
				    printf(PAGINATION_LINK_BOLD, $x);
				}
				else {
				    printf(PAGINATION_LINK_NORMAL, $x);
				}
			}

            // Find the next page
            $next_page = $page + 1;

			if ($next_page < ($num_pages + 1)) {
			    $next_text = sprintf(PAGINATION_NEXT_LINK, $next_page, $num_pages, NEXT_LABEL, LAST_LABEL);
			}
			else {
			    $next_text = '';
			}

		    echo $next_text;
		}
	}
}