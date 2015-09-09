<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    Group.class.php
* @start   October 27th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.0.1
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
 * Contains all the functions needed to run the group control panel
 */
class Group {

/**
 * @var object $qls - Contains everything else
 */
var $qls;

    /**
     * Constructs the class
     * @param $qls
     * @return void
     */
	function Group(&$qls) {
	    $this->qls = &$qls;
	}

	/**
	 * Gets information about a defined group
	 * @param integer $group_id - The ID of the group
	 * @return array containing the group information
	 */
	function fetch_group_info($group_id) {
        $group_id = $this->qls->Security->make_safe($group_id);
        $result = $this->qls->SQL->select('*',
            'groups',
            array('id' =>
                array(
                    '=',
                    $group_id
                )
            )
        );
        $row = $this->qls->SQL->fetch_array($result);
        return $row;
	}

	/**
	 * Removes a user from the group
	 * @return true on success, false on failure
	 */
	function remove_user() {
	    $group_info = $this->fetch_group_info($_GET['id']);
		if ($group_info['leader'] == $this->qls->user_info['id'] || ($this->qls->user_info['auth_admin_add_group'] == 1 && $this->qls->user_info['auth_admin_list_groups'] == 1 && $this->qls->user_info['auth_admin_remove_group'] == 1 && $this->qls->user_info['auth_admin_edit_group'] == 1) || $this->qls->user_info['id'] == 1) {
			// Which method are we using
			if (isset($_GET['user_id'])) {
                $user_id = $this->qls->Security->make_safe($_GET['user_id']);
                $username = $this->qls->id_to_username($_GET['user_id']);
			}
			else {
                $user_id = $this->qls->username_to_id($_GET['username']);
                $username = $this->qls->Security->make_safe($_GET['username']);
			}

			// Can't remove themselves :(
			if ($user_id != $this->qls->user_info['id']) {
                $this->qls->SQL->update('users',
                    array('group_id' => 2),
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
                $this->remove_user_error = GROUP_CANT_REMOVE_SELF;
                return false;
			}
		}
		else {
            $this->remove_user_error = GROUPCP_NO_AUTH;
            return false;
		}
	}

	/**
	 * Adds a user to the group
	 * @return true on success, false on failure
	 */
	function add_user() {
	    $group_info = $this->fetch_group_info($_GET['id']);
		if ($group_info['leader'] == $this->qls->user_info['id'] || ($this->qls->user_info['auth_admin_add_group'] == 1 && $this->qls->user_info['auth_admin_list_groups'] == 1 && $this->qls->user_info['auth_admin_remove_group'] == 1 && $this->qls->user_info['auth_admin_edit_group'] == 1) || $this->qls->user_info['id'] == 1) {
			// Which method are we using
			if (isset($_GET['user_id'])) {
                $user_id = $this->qls->Security->make_safe($_GET['user_id']);
                $username = $this->qls->id_to_username($_GET['user_id']);
			}
			else {
                $user_id = $this->qls->username_to_id($_GET['username']);
                $username = $this->qls->Security->make_safe($_GET['username']);
			}

            // Get the user info
            $user_info = $this->qls->User->fetch_user_info($username);

            // Check if they lead any groups
            $result = $this->qls->SQL->query("SELECT `id`,`name` FROM `{$this->qls->config['sql_prefix']}groups` WHERE `leader`='{$user_id}'");
            $num_rows = $this->qls->SQL->num_rows($result);

			// We can't add the 1st user, any administrator or if they lead a group
            if ($user_id != 1 && $num_rows == 0 && $user_info['group_id'] != 1) {
                $this->qls->SQL->update('users',
                    array('group_id' => $group_info['id']),
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
                $this->add_user_error = GROUPCP_NO_AUTH;
                return false;
			}
		}
		else {
            $this->add_user_error = GROUPCP_NO_AUTH;
            return false;
		}
	}

	/**
	 * This will join a user to a public group
	 * @return true on success, false on failure
	 */
	function join_group() {
	    $group_info = $this->fetch_group_info($_GET['group_id']);

		if ($group_info['is_public'] == 1) {
			// They can't leave the administrators group by themselves
			if ($this->qls->user_info['group_id'] != 1 && $this->qls->user_info['id'] != 1) {
                $this->qls->SQL->update('users',
                    array('group_id' => $group_info['id']),
                    array('id' =>
                        array(
                            '=',
                            $this->qls->user_info['id']
                        )
                    )
                );
                $this->qls->user_info['group_id'] = $group_info['id'];
                return true;
			}
			else {
                $this->join_group_error = GROUP_CANT_JOIN;
                return false;
			}
		}
		else {
            $this->join_group_error = GROUP_NOT_PUBLIC;
            return false;
		}
	}

	/**
	 * This will let a user leave a group and go back to the default
	 * @return true on success, false on failure
	 */
	function leave_group() {
		if ($this->qls->user_info['group_id'] != 1 && $this->qls->user_info['id'] != 1) {
            $this->qls->SQL->update('users',
                array('group_id' => 2),
                array('id' =>
                    array(
                        '=',
                        $this->qls->user_info['id']
                    )
                )
            );
            $this->qls->user_info['group_id'] = 2;
            return true;
		}
		else {
            $this->join_group_error = GROUP_CANT_JOIN;
            return false;
		}
	}

	/**
	 * Creates and outputs pagination
	 * @return void
	 */
	function pagination() {
        $group_info = $this->fetch_group_info($_GET['id']);
        $area = htmlentities(strip_tags($_GET['area']));
        // Get the users
		if ($_GET['area'] == 'group') {
		    $users = $this->qls->SQL->query("SELECT * FROM `{$this->qls->config['sql_prefix']}users` WHERE `group_id`={$group_info['id']} ORDER BY `id` DESC");
		}
		else {
		    $users = $this->qls->SQL->query("SELECT * FROM `{$this->qls->config['sql_prefix']}users` WHERE `group_id`<>{$group_info['id']} ORDER BY `id` DESC");
		}

        $num_rows = $this->qls->SQL->num_rows($users);
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $this->qls->Security->make_safe($_GET['page']) : 1;

        // Find some things about what we need to select
        $perpage = 20;
        $offset = ($page - 1) * $perpage;
        $num_pages = ceil($num_rows / $perpage);

		if ($num_pages == 1) {
		    printf(PAGINATION_GROUP_ONE_PAGE, $area);
		}
		else {
            echo PAGINATION_GROUP_START;
            // Last page
            $prev_page = $page - 1;

			if ($prev_page > 0) {
			    $prev_text = sprintf(PAGINATION_GROUP_PREV_PAGE, $prev_page, FIRST_LABEL, PREV_LABEL, $area);
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
				    printf(PAGINATION_GROUP_LINK_BOLD, $x, $area);
				}
				else {
				    printf(PAGINATION_GROUP_LINK_NORMAL, $x, $area);
				}
			}

            // Find the next page
            $next_page = $page + 1;

			if ($next_page < ($num_pages + 1)) {
			    $next_text = sprintf(PAGINATION_GROUP_NEXT_LINK, $next_page, $num_pages, NEXT_LABEL, LAST_LABEL, $area);
			}
			else {
			    $next_text = '';
			}

		    echo $next_text;
		}
	}
}
