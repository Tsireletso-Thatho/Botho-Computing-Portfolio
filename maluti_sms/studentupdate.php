<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connecting to the database
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstname = $lastname = $dateofbirth = $gender = $enrollment = "";
$alertMessage = $successMessage = "";
$id = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["student_id"])) {
        header("Location: studentdetails.php");
        exit();
    }

    $id = intval($_GET["student_id"]);
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: studentdetails.php");
        exit();
    }

    $firstname = $row["firstname"];
    $lastname = $row["lastname"];
    $dateofbirth = $row["dateofbirth"];
    $gender = $row["gender"];
    $enrollment = $row["enrollmentdate"];
    $stmt->close();

} else {
    $id = intval($_POST['student_id']);
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $dateofbirth = $_POST['dob'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $enrollment = $_POST['enroll'];

    if (empty($firstname) || empty($lastname) || empty($dateofbirth) || empty($gender) || empty($enrollment)) {
        $alertMessage = "All fields must be filled.";
    } else {
        $stmt = $conn->prepare("UPDATE students SET firstname = ?, lastname = ?, dateofbirth = ?, gender = ?, enrollmentdate = ? WHERE student_id = ?");
        $stmt->bind_param("sssssi", $firstname, $lastname, $dateofbirth, $gender, $enrollment, $id);

        if ($stmt->execute()) {
            $successMessage = "Student updated successfully.";
            $stmt->close();
            header("Location: studentdetails.php");
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
    <title>Update Student</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Update Student</h2>

        <?php if (!empty($alertMessage)): ?>
            <div class="message error"><?php echo $alertMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-box">
                <label for="fname">Firstname</label><br>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($firstname); ?>">
            </div><br>

            <div class="input-box">
                <label for="lname">Lastname</label><br>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lastname); ?>">
            </div><br>

            <div class="input-box">
                <label for="dob">Date of Birth</label><br>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dateofbirth); ?>">
            </div><br>

            <div class="dif-box">
                <label>Gender</label><br>
                <label><input type="radio" name="gender" value="male" <?php echo ($gender == 'male') ? 'checked' : ''; ?>> Male</label>
                <label><input type="radio" name="gender" value="female" <?php echo ($gender == 'female') ? 'checked' : ''; ?>> Female</label>
                <label><input type="radio" name="gender" value="other" <?php echo ($gender == 'other') ? 'checked' : ''; ?>> Other</label>
            </div><br>

            <div class="input-box">
                <label for="enroll">Enrollment Date</label><br>
                <input type="date" id="enroll" name="enroll" value="<?php echo htmlspecialchars($enrollment); ?>">
            </div><br>

            <button type="Submit" class="btn">Submit</button><br>
            <a href="studentdetails.php"><button class="btn-btn">Cancel</button></a>
        </form>
    </div>
</body>
</html>
