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
	<title>Teacher dashboard</title>
	<link rel="stylesheet" href="admin.css">
	
</head>
<body>
	<header class="header">
		<a href="#">Teacher Dashboard</a>

		<div class="logout">
			<a href="logout.php" class="btn btn-primary">LOGOUT</a>
		</div>
	</header>

	<aside>
		
		<ul>
			<li><a href="attendencedetails.php">Sign Attendance</a></li>
			<li><a href="gradedetails.php">Update Grades</a></li>
		</ul>

	</aside>

	<div class="content">
		<h1>Teacher Dashboard</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat.</p>
	</div>

</body>
</html>