<?php

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$studentid="";
$name="";
$email="";
$phone="";
$relationship="";
$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$studentid=$_POST['sid'];
	$name=$_POST['name'];
	$email=$_POST['email'];
	$phone=$_POST['phone'];
	$relationship=$_POST['rel'];

	do{
		if( empty($studentid) || empty($name) || empty($email) || empty($phone) || empty($relationship) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO parents(student_id, username, email, phone, relationship)" .
		"VALUES ('$studentid', '$name', '$email', '$phone', '$relationship')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}


		$studentid="";
		$amount="";
		$date="";
		$status ="";

		$successMessage="Parent added successfully.";

		header("Location: parentdetails.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Parent Record</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>Parent Record</h2>

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
				<label for="sid">StudentID</label><br>
				<input type="number" id="sid" name="sid" value="<?php echo $studentid; ?>">
			</div><br>
			<div class="input-box">
				<label for="name">ParentUserame</label><br>
				<input type="text" id="name" name="name" value="<?php echo $name; ?>">
			</div><br>
			<div class="input-box">
				<label for="email">Email</label><br>
				<input type="email" id="email" name="email" value="<?php echo $email; ?>">
			</div><br>
			<div class="input-box">
				<label for="phone">Phonenumber</label><br>
				<input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>">
			</div><br>
			<div class="input-box">
				<label for="rel">Relationship</label><br>
				<input type="text" id="rel" name="rel" value="<?php echo $relationship; ?>"rel>
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
            <a href="parentdetails.php"><button class="btn-btn">Cancel</button></a>
		</form>
		</form>
	</div>
</body>
</html>