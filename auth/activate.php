<?php
define('QUADODO_IN_SYSTEM', true);
require_once('includes/header.php');
?>



<?php
// Are they already logged in?
if ($qls->user_info['username'] == '') {
	// is user validation even turned on
	if ($qls->config['activation_type'] == 1) {
		// Try to activate them
		if ($qls->User->activate_user()) {
		    echo ACTIVATE_SUCCESS;
		}
		else {
		    echo $qls->User->activate_error;
		}
	}
	elseif ($qls->config['activation_type'] == 2) {
	    echo ACTIVATE_ADMIN_VERIFICATION;
	}
	else {
	    echo ACTIVATE_NO_NEED;
	}
}
else {
    echo ACTIVATE_ALREADY_LOGGED;
}
?>