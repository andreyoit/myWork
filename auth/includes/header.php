<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    header.php
* @start   July 25th, 2007
* @author  Douglas Rennehan
* @license http://www.opensource.org/licenses/gpl-license.php
* @version 1.0.4
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

// Report everything except E_NOTICE, because it screws a lot of stuff up...
error_reporting(E_ALL ^ E_NOTICE);

// Send some headers
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

// The language we are using
require_once('Blank.lang.php');

// The qls class will start the other classes
require_once('qls.class.php');
$qls = new qls(SYS_CURRENT_LANG);
?>