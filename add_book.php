<?php
session_start();

include "db_connect.php";

$name = $_POST['name'];
$amount = $_POST['amount'];

if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_name = basename($_FILES['image']['name']);
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
        $sql = "INSERT INTO books (name, amount, image_path) VALUES ('$name', $amount, '$file_path')";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Книга успешно добавлена!</p> <a href='teacher_portfolio.php'>Назад</a>";
        } else {
            echo "<p>Ошибка в добавлении книги: " . $conn->error . "</p> <a href='teacher_portfolio.php'>Назад</a>";
        }
    } else {
        echo "<p>Ошибка добавления картинки!</p> <a href='teacher_portfolio.php'>Назад</a>";
    }
} else {
    $sql = "INSERT INTO books (name, amount) VALUES ('$name', $amount)";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Книга успешно добавлена!</p> <a href='teacher_portfolio.php'>Назад</a>";
    } else {
        echo "<p>Ошибка добавления книги: " . $conn->error . "</p> <a href='teacher_portfolio.php'>Назад</a>";
    }
}
?>