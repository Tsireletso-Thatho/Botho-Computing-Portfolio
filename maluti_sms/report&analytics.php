<?php

//database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

//query to get attendance summary from attendance table
$query = "SELECT student_id, date, status FROM attendance ORDER BY date DESC";
$result = $conn->query($query);

//query to generate performance report using the grades table
$query1 = "SELECT studentname, subject, AVG(grade) AS average_grade FROM grades GROUP BY studentname, subject";
$result1 = $conn->query($query1);

//query to generate financial report using the fees table
$query2 = "
    SELECT 
        student_id,
        COUNT(*) AS total_payments,
        SUM(CASE WHEN status = 'Paid' THEN 1 ELSE 0 END) AS paid_count,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
        MAX(payment_date) AS last_payment_date
    FROM fees
    GROUP BY student_id
";

$result2 = $conn->query($query2);


echo '
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
        margin-bottom: 100px;
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
';


//______________________________________attendance report__________________________________________


echo "<h2>Attendance Report</h2>";

echo "<table class='content'>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['student_id']}</td>
            <td>{$row['date']}</td>
            <td>{$row['status']}</td>
          </tr>";
}

echo "</tbody></table>";



//______________________________________performance report__________________________________________


echo "<h2>Performance Report</h2>";

echo "<table class='content'>
        <thead>
            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Average Grade</th>
            </tr>
        </thead>
        <tbody>";

while ($row = $result1->fetch_assoc()) {
    echo "<tr>
            <td>{$row['studentname']}</td>
            <td>{$row['subject']}</td>
            <td>" . number_format($row['average_grade'], 2) . "</td>
          </tr>";
}

echo "</tbody></table>";

//______________________________________finances report__________________________________________

echo "<h2>Finance Report</h2>";

echo "<table class='content'>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Total Payments</th>
                <th>Paid</th>
                <th>Pending</th>
                <th>Last Payment Date</th>
            </tr>
        </thead>
        <tbody>";

while ($row = $result2->fetch_assoc()) {
    echo "<tr>
            <td>{$row['student_id']}</td>
            <td>{$row['total_payments']}</td>
            <td>{$row['paid_count']}</td>
            <td>{$row['pending_count']}</td>
            <td>{$row['last_payment_date']}</td>
          </tr>";
}

echo "</tbody></table>";



$conn->close();
?>
