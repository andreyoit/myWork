<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    Session.class.php
* @start   July 18th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.2.1
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
 * Contains all necessary session functions
 */
class Session {

/**
 * @var object $qls - Will contain everything else
 */
var $qls;

	/**
	 * Construct the class
	 * @param object $qls - Contains all other classes
	 */
	function Session(&$qls) {
	    $this->qls = &$qls;
	}

	/**
	 * Removes any sessions that haven't been accessed in the defined time
	 * should be around 2 weeks
	 * @return void but will output error if found
	 */
	function clear_old_sessions() {
        $time_minus_defined = time() - $this->qls->config['cookie_length'];
        $this->qls->SQL->delete('sessions',
            array('time' =>
                array(
                    '<',
                    $time_minus_defined
                )
            )
        );
	}

	/**
	 * Generates a new unique id
	 * @param string $password  - The user's password
	 * @param string $user_code - The user's activation code
	 * @return string
	 */
	function generate_unique_id($password, $user_code) {
        $hash[] = md5(uniqid(rand(), true));
        $hash[] = sha1(uniqid(rand(), true)) . $hash[0] . md5($user_code);
        $hash[] = sha1($password . $hash[0] . $hash[1] . md5(sha1($user_code) . sha1($user_code))) . sha1($password);
        $hash[] = md5($hash[0] . $hash[1] . $hash[2]) . sha1($hash[0] . $hash[1] . $hash[0]);
        return sha1($hash[0] . $hash[1]. $hash[2] . $hash[3] . md5($user_code . $password));
	}

	/**
	 * Generates a unique value
	 * @param string  $password  - The user's password
	 * @param string  $user_code - The user's activation code
	 * @param string  $unique_id - The unique id previously generated
	 * @param integer $time      - The UNIX timestamp of the session
	 * @return string
	 */
	function generate_unique_value($password, $user_code, $unique_id, $time) {
        $hash[] = sha1($unique_id . $password . $user_code) . sha1(time() * 2 + $time);
        $hash[] = md5($password . $user_code . $hash[0] . time() . $time);
        $hash[] = sha1($hash[0] . $hash[1] . $user_code . sha1($password));
        return sha1($hash[0] . $hash[1] . $hash[2] . sha1($password . $user_code . $time . $hash[1]));
	}

    /**
     * Creates a new session
     * @param    string $username - The user's username
     * @param    string $password - The user's password
     * @param    string $user_code - The user's activation code
     * @param bool $remember
     * @optional boolean $remember  - Use cookies or sessions?
     * @return void
     */
	function create_session($username, $password, $user_code, $remember = false) {
        $time = time();

        // Get user id for cookie/session
        $user_id = $this->qls->SQL->select('id',
            'users',
            array('username' =>
                array(
                    '=',
                    $username
                )
            )
        );
        $user_id = $this->qls->SQL->fetch_array($user_id);
        // Generate the unique id
        $unique_id = $this->generate_unique_id($password, $user_code);

        // Generate unique value to go in users table and sessions table
        $value = $this->generate_unique_value($password, $user_code, $unique_id, $time);
        $sha1_time = sha1($time);

        // Insert new session info into database
        $this->qls->SQL->insert('sessions',
            array(
                'id',
                'value',
                'time'
            ),
            array(
                $unique_id,
                $value,
                $time
            )
        );
        // Update users table
        $this->qls->SQL->update('users',
            array(
                'last_session' => $value,
                'last_login' => time()
            ),
            array('username' =>
                array(
                    '=',
                    $username
                )
            )
        );
        $this->qls->SQL->transaction('COMMIT');

		if ($remember === true) {
		    // Set the three cookies
            setcookie($this->qls->config['cookie_prefix'] . 'user_id', $user_id['id'], time() + $this->qls->config['cookie_length'], $this->qls->config['cookie_path'], $this->qls->config['cookie_domain']);
            setcookie($this->qls->config['cookie_prefix'] . 'user_time', sha1($time), time() + $this->qls->config['cookie_length'], $this->qls->config['cookie_path'], $this->qls->config['cookie_domain']);
            setcookie($this->qls->config['cookie_prefix'] . 'user_unique', $unique_id, time() + $this->qls->config['cookie_length'], $this->qls->config['cookie_path'], $this->qls->config['cookie_domain']);
		}

        // Set the three sessions
        $_SESSION[$this->qls->config['cookie_prefix'] . 'user_id'] = $user_id['id'];
        $_SESSION[$this->qls->config['cookie_prefix'] . 'user_time'] = sha1($time);
        $_SESSION[$this->qls->config['cookie_prefix'] . 'user_unique'] = $unique_id;
	}

	/**
	 * This will fetch session info from the database
	 * @param array $information - Contains cookie information
	 * @return true on success, false on failure
	 */
	function fetch_session($information) {
        /**
         * Get the user's last session code. Will be stored
         * in $user_session['last_session']
         */
        $user_session = $this->qls->SQL->select('last_session',
            'users',
            array('id' =>
                array(
                    '=',
                    $information[0]
                )
            )
        );
        $user_session = $this->qls->SQL->fetch_array($user_session);

        /**
         * Grabs the info from the sessions table.
         * Basically verifying the session.
         */
        $session_info = $this->qls->SQL->query("SELECT * FROM `{$this->qls->config['sql_prefix']}sessions` WHERE `id`='{$information[2]}' AND `value`='{$user_session['last_session']}'");
        $session_info = $this->qls->SQL->fetch_array($session_info);

		// Did it return a blank id? (not existent)
		if ($session_info['id'] != '') {
			// Are the times equal?
			if (sha1($session_info['time']) == $information[1]) {
			    return true;
			}
			else {
			    return false;
			}
		}
		else {
		    return false;
		}
	}

	/**
	 * This will validate a user cookie/session
	 * @param array $information - Contains cookie information
	 * @return bool
	 */
	function validate_session($information) {

        // $information[0] is the user_id (int)
        // $information[1] is the time (SHA1)
        // $information[2] is the unique_id (SHA1)

		// Has to be an array, or no admittance!
		if (is_array($information)) {
			// Make sure they are SHA-1 hashes
			if (strlen($information[1]) == 40 
				&& strlen($information[2]) == 40
				&& preg_match('/^[a-fA-F0-9]{40}$/', $information[1])
				&& preg_match('/^[a-fA-F0-9]{40}$/', $information[2])) {

				// Validate the session while fetching it
				if ($this->fetch_session($information)) {
                    // Get all the user information
                    $result = $this->qls->SQL->select('*',
                        'users',
                        array('id' =>
                            array(
                                '=',
                                $information[0]
                            )
                        )
                    );

                    $row = $this->qls->SQL->fetch_array($result);

                    // We need to update the session information
                    $new_time = time();
                    $new_time_hash = sha1($new_time);

                    if (isset($_COOKIE[$this->qls->config['cookie_prefix'] . 'user_time'])) {
                        setcookie($this->qls->config['cookie_prefix'] . 'user_time', $new_time_hash, time() + $this->qls->config['cookie_length'], $this->qls->config['cookie_path'], $this->qls->config['cookie_domain']);
                    }

                    $_SESSION[$this->qls->config['cookie_prefix'] . 'user_time'] = $new_time_hash;

                    // Update the session time
                    $this->qls->SQL->update('sessions',
                        array('time' => $new_time),
                        array('id' =>
                            array(
                                '=',
                                $information[2]
                            )
                        )
                    );


					// Loop through and add to $user_info
					foreach ($row as $key => $value) {
					    $this->qls->user_info[$key] = stripslashes($value);
					}

					// These lines will retrieve the users permissions
					if ($this->qls->user_info['mask_id'] == 0) {
                        // The user is using the group mask
                        $group_mask = $this->qls->SQL->select('*',
                            'groups',
                            array('id' =>
                                array(
                                    '=',
                                    $this->qls->user_info['group_id']
                                )
                            )
                        );
                        $group_mask = $this->qls->SQL->fetch_array($group_mask);
                        $user_permissions = $this->qls->SQL->select('*',
                            'masks',
                            array('id' =>
                                array(
                                    '=',
                                    $group_mask['mask_id']
                                )
                            )
                        );
                        $user_permissions = $this->qls->SQL->fetch_array($user_permissions);

						foreach ($user_permissions as $key => $value) {
							if (!array_key_exists($key, $this->qls->user_info)) {
							    $this->qls->user_info[$key] = $value;
							}
						}
					}
					else {
                        // The user has their own mask assigned to them
                        $user_permissions = $this->qls->SQL->select('*',
                            'masks',
                            array('id' =>
                                array(
                                    '=',
                                    $this->qls->user_info['mask_id']
                                )
                            )
                        );

					    $user_permissions = $this->qls->SQL->fetch_array($user_permissions);

						foreach ($user_permissions as $key => $value) {
							if (!array_key_exists($key, $this->qls->user_info)) {
							    $this->qls->user_info[$key] = $value;
							}
						}
					}

				    return true;
				}
				else {
				    return false;
				}
			}
			else {
			    return false;
			}
		}
		else {
		    return false;
		}
	}

	/**
	 * This will grab a user session
	 * @return bool
	 */
	function find_session() {
		/**
		 * Is the user using sessions or nothing?
		 * Make sure the {cookie_prefix}user_id is numeric
		 */
		if (isset($_SESSION[$this->qls->config['cookie_prefix'] . 'user_id'])
			&& is_numeric($_SESSION[$this->qls->config['cookie_prefix'] . 'user_id'])) {

            $information = array(
                $_SESSION[$this->qls->config['cookie_prefix'] . 'user_id'],
                $_SESSION[$this->qls->config['cookie_prefix'] . 'user_time'],
                $_SESSION[$this->qls->config['cookie_prefix'] . 'user_unique']
            );

            return $this->validate_session($information);
		}
		elseif (isset($_COOKIE[$this->qls->config['cookie_prefix'] . 'user_id'])
			&& is_numeric($_COOKIE[$this->qls->config['cookie_prefix'] . 'user_id'])) {

            $information = array(
                $_COOKIE[$this->qls->config['cookie_prefix'] . 'user_id'],
                $_COOKIE[$this->qls->config['cookie_prefix'] . 'user_time'],
                $_COOKIE[$this->qls->config['cookie_prefix'] . 'user_unique']
            );

            return $this->validate_session($information);
		}
		else {
            // Cause the empty error
            return $this->validate_session('');
		}
	}
}
?>