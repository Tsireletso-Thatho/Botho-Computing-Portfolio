<?php

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$subjectname="";
$teachername="";
$studentname="";
$academic="";
$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$subjectname=$_POST['sname'];
	$teachername=$_POST['tname'];
	$academic=$_POST['acyear'];

	do{
		if( empty($subjectname) || empty($teachername) || empty($academic) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO subjects(subjectname, teachername, studentname, academicyear)" .
		"VALUES ('$subjectname', '$teachername', '$studentname', '$academic')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}

		$subjectname="";
		$teachername="";
		$studentname="";
		$academic="";

		$successMessage="Student added successfully.";

		header("Location: assignsub2teachers.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Assigning Subject to Teacher</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>Assign Subject</h2>

		<?php
		//the error message will be displayed here

		//checking if the alert message for error is not empty
		if( !empty($alertMessage) ){
			echo "
			<div>
			<strong>$alertMessage</strong>
			</div>
			";
		}

		?>
		<form action="" method="POST">
			<div class="input-box">
				<label for="sname">Subjectname</label><br>
				<input type="text" id="sname" name="sname" value="<?php echo $subjectname; ?>">
			</div><br>
			<div class="input-box">
				<label for="tname">Teachername</label><br>
				<input type="text" id="tname" name="tname" value="<?php echo $teachername; ?>">
			</div><br>
			<div class="input-box">
				<label for=acyear>Academicyear</label><br>
				<input type="text" id="acyear" name="acyear" value="<?php echo $academic; ?>">
			</div><br>

			<?php
			//this part will display success message

			//checking if the success message is not empty
			if( !empty($successMessage) ){
				echo "
				<div>
				<etrong>$successMessage</strong>
				</div>
				";
			}

			?>

			<button type="Submit" class="btn">Submit</button><br>
            <a href="assignsub2teachers.php"><button class="btn-btn">Cancel</button></a>
		</form>
		</form>
	</div>
</body>
</html>