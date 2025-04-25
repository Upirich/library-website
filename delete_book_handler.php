<?php
session_start();
include "db_connect.php";

$book_id = $_GET['delete_book'];

$sql = "SELECT image_path FROM books WHERE id = $book_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image_path = $row['image_path'];

    $sql = "DELETE FROM books WHERE id = $book_id";
    if ($conn->query($sql) === TRUE) {
        if (!empty($image_path) && file_exists($image_path)) {
            unlink($image_path);
        }
        echo "<p>Книга успешно удалена!</p> <a href='teacher_portfolio.php'>Назад</a>";
    } else {
        echo "<p>Ошибка удаления книги: " . $conn->error . "</p>";
        echo "<a href='teacher_portfolio.php'>Назад</a>";
    }
} else {
    echo "<p>Книга не найдена!</p> <a href='teacher_portfolio.php'>Назад</a>";
}
?>