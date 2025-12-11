<?php
session_start();
require_once __DIR__ . '/../php/config.php';

session_unset();
session_destroy();
redirect_to('auth/login.php');
?>
