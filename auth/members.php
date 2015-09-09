<?php
define('QUADODO_IN_SYSTEM', true);
require_once('includes/header.php');
$qls->Security->check_auth_page('members.php');
?>



<?php
// Look in the USERGUIDE.html for more info
if ($qls->user_info['username'] != '') {
?>

You are logged in as <?php echo $qls->user_info['username']; ?><br />
Your email address is set to <?php echo $qls->user_info['email']; ?><br />
There have been <b><?php echo $qls->hits('members.php'); ?></b> visits to this page.<br />
<br />
Currently online users (<?php echo count($qls->online_users()); ?>): <?php $qls->output_online_users(); ?>

<?php
}
else {
?>

You are currently not logged in.

<?php
}
?>