<?php
if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    //database Connection
    $conn = new mysqli('localhost', 'root', '', 'MalutiDB');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $checkQuery = "SELECT * FROM teachers WHERE id = $id";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $Query = "DELETE FROM teachers WHERE id = $id";
        if (!$conn->query($Query)) {
            die("Error deleting record: " . $conn->error);
        }
    }
}

header("Location: teacherdetails.php");
exit();
?>
