<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    Upload.class.php
* @start   October 10th, 2007
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
 * Contains everything necessary to upload a file
 */
class Upload {

/**
 * @var object $qls - Will contain everything else
 */
var $qls;

	/**
	 * Constructs the class
	 * @param object $qls - Reference to the rest of the program
	 * @return void
	 */
	function Upload(&$qls) {
	    $this->qls = &$qls;
	}

	/**
	 * Checks existence of file
	 * @return bool
	 */
	function validate_existence() {
		if (!file_exists($this->qls->main_directory . '/' . trim($_FILES['upload']['name']))) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * Validates the size
	 * @return bool
	 */
	function validate_size() {
		if ($_FILES['upload']['size'] <= $this->qls->config['max_upload_size']) {
		    return true;
		}
		else {
		    return false;
		}
	}

	/**
	 * Validates the extension
	 * @return bool
	 */
	function validate_extension() {
	    $extension = strtolower(substr($_FILES['upload']['name'], -4));

		if ($extension == '.php') {
	    	return true;
		}
		else {
		    return false;
		}
	}

    /**
     * Validates the file being uploaded
     * @internal param string $file_name - The temporary uploaded file to check
     * @return bool
     */
	function upload_file() {
        $temporary_file_location = $_FILES['upload']['tmp_name'];
        $new_file_location = $this->qls->main_directory . '/' . trim($_FILES['upload']['name']);

		if ($this->validate_existence() && $this->validate_size() && $this->validate_extension()) {
			// Is it an uploaded file? If so move it to the proper directory
			if (is_uploaded_file($temporary_file_location)) {
				if (move_uploaded_file($temporary_file_location, $new_file_location)) {
                    return true;
				}
				else {
                    $this->qls->Admin->add_page_error = FILE_NOT_MOVED;
                    return false;
				}
			}
			else {
                $this->qls->Admin->add_page_error = FILE_NOT_UPLOADED;
                return false;
			}
		}
		else {
            $this->qls->Admin->add_page_error = FILE_EXISTS_SIZE_EXTENSION;
            return false;
		}
	}
}