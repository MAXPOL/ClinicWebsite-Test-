<?php
require_once '../auth.php';
logout();
header('Location: /clinic/login.php');
exit();
?>
