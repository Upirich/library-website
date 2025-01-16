<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.html");
    exit();
}

include "db_connect.php";

$debt_id = $_GET['debt_id'];

$sql = "UPDATE debts SET is_returned=1 WHERE id=$debt_id";
if ($conn->query($sql) === TRUE) {
    $sql = "UPDATE books SET amount = amount + 1 WHERE id = (SELECT book_id FROM debts WHERE id=$debt_id)";
    if ($conn->query($sql) === TRUE) {
        echo "Книга возвращена. <a href=\"teacher_portfolio.php\">Назад</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>