<?php
session_start();
setcookie(session_name(), '', 100);
session_unset();
session_destroy();
$_SESSION = array();
header('Location: ' . $_SERVER['HTTP_REFERER']);
echo '<script>window.location.replace("../");</script>';
?>
