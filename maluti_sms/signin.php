<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receiving input from the form
    $username = $_POST['uname'];
    $password = $_POST['passwd'];

    //database connectio
    $conn = new mysqli('localhost', 'root', '', 'MalutiDB');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Login denied!!!";
    } else {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $role = $row['role'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['role'] = $role;

            switch ($role) {
                case 'admin':
                    header("Location: admindashboard.php");
                    break;
                case 'teacher':
                    header("Location: teacherdashboard.php");
                    break;
                case 'parent':
                    header("Location: parentdashboard.php");
                    break;
                case 'student':
                    header("Location: studentdashboard.php");
                    break;
                default:
                    echo "Invalid role!";
                    break;
            }
            exit();
        } else {
            echo "Login denied!!!";
        }
    }

    $stmt->close();
    $conn->close();
}
?>