<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    die("Unauthorized access. Please log in as a parent.");
}

$student_id = $_SESSION['student_id'];

//database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}


$query = "SELECT student_id, date, status FROM attendance WHERE student_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();


$query1 = "SELECT studentname, subject, AVG(grade) AS average_grade FROM grades WHERE student_id = ? GROUP BY studentname, subject";
$stmt1 = $conn->prepare($query1);
$stmt1->bind_param("i", $student_id);
$stmt1->execute();
$result1 = $stmt1->get_result();


$query2 = "
    SELECT 
        student_id,
        COUNT(*) AS total_payments,
        SUM(CASE WHEN status = 'Paid' THEN 1 ELSE 0 END) AS paid_count,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
        MAX(payment_date) AS last_payment_date
    FROM fees
    WHERE student_id = ?
    GROUP BY student_id
";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("i", $student_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Parent Reports</title>
    <style>
    h2 {
        color: #009870;
        margin-left: 15%;
        margin-bottom: 30px;
    }
    a .click {
        background: #009870;
        color: white;
        padding: 12px;
        width: 150px;
        border-radius: 15px;
        border: 0;
        transition: .3s ease;
        cursor: pointer;
    }
    .click:hover {
        background: skyblue;
    }
    a .btn-click {
        background: skyblue;
        color: white;
        width: 60px;
        height: 20px;
        padding: 3px;
        border-radius: 15px;
        border: 0;
        transition: .3s ease;
        cursor: pointer;
    }
    .btn-click:hover {
        background: #009870;
    }
    .content {
        border-radius: 10px 10px 0 0;
        overflow: hidden;
        border-collapse: collapse;
        margin: 25px auto;
        font-size: 0.9em;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, .15);
        width: 100%;
        margin-bottom: 60px;
    }
    .content thead tr {
        background: #009870;
        color: white;
        text-align: left;
        font-weight: bold;
    }
    .content th,
    .content td {
        padding: 12px 15px;
    }
    .content tbody tr {
        border-bottom: 1px solid #dddddd;
    }
    .content tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }
    .content tbody tr:last-of-type {
        border-bottom: 2px solid #009829;
    }
</style>
</head>
<body>

<h2>Attendance Report</h2>
<table class="content">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h2>Performance Report</h2>
<table class="content">
    <thead>
        <tr>
            <th>StudentName</th>
            <th>Subject</th>
            <th>Average Grade</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result1->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['studentname']) ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= number_format($row['average_grade'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h2>Finance Report</h2>
<table class="content">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Total Payments</th>
            <th>Paid</th>
            <th>Pending</th>
            <th>Last Payment Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result2->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['total_payments']) ?></td>
                <td><?= htmlspecialchars($row['paid_count']) ?></td>
                <td><?= htmlspecialchars($row['pending_count']) ?></td>
                <td><?= htmlspecialchars($row['last_payment_date']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
