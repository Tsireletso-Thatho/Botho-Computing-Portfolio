<?php

$username = $_POST['name'];
$role = $_POST['role'];
$email = $_POST['emaill'];
$password = $_POST['pass'];
$confirm_password = $_POST['compass'];

//checking if the password entered matches the previous one before submitting them to the database
if ($password !== $confirm_password) {
    die('The passwords do not match.');
}

//hashing the password in the users table in the database so that even the adimin cannot see it
$hidden_password = password_hash($password, PASSWORD_DEFAULT);

//database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, role, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $role, $email, $hidden_password);
    
    if ($stmt->execute()) {
        header("Location: sign.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>