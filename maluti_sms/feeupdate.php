<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

//database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = "";
$pay = "";
$status = "";
$amount = "";
$studentid = "";
$alertMessage="";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("Location: feedetails.php");
        exit();
    }

    $id = intval($_GET["id"]);
    $stmt = $conn->prepare("SELECT * FROM fees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: feedetails.php");
        exit();
    }

    $pay = $row["payment_date"];
    $status = $row["status"];
    $amount = $row["amount"];
    $studentid = $row["student_id"];
    $stmt->close();

} else {
    $id = intval($_POST['id']);
    $pay = $_POST['pay'];
    $status = $_POST['status'];
    $amount = $_POST['am'];
    $studentid = $_POST['sid'];

    if (empty($pay) || empty($status) || empty($amount) || empty($studentid)) {
        $alertMessage = "All fields must be filled.";
    } else {
        $stmt = $conn->prepare("UPDATE fees SET student_id = ?, amount = ?, payment_date = ?, status = ? WHERE id = ?");
        $stmt->bind_param("idssi", $studentid, $amount, $pay, $status, $id);

        if ($stmt->execute()) {
            $successMessage = "Record updated successfully.";
            $stmt->close();
            header("Location: feedetails.php");
            exit();
        } else {
            $alertMessage = "Update failed: " . $stmt->error;
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Fee Records</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Update Fee</h2>

        <?php if (!empty($alertMessage)): ?>
            <div class="message error"><?php echo $alertMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-box">
                <label for="sid">StudentID</label><br>
                <input type="number" id="sid" name="sid" value="<?php echo htmlspecialchars($studentid); ?>">
            </div><br>

            <div class="input-box">
                <label for="am">Amount</label><br>
                <input type="number" id="am" name="am" value="<?php echo htmlspecialchars($amount); ?>">
            </div><br>

            <div class="input-box">
                <label for="pay">Paymentdate</label><br>
                <input type="date" id="pay" name="pay" value="<?php echo htmlspecialchars($pay); ?>">
            </div><br>
            
            <div class="dif-box">
                <label>Status</label>
                <label for="pending">
                    <input type="radio" id="pending" name="status" value="pending" <?php echo ($status == 'pending') ? 'checked' : ''; ?> required> Pending
                </label>
                <label for="paid">
                    <input type="radio" id="paid" name="status" value="paid" <?php echo ($status == 'paid') ? 'checked' : ''; ?>> Paid
                </label>
            </div><br>

            <button type="Submit" class="btn">Submit</button><br>
            <a href="feedetails.php"><button class="btn-btn">Cancel</button></a>
    </div>
</body>
</html>
