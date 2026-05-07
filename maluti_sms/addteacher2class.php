<?php

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$classname="";
$teachername="";
$studentname="";
$academic="";
$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$classname=$_POST['cname'];
	$teachername=$_POST['tname'];
	$academic=$_POST['acyear'];

	do{
		if( empty($classname) || empty($teachername) || empty($academic) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO classes(classname, teachername, studentname, academicyear)" .
		"VALUES ('$classname', '$teachername', '$studentname', '$academic')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}

		$classname="";
		$teachername="";
		$studentname="";
		$academic="";

		$successMessage="Student added successfully.";

		header("Location: assigntea2classes.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Assigning classes to Teachers</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>Assign Class</h2>

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
				<label for="cname">Classname</label><br>
				<input type="text" id="cname" name="cname" value="<?php echo $classname; ?>">
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
			if( !empty($successMessage) ){
				echo "
				<div>
				<etrong>$successMessage</strong>
				</div>
				";
			}

			?>

			<button type="Submit" class="btn">Submit</button><br>
            <a href="assigntea2classes.php"><button class="btn-btn">Cancel</button></a>
		</form>
		</form>
	</div>
</body>
</html>