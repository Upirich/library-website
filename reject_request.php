<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.html");
    exit();
}

include "db_connect.php";

$request_id = $_GET['request_id'];


$sql = "SELECT user_id, book_id FROM borrow_requests WHERE id = $request_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $book_id = $row['book_id'];

    $sql = "SELECT name FROM books WHERE id = $book_id";
    $book_result = $conn->query($sql);
    $book_name = $book_result->fetch_assoc()['name'];


    $sql = "UPDATE borrow_requests SET status='rejected' WHERE id = $request_id";
    if ($conn->query($sql) === TRUE) {
        $message = "Ваш запрос на взятие книги $book_name был отклонён.";
        $sql = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$message')";
        if ($conn->query($sql) === TRUE) {
            $sql = "UPDATE books SET amount = amount + 1 WHERE id = $book_id";
            $conn->query($sql);
            echo "Запрос был отклонён. <a href=\"teacher_portfolio.php\">Назад</a>";
        } else {
            echo "Error inserting notification: " . $conn->error;
        }
    } else {
        echo "Error rejecting request: " . $conn->error;
    }
} else {
    echo "Request not found!";
}

$conn->close();

header("Location: teacher_portfolio.php");
exit();
?>