<?php

//database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$studentname="";
$studentID="";
$subject="";
$grade="";
$term="";
$academic="";
$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$studentname=$_POST['sname'];
	$studentID=$_POST['sid'];
	$subject=$_POST['sub'];
	$grade=$_POST['grade'];
	$term=$_POST['term'];
	$academic=$_POST['acyear'];
	

	do{
		if( empty($studentname) || empty($subject) || empty($grade) || empty($term) || empty($studentID) || empty($academic) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO grades(studentname, student_id, subject, grade, term, Academicyear)" .
		"VALUES ('$studentname', '$studentID', '$subject', '$grade', '$term', '$academic')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}

		$studentname="";
		$studentID="";
		$subject="";
		$grade="";
		$term="";
		$academic="";
		


		$successMessage="Student added successfully.";

		header("Location: gradedetails.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Updating Grades</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>Upload Grade</h2>

		<?php
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
				<label for="sname">Studentname</label><br>
				<input type="text" id="sname" name="sname" value="<?php echo $studentname; ?>">
			</div><br>
			<div class="input-box">
				<label for="sid">StudentID</label><br>
				<input type="number" id="sid" name="sid" value="<?php echo $studentID; ?>">
			</div><br>
			<div class="input-box">
				<label for="sub">Subject</label><br>
				<input type="text" id="sub" name="sub" value="<?php echo $subject; ?>">
			</div><br>
			<div class="input-box">
				<label for="grade">Grade</label><br>
				<input type="number" id="grade" name="grade" value="<?php echo $grade; ?>">
			</div><br>
			<div class="input-box">
				<label for="term">Term</label><br>
				<input type="text" id="term" name="term" value="<?php echo $term; ?>">
			</div><br>
			<div class="input-box">
				<label for=acyear>Academicyear</label><br>
				<input type="text" id="acyear" name="acyear" value="<?php echo $academic; ?>">
			</div><br>
			

			<?php
			if( !empty($successMessage) ){
				echo "
				<div>
				<etrong>$successMessage</strong>
				</div>
				";
			}

			?>

			<button type="Submit" class="btn">Submit</button>
			<a href="gradedetails.php"><button class="btn-btn">Cancel</button></a>
		</form>
		</form>
	</div>
</body>
</html>