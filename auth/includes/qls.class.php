<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    qls.class.php
* @start   July 18th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.1.3
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
 * Contains everything needed to run the system
 */
class qls {

	/**
	 * Construct main class and grab all other classes
	 * @param string $current_language - The current language
	 * @return void, but will output error if found
	 */
	function qls($current_language) {
        // Get current language constants
        require_once($current_language . '.lang.php');

        require_once('Security.class.php');
        $this->Security = new Security($this);

        require_once('SQL.class.php');
        $this->SQL = new SQL($this);

        // Get configuration information and assign to $config
        $result = $this->SQL->query("SELECT * FROM `{$this->config['sql_prefix']}config`");

		while ($row = $this->SQL->fetch_array($result)) {
		    $this->config[$row['name']] = $row['value'];
		}

        $this->Security->remove_old_tries();

        require_once('User.class.php');
        $this->User = new User($this);

        require_once('Session.class.php');
        $this->Session = new Session($this);

        require_once('Admin.class.php');
        $this->Admin = new Admin($this);

        require_once('Group.class.php');
        $this->Group = new Group($this);

        require_once('Upload.class.php');
        $this->Upload = new Upload($this);

        $this->main_directory = str_replace('/includes', '', dirname(__FILE__));

        // Make sure their account isn't outdated
        $this->User->check_activated_accounts();

        // See if someone is logged in 0_0
        $this->User->validate_login();

        // Clear any old sessions used by the system
        $this->Session->clear_old_sessions();

        // Set the users last action
        if ($this->user_info['username'] != '') {
            $this->SQL->update('users',
                array('last_action' => time()),
                array('id' =>
                    array(
                        '=',
                        $this->user_info['id']
                    )
                )
            );
	    }

		if ($this->user_info['blocked'] == 'yes') {
		    die(BLOCKED_ERROR);
		}
	}

	/**
	 * Reference to the function inside User.class.php
	 * @param string $username - The username
	 * @return the ID of the username in the form of an integer
	 */
	function username_to_id($username) {
	    return $this->User->username_to_id($username);
	}

	/**
	 * Reference to the function inside User.class.php
	 * @param integer $user_id - The user ID
	 * @return the username (string)
	 */
	function id_to_username($user_id) {
	    return $this->User->id_to_username($user_id);
	}

	/**
	 * Translates a page name into a ID from the database
	 * @param string $page_name - The name of the page
	 * @return int
	 */
	function page_name_to_id($page_name) {
        $page_name = $this->Security->make_safe($page_name);
        $result = $this->SQL->select('id',
            'pages',
            array('name' =>
                array(
                    '=',
                    $page_name
                )
            )
        );
        $row = $this->SQL->fetch_array($result);
        return $row['id'];
	}

	/**
	 * Translates a page ID into a name from the database
	 * @param integer $page_id - The ID of the page
	 * @return String
	 */
	function page_id_to_name($page_id) {
        $page_id = $this->Security->make_safe($page_id);
        $result = $this->SQL->select('name',
            'pages',
            array('id' =>
                array(
                    '=',
                    $page_id
                )
            )
        );
        $row = $this->SQL->fetch_array($result);
        return $row['name'];
	}

	/**
	 * Translates a group name into an ID from the database
	 * @param string $group_name - The group name
	 * @return int
	 */
	function group_name_to_id($group_name) {
        $group_name = $this->Security->make_safe($group_name);
        $result = $this->SQL->select('id',
            'groups',
            array('name' =>
                array(
                    '=',
                    $group_name
                )
            )
        );
        $row = $this->SQL->fetch_array($result);
        return $row['id'];
	}

	/**
	 * Translate a group ID into a name from the database
	 * @param integer $group_id - The group ID
	 * @return String
	 */
	function group_id_to_name($group_id) {
        $group_id = $this->Security->make_safe($group_id);
        $result = $this->SQL->select('name',
            'groups',
            array('id' =>
                array(
                    '=',
                    $group_id
                )
            )
        );
        $row = $this->SQL->fetch_array($result);
        return $row['name'];
	}

	/**
	 * Translates a mask name to an ID from the database
	 * @param string $mask_name - The mask name
	 * @return int
	 */
	function mask_name_to_id($mask_name) {
        $mask_name = $this->Security->make_safe($mask_name);
        $result = $this->SQL->select('id',
            'masks',
            array('name' =>
                array(
                    '=',
                    $mask_name
                )
            )
        );
        $row = $this->SQL->fetch_array($result);
        return $row['id'];
	}

	/**
	 * Translates a mask ID to a name from the database
	 * @param integer $mask_id - The mask ID
	 * @return string
	 */
	function mask_id_to_name($mask_id) {
        $mask_id = $this->Security->make_safe($mask_id);
        $result = $this->SQL->select('name',
            'masks',
            array('id' =>
                array(
                    '=',
                    $mask_id
                )
            )
        );
        $row = $this->SQL->fetch_array($result);
        return $row['name'];
	}

	/**
	 * Opens a file and reads from it
	 * @param string $file_name - The name of the file
	 * @return string
	 */
	function fetch_file_data($file_name) {
        $file_location = $this->main_directory . '/' . $file_name;

        // If it has a 0 file size it won't be readable
        $file_size = filesize($file_location);
        if ($file_size == 0) {
            return '';
        }
        else {
            if (!$file_handle = fopen($file_location, 'r')) {
                $this->file_data_error = FILE_NOT_OPENABLE;
                return false;
            }
            else {
                if (!$file_data = fread($file_handle, filesize($file_location))) {
                    $this->file_data_error = FILE_NOT_READABLE;
                    return false;
                }
                else {
                    fclose($file_handle);
                    return $file_data;
                }
            }
        }
	}

	/**
	 * Retrieves the current page hits
	 * @param string $page_name - The page name
	 * @return int
	 */
	function hits($page_name) {
        $page_name = $this->Security->make_safe($page_name);
        $result = $this->SQL->select('*',
            'pages',
            array('name' =>
                array(
                    '=',
                    $page_name
                )
            )
        );
        $row = $this->SQL->fetch_array($result);
        return $row['hits'];
	}

	/**
	 * This will generate the activation link using the cookie information
	 * @param string $generated_code - The code they need
	 * @param string $username       - The user's username
	 * @return string
	 */
	function generate_activation_link($generated_code, $username) {
		// See if the domain is prepended with a dot
		if (substr($this->config['cookie_domain'], 0, 1) == '.') {
			// Does it have a / at the end?
			if (substr($this->config['cookie_path'], -1) == '/') {
			    $activation_link = "http://www{$this->config['cookie_domain']}{$this->config['cookie_path']}activate.php?code={$generated_code}&username={$username}";
			}
			else {
			    $activation_link = "http://www{$this->config['cookie_domain']}{$this->config['cookie_path']}/activate.php?code={$generated_code}&username={$username}";
			}
		}
		else {
			// Does it have a / at the end?
			if (substr($this->config['cookie_path'], -1) == '/') {
			    $activation_link = "http://{$this->config['cookie_domain']}{$this->config['cookie_path']}activate.php?code={$generated_code}&username={$username}";
			}
			else {
			    $activation_link = "http://{$this->config['cookie_domain']}{$this->config['cookie_path']}/activate.php?code={$generated_code}&username={$username}";
			}
		}

	    return $activation_link;
	}

	/**
	 * Redirects a user to another page
	 * @param string $url - The new URL to go to
	 * @return void
	 */
	function redirect($url) {
		switch ($this->config['redirect_type']) {
			default:
                header('Location: ' . $url);
                exit;
			break;
			case 2:
			    echo <<<META
<html><head><meta http-equiv="Refresh" content="0;URL={$url}" /></head><body></body></html>
META;
			    break;
			case 3:
			    echo <<<SCRIPT
<html><body><script>location="{$url}";</script></body></html>
SCRIPT;
			    break;
		}
	}

	/**
	 * Grabs all the users that are currently surfing and their info
	 * @return array
	 */
	function online_users() {
        // $five_minutes_ago can be changed if you want it farther back
        $five_minutes_ago = time() - 300;
        $result = $this->SQL->select('*',
            'users',
            array('last_action' =>
                array(
                    '>',
                    $five_minutes_ago
                )
            )
        );

        $users = array();

		while ($row = $this->SQL->fetch_assoc($result)) {
		    $users[] = $row;
		}

	    return $users;
	}
	
	/**
	 * Outputs the current online users
	 * @return void
	 */
	function output_online_users() {
	    $users = $this->online_users();

		if (count($users) == 0) {
		    echo ' ---- ';
		}
		else {
		    $count = 0;
            $string = '';

			foreach ($users as $information) {
                $prepared_output = str_ireplace('{username}', $information['username'], stripslashes($this->config['online_users_format']));
                $prepared_output = str_ireplace('{id}', $information['id'], $prepared_output);

				if ($count == 0) {
				    $string = $prepared_output;
				}
				else {
				    $string .= stripslashes($this->config['online_users_separator']) . $prepared_output;
				}

			    $count++;
			}

		    echo $string;
		}
	}
}