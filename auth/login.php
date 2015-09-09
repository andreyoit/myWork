<?php
define('QUADODO_IN_SYSTEM', true);
require_once('includes/header.php');
?>



<?php
// Is the user logged in already?
if ($qls->user_info['username'] == '') {
    require_once('html/login_form.php');
}
else {
    echo LOGIN_ALREADY_LOGGED;
}
?>