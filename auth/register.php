<?php
define('QUADODO_IN_SYSTEM', true);
require_once('includes/header.php');
$qls->Security->check_auth_registration();
?>



<?php
// Is the user logged in already?
if ($qls->user_info['username'] == '') {
	if (isset($_POST['process'])) {
		// Try to register the user
		if ($qls->User->register_user()) {
			switch ($qls->config['activation_type']) {
				default:
                    echo REGISTER_SUCCESS_NO_ACTIVATION;
                    break;
				case 1:
                    echo REGISTER_SUCCESS_USER_ACTIVATION;
                    break;
				case 2:
                    echo REGISTER_SUCCESS_ADMIN_ACTIVATION;
                    break;
            }
		}
		else {
            // Output register error
            echo $qls->User->register_error . REGISTER_TRY_AGAIN;
		}
	}
	else {
        // Get the random id for use in the form
        $random_id = $qls->Security->generate_random_id();
        require_once('html/register_form.php');
	}
}
else {
    echo REGISTER_ALREADY_LOGGED;
}
?>