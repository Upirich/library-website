<?php
session_start();

include "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($role === 'student') {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $class = $_POST['class'];

        $stmt = $conn->prepare("INSERT INTO users (name, surname, class, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $surname, $class, $email, $password, $role);
    } else {
        $teacher_code = $_POST['teacher_code'];
        $file = fopen("teacher_code.txt", "r");
        $str = fgets($file);
        fclose($file);
        if ($teacher_code === $str) {
            $stmt = $conn->prepare("INSERT INTO users (email, password, role, teacher_code) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $email, $password, $role, $teacher_code);
        } else {
            echo "Invalid teacher code! <a href='index.html'>Try again</a>";
        }
    }

    if ($stmt->execute()) {
        echo "Registration successful! <a href='login.html'>Login here</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>