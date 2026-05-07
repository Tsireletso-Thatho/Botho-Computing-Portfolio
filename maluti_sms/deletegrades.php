<?php
if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    $conn = new mysqli('localhost', 'root', '', 'MalutiDB');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $checkQuery = "SELECT * FROM grades WHERE id = $id";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $Query = "DELETE FROM grades WHERE id = $id";
        if (!$conn->query($Query)) {
            die("Error deleting record: " . $conn->error);
        }
    }
}

header("Location: gradedetails.php");
exit();
?>
