<?php
ob_start();
header('Cache-control: private'); // IE 6 FIX

if(isSet($_GET['lang']))
{
$lang = $_GET['lang'];
 
// register the session and set the cookie
$_SESSION['lang'] = $lang;
 
setcookie('lang', $lang, time() + (3600 * 24 * 30), '/' );
}
else if(isSet($_SESSION['lang']))
{
$lang = $_SESSION['lang'];
}
else if(isSet($_COOKIE['lang']))
{
$lang = $_COOKIE['lang'];
}
else
{
$lang = 'en';
}

$lang_list = array (
    'English',
    'Italian',
  );
  

  
$lang_list = array_values($lang_list);
 
switch ($lang) {
  case 'en':
    $lang_file = 'lang.en.php';
    $lang_id = 'en';
    $lang_name = "English";
  break;
  
  case 'it':
    $lang_file = 'lang.it.php';
    $lang_id = 'it';
    $lang_name = "Italian";
  break;
 
  default:
    $lang_file = 'lang.en.php';
    $lang_id = 'en';
    $lang_name = "English";
  break;
}

include_once ('lang/'. $lang_file );

?>