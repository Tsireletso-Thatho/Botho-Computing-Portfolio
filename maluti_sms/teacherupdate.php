<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Connecting to the database
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = "";
$firstname="";
$lastname="";
$subject="";
$email = "";
$alertMessage="";
$successMessage = "";


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("Location: teacherdetails.php");
        exit();
    }

    $id = intval($_GET["id"]);
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: teacherdetails.php");
        exit();
    }

    $firstname = $row["firstname"];
    $lastname = $row["lastname"];
    $subject = $row["subject"];
    $email = $row["email"];
    $stmt->close();

} else {
    $id = intval($_POST['id']);
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $subject = $_POST['sub'];
    $email = $_POST['email'];

    if (empty($firstname) || empty($lastname) || empty($subject) || empty($email)) {
        $alertMessage = "All fields must be filled.";
    } else {
        $stmt = $conn->prepare("UPDATE teachers SET firstname = ?, lastname = ?, subject = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $firstname, $lastname, $subject, $email, $id);

        if ($stmt->execute()) {
            $successMessage = "Teacher updated successfully.";
            $stmt->close();
            header("Location: teacherdetails.php");
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
    <title>Update teacher</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Update teacher</h2>

        <?php if (!empty($alertMessage)): ?>
            <div class="message error"><?php echo $alertMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-box">
                <label for="fname">Firstname</label><br>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($firstname); ?>">
            </div><br>

            <div class="input-box">
                <label for="lname">Lastname</label><br>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lastname); ?>">
            </div><br>

            <div class="input-box">
                <label for="sub">Subject</label><br>
                <input type="text" id="sub" name="sub" value="<?php echo htmlspecialchars($subject); ?>">
            </div><br>

            <div class="input-box">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div><br>

            <button type="Submit" class="btn">Submit</button><br>
            <a href="teacherdetails.php"><button class="btn-btn">Cancel</button></a>
        </form>
    </div>
</body>
</html>
