<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    Security.class.php
* @start   July 24th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.0.3
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
 * Contains all necessary security functions
 */
class Security {

/**
 * @var object $qls - Will contain everything else
 */
var $qls;

	/**
	 * Construct class and clean input
	 * @param object $qls - Contains all other classes
	 * @return void
	 */
	function Security(&$qls) {
		// Check register_globals
		if (@ini_get('register_globals') == 1 || strtoupper(@ini_get('register_globals')) == 'ON') {
		    die(REGISTER_GLOBALS_ON);
		}

	    $this->qls = &$qls;

		// Get rid of the slashes if it's turned on
		if (get_magic_quotes_gpc()) {
			// POST Method
			foreach ($_POST as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								if (is_array($value3)) {
									foreach ($value3 as $key4 => $value4) {
										// Can't go any deeper
										if (is_array($value4)) {
										    $_POST[$key][$key2][$key3][$key4] = $value4;
										}
										else {
										    $_POST[$key][$key2][$key3][$key4] = stripslashes($value4);
										}
									}
								}
								else {
								    $_POST[$key][$key2][$key3] = stripslashes($value3);
								}
							}
						}
						else {
						    $_POST[$key][$key2] = stripslashes($value2);
						}
					}
				}
				else {
				    $_POST[$key] = stripslashes($value);
				}
			}

			// GET Method
			foreach ($_GET as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								if (is_array($value3)) {
									foreach ($value3 as $key4 => $value4) {
										// Can't go any deeper
										if (is_array($value4)) {
										    $_GET[$key][$key2][$key3][$key4] = $value4;
										}
										else {
										    $_GET[$key][$key2][$key3][$key4] = stripslashes($value4);
										}
									}
								}
								else {
								    $_GET[$key][$key2][$key3] = stripslashes($value3);
								}
							}
						}
						else {
						    $_GET[$key][$key2] = stripslashes($value2);
						}
					}
				}
				else {
				    $_GET[$key] = stripslashes($value);
				}
			}

			// COOKIE Method
			foreach ($_COOKIE as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								if (is_array($value3)) {
									foreach ($value3 as $key4 => $value4) {
										// Can't go any deeper
										if (is_array($value4)) {
										    $_COOKIE[$key][$key2][$key3][$key4] = $value4;
										}
										else {
										    $_COOKIE[$key][$key2][$key3][$key4] = stripslashes($value4);
										}
									}
								}
								else {
								    $_COOKIE[$key][$key2][$key3] = stripslashes($value3);
								}
							}
						}
						else {
						    $_COOKIE[$key][$key2] = stripslashes($value2);
						}
					}
				}
				else {
				    $_COOKIE[$key] = stripslashes($value);
				}
			}
		}
	}

	/**
	 * Quotes strings for insertion in database
	 * @param    mixed $input - Can be an array of values or just a string
	 * @optional bool  $html  - Whether or not to use htmlentities()
	 * @return string of cleaned input
	 */
	function make_safe($input, $html = true) {
		if (is_array($input)) {
			/**
			 * Loops to a depth of 3, and it will add slashes to each of
			 * the $value{num} at each depth. If the htmlentities() is chosen
			 * via the $html variable, it will be used.
			 */
			foreach ($input as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								if (is_array($value3)) {
									foreach ($value3 as $key4 => $value4) {
										if ($html === false) {
										    $input[$key][$key2][$key3][$key4] = addslashes($value4);
										}
										else {
										    $input[$key][$key2][$key3][$key4] = htmlentities($value4, ENT_QUOTES);
										}
									}
								}
								else {
									if ($html === false) {
									    $input[$key][$key2][$key3] = addslashes($value3);
									}
									else {
									    $input[$key][$key2][$key3] = htmlentities($value3, ENT_QUOTES);
									}
								}
							}
						}
						else {
							if ($html === false) {
							    $input[$key][$key2] = addslashes($value2);
							}
							else {
							    $input[$key][$key2] = htmlentities($value2, ENT_QUOTES);
							}
						}
					}
				}
				else {
					if ($html === false) {
					    $input[$key] = addslashes($value);
					}
					else {
					    $input[$key] = htmlentities($value, ENT_QUOTES);
					}
				}
			}

		    return $input;
		}
		else {
			if ($html === false) {
			    return addslashes($input);
			}
			else {
			    return htmlentities($input, ENT_QUOTES);
			}
		}
	}

	/**
	 * Check the security image and user inputted code
	 * @param string $random_id - Random id of the image 
	 * @param string $input     - Input text from the user
	 * @return bool
	 */
	function check_security_image($random_id, $input) {
        // Get info from database
        $result = $this->qls->SQL->select('*',
            'security_image',
            array('random_id' =>
                array(
                    '=',
                    $random_id
                )
            )
        );
	    $row = $this->qls->SQL->fetch_array($result);

		// Is the text equal?
		if ($row['real_text'] == $input) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * Remove any old login attempts from the database
	 * @return void
	 */
	function remove_old_tries() {
        // Find the time minus 12 hours
        $time_minus_12_hours = time() - ((60 * 60) * 12);
        $this->qls->SQL->update('users',
            array(
                'tries' => 0,
                'last_try' => time()
            ),
            array('last_try' =>
                array(
                    '<',
                    $time_minus_12_hours
                )
            )
        );
	}

	/**
	 * Generates a random text string for the security image
	 * @return string
	 */
	function generate_text_string() {
	    // List of characters to include on security image
	    $characters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	    $num_chars = rand(5, 8);
        $real_text = '';

		for ($x = 0; $x < $num_chars; $x++) {
		    $real_text .= $characters[array_rand($characters)];
		}

	    return $real_text;
	}

	/**
	 * Removes old image records from the database (15 minutes)
	 * @return void
	 */
	function delete_old_images() {
        // Run the delete query
        $this->qls->SQL->delete('security_image',
            array('date' =>
                array(
                    '<',
                    time() - 900
                )
            )
        );
	}

	/**
	 * Generates a random id and inserts text and random id in the database
	 * @return the random id string or false on failure
	 */
	function generate_random_id() {
		// Do we even have to do it?
		if ($this->qls->config['security_image'] == 'yes') {
            $this->delete_old_images();

            $hash[] = md5(md5(time() + time()) . sha1(time()));
            $hash[] = sha1($hash[0] . $hash[0] . time());
            $hash[] = sha1(rand() . rand() . (time() * rand()) . rand(1, 100));
            $final = sha1($hash[0] . $hash[1] . $hash[2] . time());

            // Run insert query
            $this->qls->SQL->insert('security_image',
                array(
                    'random_id',
                    'real_text',
                    'date'
                ),
                array(
                    $final,
                    $this->generate_text_string(),
                    time()
                )
            );
		    return $final;
		}
		else {
		    return false;
		}
	}

	/**
	 * Checks to see whether the registration page is available
	 * @return void, but will kill the script if not allowed
	 */
	function check_auth_registration() {
		if ($this->qls->config['auth_registration'] == 0) {
            // See if the code is set
            $code = (isset($_GET['code']) && strlen($_GET['code']) == 40 && preg_match('/^[a-fA-F0-9]{40}$/', $_GET['code'])) ? $this->make_safe($_GET['code']) : false;
            $result = $this->qls->SQL->query("SELECT `used` FROM `{$this->qls->config['sql_prefix']}invitations` WHERE `code`='{$code}'");
            $row = $this->qls->SQL->fetch_array($result);

			if ($row['used'] == 1 || $row['used'] == '') {
			    die(REGISTER_CODE_INVALID);
			}
		}
	}

	/**
	 * Checks to see whether the user can access the 
	 * page and will update the hit counter.
	 * @param string $page_name - The page to check (must have extension)
	 * @return void
	 */
	function check_auth_page($page_name) {
        $page_name = $this->make_safe($page_name);
        $result = $this->qls->SQL->select('*',
            'pages',
            array('name' =>
                array(
                    '=',
                    $page_name
                )
            )
        );
        $row = $this->qls->SQL->fetch_array($result);
        $hash = sha1($row['id']);

		if ($this->qls->user_info['auth_' . $hash] == 0) {
		    die(NO_AUTHORIZATION);
		}

        // Update the hit counter
        $this->qls->SQL->update('pages',
            array('hits' => $row['hits'] + 1),
            array('id' =>
                array(
                    '=',
                    $row['id']
                )
            )
        );
	}

	/**
	 * This outputs a security image (no text can be output before it is called)
	 * @return void
	 */
	function security_image() {
	$ext_prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';

		// Is the GD extension installed?
		if (!extension_loaded('gd') && !@dl($ext_prefix . 'gd.' . PHP_SHLIB_SUFFIX) && !extension_loaded('gd2') && !@dl($ext_prefix . 'gd2.' . PHP_SHLIB_SUFFIX)) {
		    die(NO_GD);
		}

        // Send the header information
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Cache-Control: pre-check=0, post-check=0', false);
        header('Pragma: no-cache');
        header('Expires: Thursday, 21 June 2007 05:00:00 GMT');
        header('Content-Type: image/png', true);

        // Height and width of the image
        $height = rand(100, 150);
        $width = rand(300, 350);

	    $random_id = (isset($_GET['id']) && strlen($_GET['id']) == 40 && preg_match('/[a-fA-F0-9]{40}/', $_GET['id'])) ? $this->make_safe($_GET['id']) : '';

		if ($random_id == '') {
		    $text_string = 'Quadodo';
		}
		else {
            // Get the information from the database
            $result = $this->qls->SQL->select('*',
                'security_image',
                array('random_id' =>
                    array(
                        '=',
                        $random_id
                    )
                )
            );
		    $row = $this->qls->SQL->fetch_array($result);

			if ($row['real_text'] == '') {
			    $text_string = 'Quadodo';
			}
			else {
			    $text_string = $row['real_text'];
			}
		}

        // Create a true color image
        $image = imagecreatetruecolor($width, $height);

        // Allocate some colors
        $colors = array(
            imagecolorallocate($image, 255, 255, 255),
            imagecolorallocate($image, 255, 0, 0),
            imagecolorallocate($image, 0, 255, 0),
            imagecolorallocate($image, 0, 0, 255),
            imagecolorallocate($image, 255, 255, 0),
            imagecolorallocate($image, 255, 0, 255),
            imagecolorallocate($image, 0, 255, 255)
        );

		// Place those dots all over the place
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $height; $y++) {
			    // Totally random color
			    $random_color = imagecolorallocate($image, rand(rand(100, 120), 255), rand(rand(100, 120), 255), rand(rand(100, 120), 255));
			    imagesetpixel($image, $x, $y, $random_color);
			}
		}

        // Put some lines on there!
        $random_increment = array(rand(rand(4, 5), rand(5, 10)), rand(rand(5, 6), rand(7, 15)));

		for ($x = 0; $x < $width; $x++) {
			// If it's on the increment line, draw it
			if (($x % $random_increment[0]) == 0) {
			    imagesetthickness($image, rand(1, 2));
			    imageline($image, $x, 0, $x, $height, $colors[array_rand($colors)]);
			}
		}

		// Put some more lines
		for ($x = 0; $x < $height; $x++) {
			if (($x % $random_increment[1]) == 0) {
		    	imagesetthickness($image, rand(1, 2));
			    imageline($image, 0, $x, $width, $x, $colors[array_rand($colors)]);
			}
		}

		// Search through the fonts directory
		foreach (glob(dirname(__FILE__) . '/fonts/*.ttf') as $file_name) {
		    $fonts[] = $file_name;
		}

	    $font = $fonts[array_rand($fonts)];

		// Place random characters all over the place
		$chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '�', '�', '�');
		for ($x = 0; $x < rand(200, 250); $x++) {
            $font_size = rand(5, 10);
            $font_angle = rand(-30, 40);
            $font_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            $random_char = $chars[array_rand($chars)];
            $text = imagettfbbox($font_size, $font_angle, $font, $random_char);
            $text_width = abs($text[2] - $text[0]);
            $text_height = abs($text[5] - $text[3]);
            $text_x = rand(0, $width);
            $text_y = rand(0, $height);
            imagettftext($image, $font_size, $font_angle, $text_x, $text_y, $font_color, $font, $random_char);
		}

		// Put ellipses everywhere! :O
		for ($x = 0; $x < rand(10, 20); $x++) {
            $ellipse_height = rand(0, ($height / 2));
            $ellipse_width = rand(0, ($width / 2));
            $ellipse_y = rand($ellipse_height, $height);
            $ellipse_x = rand($ellipse_width, $width);
            $ellipse_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetthickness($image, rand(1, 10));
            imageellipse($image, $ellipse_x, $ellipse_y, $ellipse_width, $ellipse_height, $ellipse_color);
		}

	    // Generate the text and randomly position it
        $font_color = imagecolorallocate($image, rand(0, 10), rand(0, 10), rand(0, 10));
        $font_size = rand(30, 40);
        $font_angle = rand(-6, 6);
        $text = imagettfbbox($font_size, $font_angle, $font, $text_string);
        $text_width = abs($text[2] - $text[0]);
        $text_height = abs($text[5] - $text[3]);
        $text_x = ($width / 2) - ($text_width / 2) + rand(-10, 10);

		if ($font_angle > 4 || $font_angle < -4) {
		    $text_y = (($height / 2) - ($text_height / 2));
		}
		else {
		    $text_y = (($height / 1.5) - ($text_height / 2));
		}

        // Place text on image, output image and then destroy the image
        imagettftext($image, $font_size, $font_angle, $text_x, $text_y, $font_color, $font, $text_string);
        imagepng($image);
        imagedestroy($image);
	}
}