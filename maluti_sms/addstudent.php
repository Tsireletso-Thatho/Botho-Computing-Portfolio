<?php

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$firstname="";
$lastname="";
$dateofbirth="";
$gender="";
$enrollment="";

$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$firstname=$_POST['fname'];
	$lastname=$_POST['lname'];
	$dateofbirth=$_POST['dob'];
	$gender = isset($_POST['gender']) ? $_POST['gender'] : ''; 
	$enrollment=$_POST['enroll'];

	do{
		if( empty($firstname) || empty($lastname) || empty($dateofbirth) || empty($gender) || empty($enrollment) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO students(firstname, lastname, dateofbirth, gender, enrollmentdate)" .
		"VALUES ('$firstname', '$lastname', '$dateofbirth', '$gender', '$enrollment')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}

		$firstname="";
		$lastname="";
		$dateofbirth="";
		$gender="";
		$enrollment="";

		$successMessage="Student added successfully.";

		header("Location: studentdetails.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add new student</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>New Student</h2>

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
				<label for="fname">Firstname</label><br>
				<input type="text" id="fname" name="fname" value="<?php echo $firstname; ?>">
			</div><br>
			<div class="input-box">
				<label for="lname">Lastname</label><br>
				<input type="text" id="lname" name="lname" value="<?php echo $lastname; ?>">
			</div><br>
			<div class="input-box">
				<label for="dob">Date of Birth</label><br>
				<input type="date" id="dob" name="dob" value="<?php echo $dateofbirth; ?>" >
			</div><br>
			<div class="dif-box">
                <label for="gender">Gender</label>
                <label for="male"><input type="radio" id="male" name="gender" value="male" <?php echo($gender == 'male') ? 'checked': ''; ?> >male</label>
                <label for="female"><input type="radio" id="female" name="gender" value="female" <?php echo($gender == 'female') ? 'checked': ''; ?> >female</label>
                <label for="female"><input type="radio" id="other" name="gender" value="other" <?php echo($gender == 'other') ? 'checked': ''; ?> >other</label>
            </div><br>
			<div class="input-box">
				<label for="enroll">EnrollmentDate</label><br>
				<input type="date" id="enroll" name="enroll" value="<?php echo $enrollment; ?>">
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

			<button type="Submit" class="btn">Submit</button><br>
			<a href="studentdetails.php"><button class="btn-btn">Cancel</button></a>
		</form>
		</form>
	</div>
</body>
</html>