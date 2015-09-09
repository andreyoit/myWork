<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    User.class.php
* @start   July 15th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.1.5
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
 * Contains all user functions
 */
class User {

/**
 * @var object $qls - Will contain everything else
 */
var $qls;

	/**
	 * Construct class
	 * @param object $qls - Contains all other classes
	 * @return void
	 */
	function User(&$qls) {
	    $this->qls = &$qls;
	}

	/**
	 * Un-activates accounts that need un-activation
	 * @return void
	 */
	function check_activated_accounts() {
	    $groups_result = $this->qls->SQL->query("SELECT * FROM `{$this->qls->config['sql_prefix']}groups` WHERE `expiration_date`<>0");

		// Get the groups and put them into a variable
		while ($groups_row = $this->qls->SQL->fetch_array($groups_result)) {
            // Find the amount of seconds the admin entered
            $in_seconds = time() - ($groups_row['expiration_date'] * 86400);
            $users_result = $this->qls->SQL->query("SELECT * FROM `{$this->qls->config['sql_prefix']}users` WHERE `group_id`={$groups_row['id']} AND `activation_time`<{$in_seconds} AND `active`='yes'");

			while ($users_row = $this->qls->SQL->fetch_array($users_result)) {
                // Un-activate them
                $this->qls->SQL->update('users',
                    array(
                        'active' => 'no'
                    ),
                    array('id' =>
                        array(
                            '=',
                            $users_row['id']
                        )
                    )
                );
			}
		}
	}

	/**
	 * Checks the password code via the GET method
	 * @return bool
	 */
	function check_password_code() {
        $code = $this->qls->Security->make_safe($_GET['code']);
        $result = $this->qls->SQL->select('*',
            'password_requests',
            array('code' =>
                array(
                    '=',
                    $code
                )
            )
        );
        $row = $this->qls->SQL->fetch_array($result);

		if ($row['id'] != '' && $row['used'] != 1) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * This will actually change the password of the user
	 * @return bool
	 */
	function change_password() {
		// A little extra security
		if ($this->check_password_code()) {
		    $code = $this->qls->Security->make_safe($_GET['code']);

            // Retrieve the information from the database
            $result = $this->qls->SQL->select('*',
                'password_requests',
                array('code' =>
                    array(
                        '=',
                        $code
                    )
                )
            );
            $row = $this->qls->SQL->fetch_array($result);

            // Get the user's username from the database
            $users_result = $this->qls->SQL->select('*',
                'users',
                array('id' =>
                    array(
                        '=',
                        $row['user_id']
                    )
                )
            );
            $users_row = $this->qls->SQL->fetch_array($users_result);

            $new_password = (isset($_POST['new_password']) && $this->validate_password($_POST['new_password'])) ? $this->qls->Security->make_safe($_POST['new_password']) : false;
            $new_password_confirm = (isset($_POST['new_password_confirm']) && $_POST['new_password_confirm'] == $_POST['new_password']) ? true : false;

			if ($new_password !== false && $new_password_confirm !== false) {
                $password_hash = $this->generate_password_hash($new_password, $users_row['code']);

                // Update the database
                $this->qls->SQL->update('users',
                    array('password' => $password_hash),
                    array('id' =>
                        array(
                            '=',
                            $row['user_id']
                        )
                    )
                );
                $this->qls->SQL->update('password_requests',
                    array('used' => 1),
                    array('id' =>
                        array(
                            '=',
                            $row['id']
                        )
                    )
                );

			    return true;
			}
			else {
                $this->change_password_error = REGISTER_PASSWORD_ERROR;
                return false;
			}
		}
		else {
            $this->change_password_error = CHANGE_PASSWORD_INVALID_CODE;
            return false;
		}
	}

	/**
	 * This will generate a random code
	 * @return string of SHA1 hash
	 */
	function generate_random_code() {
        $hash[] = sha1(sha1(rand(1, 100)) . md5(rand(1, 100)));
        $hash[] = sha1(time() + time() . md5(time() + time()) . md5(rand()));
        $hash[] = sha1($hash[0] . $hash[1] . md5(time()));
        $hash[] = sha1($this->qls->config['user_regex'] . time());
        return sha1($hash[0] . $hash[0] . $hash[1] . $hash[2] . $hash[3] . time() . time() + time());
	}

	/**
	 * Sends the password change email to the user
	 * @return true on success, false on failure
	 */
	function send_password_email() {
	    $username = $this->qls->Security->make_safe($_POST['username']);

		if ($this->check_username_existence($username)) {
            $code = $this->generate_random_code();
            $this->qls->SQL->insert('password_requests',
                array(
                    'user_id',
                    'code',
                    'used',
                    'date'
                ),
                array(
                    $this->username_to_id($_POST['username']),
                    $code,
                    0,
                    time()
                )
            );
            $user_info = $this->fetch_user_info($username);

			// Generate the link
			if (substr($this->qls->config['cookie_domain'], 0, 1) == '.') {
				if (substr($this->qls->config['cookie_path'], -1) == '/') {
				    $change_link = "http://www{$this->qls->config['cookie_domain']}{$this->qls->config['cookie_path']}change_password.php?code={$code}";
				}
				else {
				    $change_link = "http://www{$this->qls->config['cookie_domain']}{$this->qls->config['cookie_path']}/change_password.php?code={$code}";
				}
			}
			else {
				if (substr($this->qls->config['cookie_path'], -1) == '/') {
				    $change_link = "http://{$this->qls->config['cookie_domain']}{$this->qls->config['cookie_path']}change_password.php?code={$code}";
				}
				else {
				    $change_link = "http://{$this->qls->config['cookie_domain']}{$this->qls->config['cookie_path']}/change_password.php?code={$code}";
				}
			}

		    $headers = "From: {$user_info['email']}\r\n";

			if (mail($user_info['email'], SEND_PASSWORD_SUBJECT, sprintf(SEND_PASSWORD_BODY, $change_link), $headers)) {
			    return true;
			}
			else {
			    $this->send_password_email_error = SEND_PASSWORD_MAIL_ERROR;
			    return false;
			}
		}
		else {
		    $this->send_password_email_error = SEND_PASSWORD_USERNAME_NON_EXISTANT;
		    return false;
		}
	}

	/**
	 * Transforms a username into an ID number
	 * @param string $username - The username to change
	 * @return int
	 */
	function username_to_id($username) {
	    $username = $this->qls->Security->make_safe($username);

		// Make sure it exists
		if ($this->check_username_existence($username)) {
            $result = $this->qls->SQL->select('id',
                'users',
                array('username' =>
                    array(
                        '=',
                        $username
                    )
                )
            );
            $row = $this->qls->SQL->fetch_array($result);
            return $row['id'];
		}
		else {
		    return 0;
		}
	}

	/**
	 * Transform a user ID into a username
	 * @param integer $user_id - The ID to change
	 * @return string
	 */
	function id_to_username($user_id) {
        $user_id = (is_numeric($user_id) && $user_id > 0) ? $user_id : 0;
        $result = $this->qls->SQL->select('username',
            'users',
            array('id' =>
                array(
                    '=',
                    $user_id
                )
            )
        );
        $row = $this->qls->SQL->fetch_array($result);
        return $row['username'];
	}

	/**
	 * Validates a password
	 * @param string $input - The input password
	 * @return bool
	 */
	function validate_password($input) {
		if (strlen($input) <= $this->qls->config['max_password'] &&
			strlen($input) >= $this->qls->config['min_password']) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * Validate the username according to the defined regex string.
	 * @param string $input - The input username
	 * @return bool
	 */
	function validate_username($input) {
		if (preg_match($this->qls->config['user_regex'], $input)) {
			if (strlen($input) <= $this->qls->config['max_username'] &&
				strlen($input) >= $this->qls->config['min_username']) {
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
	 * Validates the user that is logged in, not logging in a user
	 * @return bool
	 */
	function validate_login() {
		if ($this->qls->Session->find_session()) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * Fetch the user info of the user trying to login
	 * @param string $username - The username
	 * @return array|bool
	 */
	function fetch_user_info($username) {
		if ($this->validate_username($username)) {
            // Get info from the database
            $result = $this->qls->SQL->select('*',
                'users',
                array('username' =>
                    array(
                        '=',
                        $username
                    )
                )
            );
            $row = $this->qls->SQL->fetch_array($result);
            return $row;
		}
		else {
		    return false;
		}
	}

	/**
	 * Increases the number of tries by 1
	 * @param string  $username      - The username
	 * @param integer $current_tries - The user's current tries
	 * @return void
	 */
	function update_tries($username, $current_tries) {
		if ($this->validate_username($username)) {
            $this->qls->SQL->update('users',
                array(
                    'tries' => ($current_tries + 1),
                    'last_try' => time()
                ),
                array('username' =>
                    array(
                        '=',
                        $username
                    )
                )
            );
		}
	}

	/**
	 * Generates the password hash
	 * @param string $password  - The user's password
	 * @param string $user_code - The user's activation code
	 * @return string
	 */
	function generate_password_hash($password, $user_code) {
        $hash[] = md5($password);
        $hash[] = md5($password . $user_code);
        $hash[] = md5($password) . sha1($user_code . $password) . md5(md5($password));
        $hash[] = sha1($password . $user_code . $password);
        $hash[] = md5($hash[3] . $hash[0] . $hash[1] . $hash[2] . sha1($hash[3] . $hash[2]));
        $hash[] = sha1($hash[0] . $hash[1] . $hash[2] . $hash[3]) . md5($hash[4] . $hash[4]) . sha1($user_code);
        return sha1($hash[0] . $hash[1] . $hash[2] . $hash[3] . $hash[4] . $hash[5] . md5($user_code));
	}

	/**
	 * Compares an inputted password with the one in the database
	 * @param string $input_password - The input password
	 * @param string $real_password  - The user's real password
	 * @param string $user_code      - The user's activation code
	 * @return bool
	 */
	function compare_passwords($input_password, $real_password, $user_code) {
        // Generate the hash to compare them
        $input_hash = $this->generate_password_hash($input_password, $user_code);

		// Actually compare them
		if ($input_hash == $real_password) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * Tries to login the user
	 * @return bool
	 */
	function login_user() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $remember = $_POST['remember'];
        $user_info = $this->fetch_user_info($username);

		if ($user_info['id'] != '') {
			if ($user_info['tries'] < $this->qls->config['max_tries']) {
				if ($this->compare_passwords($password, $user_info['password'], $user_info['code'])) {
					if ($user_info['blocked'] == 'no') {
						// They need to be active
						if ($user_info['active'] == 'yes') {
							if ($remember == '1') {
							    $this->qls->Session->create_session($username, $password, $user_info['password'], true);
							}
							else {
							    $this->qls->Session->create_session($username, $password, $user_info['password'], false);
							}

						    return true;
						}
						else {
                            if ($this->qls->config['activation_type'] == 1) {
                                $this->login_error = LOGIN_NOT_ACTIVE_USER_CODE;
                            }
                            else {
                                $this->login_error = LOGIN_NOT_ACTIVE_ADMIN_CODE;
                            }

						    return false;
						}
					}
					else {
					    $this->update_tries($username, $user_info['tries']);
					    $this->login_error = LOGIN_USER_BLOCKED_CODE;
					    return false;
					}
				}
				else {
				    $this->update_tries($username, $user_info['tries']);
				    $this->login_error = LOGIN_PASSWORDS_NOT_MATCHED_CODE;
				    return false;
				}
			}
			else {
			    $this->login_error = LOGIN_NO_TRIES_CODE;
			    return false;
			}
		}
		else {
		    $this->login_error = LOGIN_USER_INFO_MISSING_CODE;
		    return false;
		}
	}

	/**
	 * Removes set logout cookies/sessions
	 * @returns void
	 */
	function logout_user() {
	    $session_names = array('user_id', 'user_time', 'user_unique');

		if (isset($_SESSION[$this->qls->config['cookie_prefix'] . 'user_unique'])) {
            $this->qls->SQL->delete('sessions',
                array('id' =>
                    array(
                        '=',
                        $_SESSION[$this->qls->config['cookie_prefix'] . 'user_unique']
                    )
                )
            );
		}

        // Remove all session information and unset the cookie
        $_SESSION = array();

		if (isset($_COOKIE[session_name()])) {
		    setcookie(session_name(), '', time() - 42000, '/');
		}

		if (isset($_COOKIE[$this->qls->config['cookie_prefix'] . 'user_id'])) {
			foreach ($session_names as $value) {
			    setcookie($this->qls->config['cookie_prefix'] . $value, 0, time() - 3600, $this->qls->config['cookie_path'], $this->qls->config['cookie_domain']);
			}
		}

	    $this->qls->redirect($this->qls->config['logout_redirect']);
	}

	/**
	 * Checks to see if that username already exists
	 * @param string $username - Username to check
	 * @return bool
	 */
	function check_username_existence($username) {
        if (empty($username)) {
            return false;
        }

		// Check username...
		if ($this->validate_username($username)) {
            $result = $this->qls->SQL->select('id',
                'users',
                array('username' =>
                    array(
                        '=',
                        $username
                    )
                )
            );
            $row = $this->qls->SQL->fetch_array($result);

			if ($row['id'] == '') {
			    return false;
			}
			else {
			    return true;
			}
		}
		else {
		    return true;
		}
	}

	/**
	 * Generates a new activation code
	 * @param string $username - The user's username
	 * @param string $password - The user's password
	 * @param string $email    - The user's email address
	 * @return string
	 */
	function generate_activation_code($username, $password, $email) {
        $hash[] = md5($username . $password . md5($email));
        $hash[] = sha1($hash[0] . $hash[0]) . md5(sha1(sha1($email) . sha1($password)) . md5($username));
        $hash[] = sha1(sha1(sha1(sha1(md5(md5('   	') . sha1(' 	'))) . sha1($password . $username))));
        $hash[] = sha1($hash[0] . $hash[1] . $hash[2]) . sha1($hash[2] . $hash[0] . $hash[1]);
        $hash[] = sha1($username);
        $hash[] = sha1($password);
        $hash[] = md5(md5($email) . md5($password));
        $hash_count = count($hash);

		for ($x = 0; $x < $hash_count; $x++) {
            $random_hash = rand(0, $hash_count);
            $hash[] = sha1($hash[$x]) . sha1($password) . sha1($hash[$random_hash] . $username);
		}

	    return sha1(sha1($hash[0] . $hash[1] . $hash[2] . $hash[3]) . sha1($hash[4] . $hash[5]) . md5($hash[6] . $hash[7] . $hash[8] . sha1($hash[9])) . $password . $email);
	}

	/**
	 * Inserts registration data into the database
	 * @param string $username - The user's username
	 * @param string $password - The user's password
	 * @param string $email    - The user's email address
	 * @return void
	 */
	function insert_registration_data($username, $password, $email) {
        // Generate activation code
        $generated_code = $this->generate_activation_code($username, $password, $email);

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
            'activation_time'
        );

        // All the values that go with the columns
        $values = array(
            $username,
            $this->generate_password_hash($password, $generated_code),
            $generated_code,
            'no',
            0,
            '',
            'no',
            0,
            0,
            $email,
            0
        );

		// Is activation required?
		if ($this->qls->config['activation_type'] == 0) {
            $values[3] = 'yes';
            $values[10] = time();
		}
		elseif ($this->qls->config['activation_type'] == 1) {
	    	$headers = "From: {$email}\r\n";
		    // Email stuff...

			if (substr($this->qls->config['cookie_domain'], 0, 1) == '.') {
				if (substr($this->qls->config['cookie_path'], -1) == '/') {
				    $activation_link = "http://www{$this->qls->config['cookie_domain']}{$this->qls->config['cookie_path']}activate.php?code={$generated_code}&username={$username}";
				}
				else {
				    $activation_link = "http://www{$this->qls->config['cookie_domain']}{$this->qls->config['cookie_path']}/activate.php?code={$generated_code}&username={$username}";
				}
			}
			else {
				if (substr($this->qls->config['cookie_path'], -1) == '/') {
				    $activation_link = "http://{$this->qls->config['cookie_domain']}{$this->qls->config['cookie_path']}activate.php?code={$generated_code}&username={$username}";
				}
				else {
				    $activation_link = "http://{$this->qls->config['cookie_domain']}{$this->qls->config['cookie_path']}/activate.php?code={$generated_code}&username={$username}";
				}
			}

		    @mail($email, ACTIVATION_SUBJECT, sprintf(ACTIVATION_BODY, $activation_link), $headers);
		}


	    $this->qls->SQL->insert('users', $columns, $values);

        // Check the invitation code
        $code = (isset($_GET['code']) && strlen($_GET['code']) == 40 && preg_match('/^[a-fA-F0-9]{40}$/', $_GET['code'])) ? $this->qls->Security->make_safe($_GET['code']) : false;
            if ($code !== false) {
            $this->qls->SQL->update('invitations',
                array('used' => 1),
                array('code' =>
                    array(
                        '=',
                        $code
                    )
                )
            );
		}
	}

	/**
	 * This will register a user
	 * @return bool
	 */
	function register_user() {
        $this->qls->Security->check_auth_registration();

        // Default security
        $security_check = false;

        /**
         * These next lines will retrieve the necessary fields. These include username,
         * password & confirmation, email & confirmation and possibly the security image.
         */
        $username = (isset($_POST['username']) && $this->validate_username($_POST['username'])) ? $this->qls->Security->make_safe($_POST['username']) : false;
        $password = (isset($_POST['password']) && $this->validate_password($_POST['password'])) ? $this->qls->Security->make_safe($_POST['password']) : false;
        $confirm_password = (isset($_POST['password_c']) && $this->qls->Security->make_safe($_POST['password_c']) == $password) ? true : false;
        $email = (isset($_POST['email']) && strlen($_POST['email']) > 6 && strlen($_POST['email']) < 256 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) ? $this->qls->Security->make_safe($_POST['email']) : false;
        $confirm_email = (isset($_POST['email_c']) && $_POST['email_c'] == $email) ? true : false;

		if ($this->qls->config['security_image'] == 'yes') {
            // The random id of the image
            $random_id = (isset($_POST['random_id']) && preg_match('/^[a-fA-F0-9]{40}$/', $_POST['random_id'])) ? $this->qls->Security->make_safe($_POST['random_id']) : false;

            // The security code entered by the user
            $security_code = (isset($_POST['security_code']) && preg_match('/[a-zA-Z1-9]{5,8}/', $_POST['security_code'])) ? $_POST['security_code'] : false;

			if ($this->qls->Security->check_security_image($random_id, $security_code)) {
			    $security_check = true;
			}
		}
		else {
		    $security_check = true;
		}

        $_SESSION[$this->qls->config['cookie_prefix'] . 'registration_username'] = $this->qls->Security->make_safe($_POST['username']);
        $_SESSION[$this->qls->config['cookie_prefix'] . 'registration_email'] = $this->qls->Security->make_safe($_POST['email']);
        $_SESSION[$this->qls->config['cookie_prefix'] . 'registration_email_confirm'] = $this->qls->Security->make_safe($_POST['email_c']);

		if ($username === false) {
		    $this->register_error = REGISTER_USERNAME_ERROR;
		    return false;
		}

		if ($this->check_username_existence($username)) {
		    $this->register_error = REGISTER_USERNAME_EXISTS;
		    return false;
		}

		if ($password === false || $confirm_password === false) {
		    $this->register_error = REGISTER_PASSWORD_ERROR;
		    return false;
		}

		if ($email === false || $confirm_email === false) {
		    $this->register_error = REGISTER_EMAIL_ERROR;
		    return false;
		}

		if ($security_check === false) {
		    $this->register_error = REGISTER_SECURITY_ERROR;
		    return false;
		}

        $this->insert_registration_data($username, $password, $email);
        return true;
	}

	/**
	 * Compare the code input by the user to the one in the database
	 * @param string $input    - The input code
	 * @param string $username - The username
	 * @return bool
	 */
	function compare_codes($input, $username) {
        $result = $this->qls->SQL->select('*',
            'users',
            array('username' =>
                array(
                    '=',
                    $username
                )
            )
        );
        $row = $this->qls->SQL->fetch_array($result);

		// Compare the codes
		if ($row['code'] == $input) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * Tries to activate a user
	 * @return bool
	 */
	function activate_user() {
        // validate activation code input and user id input
        $activation_code = (isset($_GET['code']) && preg_match('/[a-fA-F0-9]{40}/', $_GET['code'])) ? $this->qls->Security->make_safe($_GET['code']) : false;
        $username = (isset($_GET['username']) && $this->validate_username($_GET['username'])) ? $this->qls->Security->make_safe($_GET['username']) : false;

		if ($activation_code === false) {
            $this->activate_error = ACTIVATE_CODE_NOT_VALID;
		    return false;
		}

		if ($username === false) {
		    $this->activate_error = ACTIVATE_USERNAME_NOT_VALID;
		    return false;
		}

		// Compare the codes
		if ($this->compare_codes($activation_code, $username)) {
            $this->qls->SQL->update('users',
                array(
                    'active' => 'yes',
                    'activation_time' => time()
                ),
                array('username' =>
                    array(
                        '=',
                        $username
                    )
                )
            );

            return true;
		}
		else {
            $this->activate_error = ACTIVATE_CODE_NOT_MATCH;
            return false;
		}
	}
}