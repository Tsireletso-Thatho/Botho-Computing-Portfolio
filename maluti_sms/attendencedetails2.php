<?php

session_start();

if (!isset($_SESSION['parent_id']) || !isset($_SESSION['student_id'])) {
    header('Location: sign.html');
    exit();
}

$studentid = $_SESSION['student_id']; 

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Attendance Details</title>
	<style>
		
		h2{
			color: #009870;
			margin-left: 15%;
			margin-bottom: 30px;
		}
		a .click{
			background: #009870;
			color: white;
			padding: 12px;
			width: 150px;
			border-radius: 15px;
			border: 0;
			transition: .3s ease;
			cursor: pointer;
		}
		.click:hover{
			background: skyblue;
		}
		a .btn-click{
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
		.btn-click:hover{
			background: #009870;
		}
		.content{
			border-radius: 10px 10px 0 0;
			overflow: hidden;
			border-collapse: collapse;
			margin: 25px 0;
			font-size: 0.9em;
			min-width: 400px;
			box-shadow: 0 0 20px rgba(0, 0, 0, .15);
			width: 100%;
		}
		.content thead tr{
			background: #009870;
			color: white;
			text-align: left;
			font-weight: bold;
		}
		.content th,
		.content td{
			padding: 12px 15px;
		}
		.content tbody tr{
			border-bottom: 1px solid #dddddd;
		}
		.content tbody tr:nth-of-type(even){
			background-color: #f3f3f3;
		}
		.content tbody tr:last-of-type{
			border-bottom: 2px solid #009829;
		}

	</style>
</head>
<body>
	<h2>Attendance Details</h2>
	<table class="content">
		<thead>
			<tr>
				<th>AttendanceID</th>
				<th>StudentID</th>
				<th>Subject Name</th>
				<th>Class Name</th>
				<th>Teacher Name</th>
				<th>Date</th>
				<th>Status</th>
				<th>Created At</th>
			</tr>
		</thead>
		<tbody>
			<?php
			
			$conn = new mysqli('localhost', 'root', '', 'MalutiDB');
			if ($conn->connect_error) {
				die('Connection Failed: ' . $conn->connect_error);
			}

			$query = "SELECT * FROM attendance WHERE student_id = ?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("i", $studentid); 
			$stmt->execute();
			$result = $stmt->get_result();

			if (!$result) {
				die("Query was not successfully executed! " . $conn->error);
			}

			while ($row = $result->fetch_assoc()) {
				echo "
					<tr>
						<td>{$row['attendance_id']}</td>
						<td>{$row['student_id']}</td>
						<td>{$row['subjectname']}</td>
						<td>{$row['classname']}</td>
						<td>{$row['teachername']}</td>
						<td>{$row['date']}</td>
						<td>{$row['status']}</td>
						<td>{$row['created_at']}</td>
					</tr>
				";
			}

			$stmt->close();
			$conn->close();
			?>
		</tbody>
	</table>
</body>
</html>
