<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.html");
    exit();
}
$_SESSION['zahodcount'] = 2;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
<link rel="stylesheet" href="styles.css">
<div id="wrapper">
    <div id="menu">
        <a href="#" class="shkbbl">Школьная библиотека №224</a>
        <a href="#" class="hrefpgkn1">Профиль</a>
        <a href="login.html" class="hrefpgvh">Вход</a>
        <a href="index.html" class="hrefpgreg1">Регистрация</a>
    </div>
</div>
</head>

<?php
include "db_connect.php";
echo "<a href='unlogin.php' class='unlogin'>Выйти из аккаунта</a>";


$sql = "SELECT borrow_requests.id, users.name, users.surname, books.name AS book_name, borrow_requests.request_date 
        FROM borrow_requests 
        JOIN users ON borrow_requests.user_id = users.id 
        JOIN books ON borrow_requests.book_id = books.id 
        WHERE borrow_requests.status = 'pending'";
$requests_result = $conn->query($sql);

echo "<h1>Запросы в ожидании</h1>";
if ($requests_result->num_rows > 0) {
    while ($row = $requests_result->fetch_assoc()) {
        echo "Request ID: " . $row["id"] . 
             " - Ученик: " . $row["name"] . " " . $row["surname"] . 
             " - Книга: " . $row["book_name"] . 
             " - Дата запроса: " . $row["request_date"] . 
             " <a href='approve_request.php?request_id=" . $row["id"] . "'>Подтвердить</a> " .
             " <a href='reject_request.php?request_id=" . $row["id"] . "'>Отклонить</a><br>";
    }
} else {
    echo "Запросов нет.";
}

$sql = "SELECT debts.id, users.name, users.surname, books.name AS book_name, debts.borrow_date, debts.return_date 
        FROM debts 
        JOIN users ON debts.user_id = users.id 
        JOIN books ON debts.book_id = books.id 
        WHERE debts.is_returned = 0";
$debts_result = $conn->query($sql);

echo "<h1>Долги по книгам</h1>";
if ($debts_result->num_rows > 0) {
    while ($row = $debts_result->fetch_assoc()) {
        echo "Debt ID: " . $row["id"] . 
             " - Ученик: " . $row["name"] . " " . $row["surname"] . 
             " - Книга: " . $row["book_name"] . 
             " - Взята: " . $row["borrow_date"] . 
             " - Вернуть до: " . $row["return_date"] . 
             " <a href='return_book.php?debt_id=" . $row["id"] . "'>Отметить как возвращённую</a><br>";
    }
} else {
    echo "Нет долгов по книгам.";
}

$sql = "SELECT * FROM books";
$books_result = $conn->query($sql);

echo "<h1>Доступные книги</h1>";
if ($books_result->num_rows > 0) {
    while ($row = $books_result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<h3>" . $row["name"] . "</h3>";
        if (!empty($row["image_path"])) {
            echo "<img src='" . $row["image_path"] . "' alt='" . $row["name"] . "' width='200'>";
        } else {
            echo "<p>Нет картинки.</p>";
        }
        echo "<p>Доступно: " . $row["amount"] . "</p>";
        echo "<a href='delete_book_handler.php?delete_book=" . $row["id"] . "' onclick='return confirm(\"Вы уверены?\")'>Удалить</a>";
        echo "</div>";
    }
} else {
    echo "Нет книг.";
}

echo "<h1>Добавить книгу</h1>";
echo "<form method='POST' action='add_book.php' enctype='multipart/form-data'>
        <label for='name' class='name'>Название:</label>
        <input type='text' name='name' id='name1' required>
        <br>
        <label for='amount' class='amount'>Количество:</label>
        <input type='number' name='amount' id='amount' required>
        <br>
        <label for='image' class='image'>Картинка:</label>
        <input type='file' name='image' id='image' accept='image/*'>
        <br>
        <button type='submit' name='add_book'>Добавить</button>
      </form>";

$conn->close();
?>