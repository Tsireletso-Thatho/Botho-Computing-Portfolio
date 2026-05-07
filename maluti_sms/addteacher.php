<?php

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$firstname="";
$lastname="";
$subject="";
$email="";
$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$firstname=$_POST['fname'];
	$lastname=$_POST['lname'];
	$subject=$_POST['sub'];
	$email=$_POST['email'];

	do{
		if( empty($firstname) || empty($lastname) || empty($subject) || empty($email) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO teachers(firstname, lastname, subject, email)" .
		"VALUES ('$firstname', '$lastname', '$subject', '$email')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}

		$firstname="";
		$lastname="";
		$subject="";
		$email="";

		$successMessage="Student added successfully.";

		header("Location: teacherdetails.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add new teacher</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>New teacher</h2>

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
				<label for="sub">Subject</label><br>
				<input type="text" id="sub" name="sub" value="<?php echo $subject; ?>" >
			</div><br>
			<div class="input-box">
				<label for="email">Email</label><br>
				<input type="email" id="email" name="email" value="<?php echo $email; ?>">
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
            <a href="teacherdetails.php"><button class="btn-btn">Cancel</button></a>
		</form>
		</form>
	</div>
</body>
</html>