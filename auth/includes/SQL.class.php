<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    SQL.class.php
* @start   July 18th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.0.5
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
 * Acts as an interface for the sub classes
 */
class SQL {

/**
 * @var object $qls - Will contain everything else
 */
var $qls;

	/**
	 * Constructs the class
	 * @param object $qls - Contains all the other classes
	 * @return void but will output error if found
	 */
	function SQL(&$qls) {
	    $this->qls = &$qls;

        require_once('database_info.php');
        $this->database_type = $database_type;

		/**
		 * These security functions are in here because of the file
		 * included above. I'll have to change it in later versions 0.o
		 */
		if (!defined('SYSTEM_INSTALLED')) {
		    die(SYSTEM_NOT_INSTALLED);
		}

        $files = array('Install.class.php', 'install.php');
        $file_location = str_replace('/includes', '/install/', dirname(__FILE__));

		if (file_exists($file_location . $files[0]) || file_exists($file_location . $files[1])) {
		    die(REMOVE_INSTALL_FILES);
		}
		/**
		 * End of security functions
		 */

        $this->qls->config['sql_prefix'] = $database_prefix;

        // Get the actual database class
        require_once('MySQLie.class.php');

		switch ($database_type) {
			// Default is MySQLi
			default:
			case 'MySQLi':
                $this->current_layer = new MySQLie($database_server_name,
                    $database_username,
                    $database_password,
                    $database_name,
                    $this->qls,
                    $database_port
                );
                break;
		}
	}

	/**
	 * These functions run the functions in the database classes. See those 
	 * files for more information.
	 */
	function update_queries() {
	    $this->current_layer->update_queries();
	}

	function affected_rows() {
	    return $this->current_layer->affected_rows();
	}

	function fetch_row($result) {
	    return $this->current_layer->fetch_row($result);
	}

	function fetch_assoc($result) {
	    return $this->current_layer->fetch_assoc($result);
	}

	function fetch_array($result) {
	    return $this->current_layer->fetch_array($result);
	}

	function free_result($result) {
	    return $this->current_layer->free_result($result);
	}

	function get_client_info() {
	    return $this->current_layer->get_client_info();
	}

	function insert_id() {
	    return $this->current_layer->insert_id();
	}

	function num_fields($result) {
	    return $this->current_layer->num_fields($result);
	}

	function num_rows($result) {
	    return $this->current_layer->num_rows($result);
	}

	function transaction($status = 'BEGIN') {
	    return $this->current_layer->transaction($status);
	}

	function select($what, $from, $where = false, $order_by = false, $limit = false) {
	    return $this->current_layer->select($what, $from, $where, $order_by, $limit);
	}

	function delete($from, $where) {
	    return $this->current_layer->delete($from, $where);
	}

	function update($table, $set, $where) {
	    return $this->current_layer->update($table, $set, $where);
	}

	function insert($table, $columns, $values) {
	    return $this->current_layer->insert($table, $columns, $values);
	}

	function alter($table, $action, $column, $data_type = false, $null = false) {
	    return $this->current_layer->alter($table, $action, $column, $data_type, $null);
	}

	function query($query) {
	    return $this->current_layer->query($query);
	}

	function close() {
	    return $this->current_layer->close();
	}
}
?>