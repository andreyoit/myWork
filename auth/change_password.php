<?php
define('QUADODO_IN_SYSTEM', true);
require_once('includes/header.php');
?>



<?php
if ($qls->User->check_password_code()) {
	if (isset($_POST['process'])) {
		if ($qls->User->change_password()) {
		    echo CHANGE_PASSWORD_SUCCESS;
		}
		else {
		    printf($qls->User->change_password_error . CHANGE_PASSWORD_TRY_AGAIN, htmlentities(strip_tags($_GET['code']), ENT_QUOTES));
		}
	}
	else {
	    require_once('html/change_password_form.php');
	}
}
else {
	// Are we just sending the email?
	if (!isset($_GET['code'])) {
		if (isset($_POST['process'])) {
			if ($qls->User->send_password_email()) {
			    echo SEND_PASSWORD_EMAIL_SUCCESS;
			}
			else {
			    echo $qls->User->send_password_email_error . SEND_PASSWORD_EMAIL_TRY_AGAIN;
			}
		}
		else {
		    require_once('html/request_password_change_form.php');
		}
	}
	else {
	    echo CHANGE_PASSWORD_INVALID_CODE;
	}
}
?>