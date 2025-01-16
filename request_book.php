<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.html");
    exit();
}

include "db_connect.php";

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book_id'];

$sql = "INSERT INTO borrow_requests (user_id, book_id, request_date, status) VALUES ($user_id, $book_id, NOW(), 'pending')";
$sql1 = "UPDATE books SET amount = amount - 1 WHERE id = $book_id";
if ($conn->query($sql) === TRUE and $conn->query($sql1) === True) {
    echo "Запрос на взятие книги подан. <a href=\"student_portfolio.php\">Назад</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>