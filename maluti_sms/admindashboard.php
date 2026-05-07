<?php
session_start();

if(!isset($_SESSION['role'])){
	header("location:sign.html"); 
	exit();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin dashboard</title>
	<link rel="stylesheet" href="admin.css">
	
</head>
<body>
	<header class="header">
		<a href="#">Admin Dashboard</a>

		<div class="logout">
			<a href="logout.php" class="btn btn-primary">LOGOUT</a>
		</div>
	</header>

	<aside>
		
		<ul>
			<li><a href="studentdetails.php">Add Students</a></li>
			<li><a href="assignstu2classes.php">Assign Students to classes</a></li>
			<li><a href="updateacademics.php">Update Academics</a></li>
			<li><a href="teacherdetails.php">Add Teacher</a></li>
			<li><a href="assigntea2classes.php">Assign Teachers to classes</a></li>
			<li><a href="assignsub2teachers.php">Assign Teachers to Subjects</a></li>
			<li><a href="parentdetails.php">Add Parents</a></li>
			<li><a href="attendencedetails.php">View Attendence</a></li>
			<li><a href="feedetails.php">Fee Details</a></li>
			<li><a href="invoice.php">Generate Invoice</a></li>
			<li><a href="report&analytics.php">Report & Analyses</a></li>
		</ul>

	</aside>

	<div class="content">
		<h1>Admin Dashboard</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat.</p>
	</div>

</body>
</html>