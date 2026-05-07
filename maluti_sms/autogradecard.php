<?php

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$studentname = isset($_GET['studentname']) ? $_GET['studentname'] : ''; 

$query = "SELECT subject, grade, term, Academicyear FROM grades WHERE studentname = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $studentname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h2>Report Card for $studentname</h2>";
    echo "<table border='1'>
            <tr>
                <th>Subject</th>
                <th>Grade</th>
                <th>Term</th>
                <th>Academic Year</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['subject']) . "</td>
                <td>" . htmlspecialchars($row['grade']) . "</td>
                <td>" . htmlspecialchars($row['term']) . "</td>
                <td>" . htmlspecialchars($row['Academicyear']) . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<h2>No grades found for $studentname.</h2>";
}

$stmt->close();
$conn->close();
?>