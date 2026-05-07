<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['uname'];
    $password = $_POST['passwd'];

    $conn = new mysqli('localhost', 'root', '', 'MalutiDB');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Login denied!!!";
    } else {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $role = $row['role'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $username;

            if ($role === 'parent') {
                $parent_stmt = $conn->prepare("SELECT id, student_id FROM parents WHERE username = ?");
                $parent_stmt->bind_param("s", $username);
                $parent_stmt->execute();
                $parent_result = $parent_stmt->get_result();

                if ($parent_result->num_rows > 0) {
                    $parent_data = $parent_result->fetch_assoc();
                    $_SESSION['parent_id'] = $parent_data['id'];
                    $_SESSION['student_id'] = $parent_data['student_id'];
                    header("Location: parentdashboard.php");
                    exit();
                } else {
                    echo "Parent account not found!";
                }
                $parent_stmt->close();

            } elseif ($role === 'student') {
                
                $student_stmt = $conn->prepare("SELECT student_id FROM students WHERE firstname = ?");
                $student_stmt->bind_param("s", $username);

                $student_stmt->execute();
                $student_result = $student_stmt->get_result();

                if ($student_result->num_rows > 0) {
                    $student_data = $student_result->fetch_assoc();
                    $_SESSION['student_id'] = $student_data['student_id'];
                    header("Location: studentdashboard.php");
                    exit();
                } else {
                    echo "Student account not found!";
                }
                $student_stmt->close();

            } else {
                
                switch ($role) {
                    case 'admin':
                        header("Location: admindashboard.php");
                        break;
                    case 'teacher':
                        header("Location: teacherdashboard.php");
                        break;
                    default:
                        echo "Invalid role!";
                }
                exit();
            }
        } else {
            echo "Login denied!!!";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
