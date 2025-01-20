<?php
session_start();
if ($_SESSION['zahodcount'] === 1) {
    header("Location: student_portfolio.php");
    exit();
}
elseif ($_SESSION['zahodcount'] === 2) {
    header("Location: teacher_portfolio.php");
    exit();
}
else {
    header("Location: login.html");
    exit();
}