<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = "";
$subjectname="";
$teachername="";
$academic="";
$alertMessage="";
$successMessage = "";


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["subject_id"])) {
        header("Location: assignsub2teachers.php");
        exit();
    }

    $id = intval($_GET["subject_id"]);
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: assignsub2teachers.php");
        exit();
    }

    $subjectname = $row["subjectname"];
    $teachername = $row["teachername"];
    $academic = $row["academicyear"];
    $stmt->close();

} else {
    $id = intval($_POST['subject_id']);
    $subjectname = $_POST['cname'];
    $teachername = $_POST['tname'];
    $academic = $_POST['aca'];

    if (empty($subjectname) || empty($teachername) || empty($academic)) {
        $alertMessage = "All fields must be filled.";
    } else {
        $stmt = $conn->prepare("UPDATE subjects SET subjectname = ?, teachername = ?, studentname = ?, academicyear = ? WHERE subject_id = ?");
        $stmt->bind_param("ssssi", $subjectname, $teachername, $studentname, $academic, $id);

        if ($stmt->execute()) {
            $successMessage = "Record updated successfully.";
            $stmt->close();
            header("Location: assignsub2teachers.php");
            exit();
        } else {
            $alertMessage = "Failed to assign: " . $stmt->error;
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assign Teachers to Subjects</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Assign Teachers to Subjects</h2>

        <?php if (!empty($alertMessage)): ?>
            <div class="message error"><?php echo $alertMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-box">
                <label for="cname">Classname</label><br>
                <input type="text" id="cname" name="cname" value="<?php echo htmlspecialchars($subjectname); ?>">
            </div><br>

            <div class="input-box">
                <label for="tname">teachername</label><br>
                <input type="text" id="tname" name="tname" value="<?php echo htmlspecialchars($teachername); ?>">
            </div><br>

            <div class="input-box">
                <label for="aca">Academicyear</label><br>
                <input type="number" id="aca" name="aca" value="<?php echo htmlspecialchars($academic); ?>">
            </div><br>

            <button type="Submit" class="btn">Submit</button><br>
            <a href="assignsub2teachers.php"><button class="btn-btn">Cancel</button></a>
        </form>
    </div>
</body>
</html>
