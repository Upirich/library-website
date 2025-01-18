<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.html");
    exit();
}

include "db_connect.php";
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM notifications WHERE user_id = $user_id AND is_read = 0 ORDER BY created_at DESC";
$notifications_result = $conn->query($sql);

echo "<h1>Уведомления</h1>";
if ($notifications_result->num_rows > 0) {
    while ($notification = $notifications_result->fetch_assoc()) {
        echo "<p>" . $notification['message'] . " <small>(" . $notification['created_at'] . ")</small></p>";
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = " . $notification['id'];
        $conn->query($sql);
    }
} else {
    echo "<p>Новых уведомлений нет.</p>";
}

$sql = "SELECT books.name, debts.borrow_date, debts.return_date FROM debts JOIN books ON debts.book_id = books.id WHERE debts.user_id = $user_id AND debts.is_returned = 0";
$result = $conn->query($sql);

echo "<h1>Долги по книгам</h1>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Книга: " . $row["name"]. " - Взята: " . $row["borrow_date"]. " - Вернуть до: " . $row["return_date"]. "<br>";
    }
} else {
    echo "Нет долгов по книгам.";
}

$sql = "SELECT * FROM books WHERE amount > 0";
$result = $conn->query($sql);

echo "<h1>Доступные книги</h1>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<h3>" . $row["name"] . "</h3>";
        if (!empty($row["image_path"])) {
            echo "<img src='" . $row["image_path"] . "' alt='" . $row["name"] . "' width='200'>";
        } else {
            echo "<p>Нет картинки.</p>";
        }
        echo "<p>Доступные: " . $row["amount"] . " <a href='request_book.php?book_id=" . $row["id"] . "'>Отправить запрос</a></p>";
        echo "</div>";
    }
} else {
    echo "Нет доступных книг.";
}

$conn->close();
?>