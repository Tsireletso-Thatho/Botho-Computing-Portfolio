<?php

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$studentname="";
$gradelevel="";
$subject="";
$term="";
$academic="";
$grade="";
$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$studentname=$_POST['sname'];
	$gradelevel=$_POST['gradel'];
	$subject=$_POST['sub'];
	$term=$_POST['term'];
	$academic=$_POST['acyear'];
	$grade=$_POST['grade'];

	do{
		if( empty($studentname) || empty($gradelevel) || empty($subject) || empty($term) || empty($academic) || empty($grade) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO academics(studentname, gradelevel, subject, term, academicyear, grade)" .
		"VALUES ('$studentname', '$gradelevel', '$subject', '$term', '$academic', '$grade')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}

		$studentname="";
		$gradelevel="";
		$subject="";
		$term="";
		$academic="";
		$grade="";


		$successMessage="Student added successfully.";

		header("Location: updateacademics.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Updating academics</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>Academics Update</h2>

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
				<label for="gradel">Grade Level</label><br>
				<input type="text" id="gradel" name="gradel" value="<?php echo $gradelevel; ?>">
			</div><br>
			<div class="input-box">
				<label for="sub">Subject</label><br>
				<input type="text" id="sub" name="sub" value="<?php echo $subject; ?>">
			</div><br>
			<div class="input-box">
				<label for="term">Term</label><br>
				<input type="text" id="term" name="term" value="<?php echo $term; ?>">
			</div><br>
			<div class="input-box">
				<label for=acyear>Academicyear</label><br>
				<input type="text" id="acyear" name="acyear" value="<?php echo $academic; ?>">
			</div><br>
			<div class="input-box">
				<label for="grade">Grade</label><br>
				<input type="number" id="grade" name="grade" value="<?php echo $grade; ?>">
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
            <a href="updateacademics.php"><button class="btn-btn">Cancel</button></a>
		</form>
		</form>
	</div>
</body>
</html>