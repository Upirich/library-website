<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.html");
    exit();
}

include "db_connect.php";

$request_id = $_GET['request_id'];

$sql = "UPDATE borrow_requests SET status='approved' WHERE id=$request_id";
if ($conn->query($sql) === TRUE) {
    $sql = "INSERT INTO debts (user_id, book_id, borrow_date, return_date) SELECT user_id, book_id, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY) FROM borrow_requests WHERE id=$request_id";
    if ($conn->query($sql) === TRUE) {
        echo "Запрос одобрен! <a href=\"teacher_portfolio.php\">Назад</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>