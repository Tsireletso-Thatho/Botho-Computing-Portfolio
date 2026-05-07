<?php

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

$studentid="";
$amount="";
$date="";
$status="";

$alertMessage="";
$successMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$studentid=$_POST['sid'];
	$amount=$_POST['amount'];
	$date=$_POST['dt'];
	$status = isset($_POST['status']) ? $_POST['status'] : '';

	do{
		if( empty($studentid) || empty($amount) || empty($date) || empty($status) ){
			$alertMessage = "All fields must be filled";
			break;
		}

		$query="INSERT INTO fees(student_id, amount, payment_date, status)" .
		"VALUES ('$studentid', '$amount', '$date', '$status')";
		$result=$conn->query($query);

		if( !$result ){
			$alertMessage="Invalid query: " . $conn->error;
			break;
		}

		$studentid="";
		$amount="";
		$date="";
		$status ="";

		$successMessage="fee records added successfully.";

		header("Location: feedetails.php");
		exit();

	} while (false);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fee Record</title>
	<link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
	<div class="container">
		<h2>Fee Record</h2>

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
				<label for="amount">Amount</label><br>
				<input type="number" id="amount" name="amount" value="<?php echo $amount; ?>">
			</div><br>
			<div class="input-box">
				<label for="dt">PaymentDate</label><br>
				<input type="date" id="dt" name="dt" value="<?php echo $date; ?>">
			</div><br>
			<div class="dif-box">
                <label>Status</label>
                <label for="pending">
                	<input type="radio" id="pending" name="status" value="pending" <?php echo (isset($status) && $status == 'pending') ? 'checked' : ''; ?> required> Pending
                </label>
                <label for="paid">
                	<input type="radio" id="paid" name="status" value="paid" <?php echo (isset($status) && $status == 'paid') ? 'checked' : ''; ?>> Paid
                </label>
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
            <a href="feedetails.php"><button class="btn-btn">Cancel</button></a>
		</form>
		</form>
	</div>
</body>
</html>