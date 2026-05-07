<?php
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>studend details</title>
	<link rel="stylesheet" type="text/css" href="">
</head>
<body>
	<div class="container my-5">
		<h2>Use</h2>
		<a href="addstudent.php" class="add_student">ADD STUDENT</a>
		<br>
		<table class="table">
			<thead>
				<tr>
					<th>StudentID</th>
					<th>Firstname</th>
					<th>Lastname</th>
					<th>Date of Birth</th>
					<th>Gender</th>
					<th>EnrollmentDate</th>
					<th>Created At</th>
					<th>Updated At</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				//database connection
			    $conn = new mysqli('localhost', 'root', '', 'MalutiDB');
			    if ($conn->connect_error) {
			        die('Connection Failed: ' . $conn->connect_error);
			    }

			    $query = "SELECT * FROM students";
			    $result = $conn->query($query);

			    if(!$result){
			    	die("Query was not successfully executed! ". $connection->error);
			    }

			    while($row = $result->fetch_assoc()){
			    	echo "
				    	<tr>
						<td>$row[student_id]</td>
						<td>$row[firstname]</td>
						<td>$row[lastname]</td>
						<td>$row[dateofbirth]</td>
						<td>$row[gender]</td>
						<td>$row[enrollmentdate]</td>
						<td>$row[created_at]</td>
						<td>$row[updated_at]</td>
						<td>
							<a href='studentupdate2.php?{$row['student_id']}'>UPDATE</a>
							<a href='studentdelete2.php?{$row['student_id']}'>DELETE</a>
						</td>
					</tr>
			    	"; 
			    }

				?>
				
			</tbody>
		</table>
	</div>
</body>
</html>