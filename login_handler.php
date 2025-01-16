<?php
session_start();

include "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $input_password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($input_password, $row['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'student') {
                header("Location: student_portfolio.php");
            } else {
                header("Location: teacher_portfolio.php");
            }
            exit();
        } else {
            echo "Invalid password! <a href='login.html'>Try again</a>.";
        }
    } else {
        echo "User not found! <a href='index.html'>Register here</a>.";
    }

    $stmt->close();
}

$conn->close();
?>