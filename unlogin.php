<?php
session_start();
$_SESSION['zahodcount'] = 0;
header("Location: login.html");
exit();
?>