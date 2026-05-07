<?php
//database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

$query = "SELECT amount, payment_date, status FROM fees WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Invoice</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            background: #fff;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .invoice-box {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .invoice-box p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }
        .status {
            font-weight: bold;
            color: #e74c3c;
        }
        .paid {
            color: #2ecc71;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <h2>Invoice</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $statusClass = strtolower($row['status']) == 'paid' ? 'paid' : 'status';
            echo "<div class='invoice-box'>";
            echo "<p><strong>Amount Due:</strong> M" . htmlspecialchars($row['amount']) . "</p>";
            echo "<p><strong>Due Date:</strong> " . htmlspecialchars($row['payment_date']) . "</p>";
            echo "<p><strong>Status:</strong> <span class='$statusClass'>" . htmlspecialchars($row['status']) . "</span></p>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>No invoice records found for student ID $student_id.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>

</body>
</html>
