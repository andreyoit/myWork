<?php
if ($_COOKIE['login'] = '1') {
    header("location: app.php");
} elseif ($_COOKIE['login'] = NULL) {
    header("location: auth/login.php");
}

?>