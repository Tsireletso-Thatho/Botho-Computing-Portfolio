<?php

//database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$studentid="";
$subjectname="";
$classname="";
$teachername="";
$date="";
$status="";
$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$studentid=$_POST['sid'];
	$subjectname=$_POST['sub'];
	$classname=$_POST['cname'];
	$teachername=$_POST['tname'];
	$date=$_POST['dt'];
	$status = isset($_POST['status']) ? $_POST['status'] : '';

	do{
		if( empty($studentid) || empty($subjectname) || empty($classname) || empty($teachername) || empty($date) || empty($status) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO attendance(student_id, subjectname, classname, teachername, date, status)" .
		"VALUES ('$studentid', '$subjectname', '$classname', '$teachername', '$date', '$status')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}

		$studentid="";
		$subjectname="";
		$classname="";
		$teachername="";
		$date="";
		$status ="";

		$successMessage="Student added successfully.";

		header("Location: attendencedetails.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Signing Attendance</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>New Attendance</h2>

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
				<input type="text" id="sid" name="sid" value="<?php echo $studentid; ?>">
			</div><br>
			<div class="input-box">
				<label for="sub">Subjectname</label><br>
				<input type="text" id="sub" name="sub" value="<?php echo $subjectname; ?>">
			</div><br>
			<div class="input-box">
				<label for="cname">Classname</label><br>
				<input type="text" id="cname" name="cname" value="<?php echo $classname; ?>" >
			</div><br>
			<div class="input-box">
				<label for="tname">Teachername</label><br>
				<input type="text" id="tname" name="tname" value="<?php echo $teachername; ?>" >
			</div><br>
			<div class="input-box">
				<label for="dt">Date</label><br>
				<input type="date" id="dt" name="dt" value="<?php echo $date; ?>">
			</div><br>
			<div class="dif-box">
                <label>Status</label>
                <label for="present">
                	<input type="radio" id="present" name="status" value="present" <?php echo (isset($status) && $status == 'present') ? 'checked' : ''; ?> required> Present
                </label>
                <label for="absent">
                	<input type="radio" id="absent" name="status" value="absent" <?php echo (isset($status) && $status == 'absent') ? 'checked' : ''; ?>> Absent
                </label>
            </div><br>

			<?php
			
			if( !empty($successMessage) ){
				echo "
				<div>
				<strong>$successMessage</strong>
				</div>
				";
			}

			?>

			<button type="Submit" class="btn">Submit</button><br>
            <a href="attendencedetails.php"><button class="btn-btn">Cancel</button></a>
		</form>
	</div>
</body>
</html>