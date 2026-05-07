<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['student_id'])) {
    header("Location: sign.html"); 
    exit();
}

$student_id = $_SESSION['student_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Grades</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        h2 {
            color: #009870;
            margin-left: 15%;
            margin-bottom: 20px;
        }
        .content {
            border-radius: 10px 10px 0 0;
            overflow: hidden;
            border-collapse: collapse;
            margin: 25px auto;
            font-size: 0.9em;
            width: 100%;
            box-shadow: 0 0 20px rgba(0, 0, 0, .15);
        }
        .content thead tr {
            background-color: #009870;
            color: #fff;
            text-align: left;
            font-weight: bold;
        }
        .content th, .content td {
            padding: 12px 15px;
        }
        .content tbody tr {
            border-bottom: 1px solid #ddd;
        }
        .content tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }
        .content tbody tr:last-of-type {
            border-bottom: 2px solid #009870;
        }
    </style>
</head>
<body>

    <h2>My Grades</h2>
    <table class="content">
        <thead>
            <tr>
                <th>Grade ID</th>
                <th>Subject</th>
                <th>Term</th>
                <th>Grade</th>
                <th>Academic Year</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = new mysqli("localhost", "root", "", "MalutiDB");

            if ($conn->connect_error) {
                die("Database connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("SELECT id, subject, term, grade, Academicyear, created_at, updated_at FROM grades WHERE student_id = ?");
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['subject']}</td>
                        <td>{$row['term']}</td>
                        <td>{$row['grade']}</td>
                        <td>{$row['Academicyear']}</td>
                        <td>{$row['created_at']}</td>
                        <td>{$row['updated_at']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No grades found.</td></tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </tbody>
    </table>

</body>
</html>
