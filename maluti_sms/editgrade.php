<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'MalutiDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = "";
$studentname = "";
$studentID = "";
$subject = "";
$grade = "";
$term = "";
$academic = "";
$alertMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("Location: gradedetails.php");
        exit();
    }

    $id = intval($_GET["id"]);
    $stmt = $conn->prepare("SELECT * FROM grades WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: gradedetails.php");
        exit();
    }

    $studentname = $row["studentname"];
    $studentID = $row["student_id"];
    $subject = $row["subject"];
    $grade = $row["grade"];
    $term = $row["term"];
    $academic = $row["Academicyear"];
    $stmt->close();

} else {
    $id = intval($_POST['id']);
    $studentname = $_POST['sname'];
    $studentID = $_POST['sid'];
    $subject = $_POST['sub'];
    $grade = $_POST['grade'];
    $term = $_POST['term'];
    $academic = $_POST['acyear'];

    if (empty($studentname) || empty($studentID) || empty($subject) || empty($grade) || empty($term) || empty($academic)) {
        $alertMessage = "All fields must be filled.";
    } else {
        $stmt = $conn->prepare("UPDATE grades SET studentname = ?, subject = ?, grade = ?, term = ?, Academicyear = ?, student_id =? WHERE id = ?");
        $stmt->bind_param("sssssii", $studentname, $subject, $grade, $term, $academic, $studentID, $id);

        if ($stmt->execute()) {
            $successMessage = "Grade updated successfully.";
            $stmt->close();
            header("Location: gradedetails.php");
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
    <title>Update Grade</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Update Grade</h2>

        <?php if (!empty($alertMessage)): ?>
            <div style="color: red;"><?php echo $alertMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div style="color: green;"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-box">
                <label for="sname">Studentname</label><br>
                <input type="text" id="sname" name="sname" value="<?php echo htmlspecialchars($studentname); ?>">
            </div><br>

            <div class="input-box">
                <label for="sid">StudentID</label><br>
                <input type="number" id="sid" name="sid" value="<?php echo htmlspecialchars($studentID); ?>">
            </div><br>

            <div class="input-box">
                <label for="sub">Subject</label><br>
                <input type="text" id="sub" name="sub" value="<?php echo htmlspecialchars($subject); ?>">
            </div><br>

            <div class="input-box">
                <label for="grade">Grade</label><br>
                <input type="number" id="grade" name="grade" value="<?php echo htmlspecialchars($grade); ?>">
            </div><br>

            <div class="input-box">
                <label for="term">Term</label><br>
                <input type="text" id="term" name="term" value="<?php echo htmlspecialchars($term); ?>">
            </div><br>

            <div class="input-box">
                <label for="acyear">Academicyear</label><br>
                <input type="text" id="acyear" name="acyear" value="<?php echo htmlspecialchars($academic); ?>">
            </div><br>

            <button type="submit" class="btn">Submit</button>
            <a href="gradedetails.php"><button class="btn-btn">Cancel</button></a>
        </form>
    </div>
</body>
</html>
