<?php
/*** *** *** *** *** ***
* @package   Quadodo Login Script
* @file      database_info.php
* @author    Douglas Rennehan
* @generated September 12th, 2015
* @link      http://www.quadodo.net
*** *** *** *** *** ***
* Comments are always before the code they are commenting
*** *** *** *** *** ***/
if (!defined('QUADODO_IN_SYSTEM')) {
exit;
}

define('SYSTEM_INSTALLED', true);
$database_prefix = 'auth_';
$database_type = 'MySQLi';
$database_server_name = '127.0.0.1';
$database_username = 'andreaem_dev';
$database_password = '';
$database_name = 'c9';
$database_port = false;

/**
 * Use persistent connections?
 * Change to true if you have a high load
 * on your server, but it's not really needed.
 */
$database_persistent = false;
?>