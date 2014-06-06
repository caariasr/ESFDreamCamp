<?php
require_once('security/session.php');
require_once('security/user.php');
$user = new User();
$user->logout();
header('location: index.php');
exit;
