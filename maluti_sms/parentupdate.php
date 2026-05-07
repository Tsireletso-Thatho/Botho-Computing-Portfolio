<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

//database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$id = "";
$studentid = "";
$name = "";
$email = "";
$phone ="";
$relation = "";
$alertMessage="";
$successMessage = "";


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("Location: parentdetails.php");
        exit();
    }

    $id = intval($_GET["id"]);
    $stmt = $conn->prepare("SELECT * FROM parents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: parentdetails.php");
        exit();
    }

    $studentid = $row["student_id"];
    $name = $row["username"];
    $email = $row["email"];
    $phone = $row["phone"];
    $relation = $row["relationship"];
    $stmt->close();

} else {
    $id = intval($_POST['id']);
    $studentid = $_POST['sid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $relation = $_POST['rel'];

    if (empty($studentid) || empty($name) || empty($email) || empty($relation) || empty($phone)) {
        $alertMessage = "All fields must be filled.";
    } else {
        $stmt = $conn->prepare("UPDATE parents SET student_id = ?, username = ?, email = ?, phone = ?, relationship = ? WHERE id = ?");
        $stmt->bind_param("issssi", $studentid, $name, $email, $phone, $relation, $id);


        if ($stmt->execute()) {
            $successMessage = "Parent record updated successfully.";
            $stmt->close();
            header("Location: parentdetails.php");
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
    <title>Update Parent</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Update Parent</h2>

        <?php if (!empty($alertMessage)): ?>
            <div class="message error"><?php echo $alertMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-box">
                <label for="sid">Student</label><br>
                <input type="number" id="sid" name="sid" value="<?php echo htmlspecialchars($studentid); ?>">
            </div><br>

            <div class="input-box">
                <label for="name">ParentUsername</label><br>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </div><br>

            <div class="input-box">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div><br>

            <div class="input-box">
                <label for="phone">Phonenumber</label><br>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            </div><br>

            <div class="input-box">
                <label for="rel">Relationship</label><br>
                <input type="text" id="rel" name="rel" value="<?php echo htmlspecialchars($relation); ?>">
            </div><br>

            <button type="Submit" class="btn">Submit</button><br>
            <a href="parentdetails.php"><button class="btn-btn">Cancel</button></a>
        </form>
    </div>
</body>
</html>
