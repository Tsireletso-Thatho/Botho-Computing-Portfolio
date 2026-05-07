<?php
if (isset($_GET["student_id"])) {
    $id = intval($_GET["student_id"]);

    // database connection
    $conn = new mysqli('localhost', 'root', '', 'MalutiDB');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $checkQuery = "SELECT * FROM students WHERE student_id = $id";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $deleteQuery = "DELETE FROM students WHERE student_id = $id";
        if (!$conn->query($deleteQuery)) {
            die("Error deleting record: " . $conn->error);
        }
    }
}

header("Location: studentdetails.php");
exit();
?>
