<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = "";
$classname = "";
$teachername = "";
$studentname = "";
$academic = "";
$alertMessage = "";
$successMessage = "";


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["class_id"])) {
        header("Location: assignstu2classes.php");
        exit();
    }

    $id = intval($_GET["class_id"]);
    $stmt = $conn->prepare("SELECT * FROM classes WHERE class_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: assignstu2classes.php");
        exit();
    }

    $classname = $row["classname"];
    $teachername = $row["teachername"];
    $studentname = $row["studentname"];
    $academic = $row["academicyear"];
    $stmt->close();

} else {
    $id = intval($_POST['class_id']);
    $classname = $_POST['cname'];
    $teachername = $_POST['tname'];
    $studentname = $_POST['sname'];
    $academic = $_POST['aca'];

    if (empty($classname) || empty($teachername) || empty($studentname) || empty($academic)) {
        $alertMessage = "All fields must be filled.";
    } else {
        $stmt = $conn->prepare("UPDATE classes SET classname = ?, teachername = ?, studentname = ?, academicyear = ? WHERE class_id = ?");
        $stmt->bind_param("ssssi", $classname, $teachername, $studentname, $academic, $id);

        if ($stmt->execute()) {
            $successMessage = "Class updated successfully.";
            $stmt->close();
            header("Location: assignstu2classes.php");
            exit();
        } else {
            $alertMessage = "Failed to update: " . $stmt->error;
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Assigned Class</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Update Assigned Class</h2>

        <?php if (!empty($alertMessage)): ?>
            <div class="message error"><?php echo $alertMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-box">
                <label for="cname">Classname</label><br>
                <input type="text" id="cname" name="cname" value="<?php echo htmlspecialchars($classname); ?>">
            </div><br>

            <div class="input-box">
                <label for="tname">Teachername</label><br>
                <input type="text" id="tname" name="tname" value="<?php echo htmlspecialchars($teachername); ?>">
            </div><br>

            <div class="input-box">
                <label for="sname">Studentname</label><br>
                <input type="text" id="sname" name="sname" value="<?php echo htmlspecialchars($studentname); ?>">
            </div><br>

            <div class="input-box">
                <label for="aca">Academicyear</label><br>
                <input type="text" id="aca" name="aca" value="<?php echo htmlspecialchars($academic); ?>">
            </div><br>

            <button type="Submit" class="btn">Submit</button><br>
            <a href="assignstu2classes.php"><button class="btn-btn">Cancel</button></a>
        </form>
    </div>
</body>
</html>
