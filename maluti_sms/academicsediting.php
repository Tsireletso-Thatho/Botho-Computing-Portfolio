<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'MalutiDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = "";
$studentname = "";
$gradelevel = "";
$subject = "";
$term = "";
$academic = "";
$grade = "";
$alertMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['id'])) {
        header("Location: updateacademics.php");
        exit();
    }

    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM academics WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: updateacademics.php");
        exit();
    }

    $studentname = $row['studentname'];
    $gradelevel = $row['gradelevel'];
    $subject = $row['subject'];
    $term = $row['term'];
    $academic = $row['academicyear'];
    $grade = $row['grade'];

    $stmt->close();
} else {
    $id = intval($_POST['id']);
    $studentname = $_POST['sname'];
    $gradelevel = $_POST['gradel'];
    $subject = $_POST['sub'];
    $term = $_POST['term'];
    $academic = $_POST['acyear'];
    $grade = $_POST['grade'];

    if (empty($studentname) || empty($gradelevel) || empty($subject) || empty($term) || empty($academic) || empty($grade)) {
        $alertMessage = "All fields must be filled.";
    } else {
        $stmt = $conn->prepare("UPDATE academics SET studentname=?, gradelevel=?, subject=?, term=?, academicyear=?, grade=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("ssssssi", $studentname, $gradelevel, $subject, $term, $academic, $grade, $id);

        if ($stmt->execute()) {
            $successMessage = "Academic record updated successfully.";
            $stmt->close();
            header("Location: updateacademics.php");
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
    <title>Update Academic Record</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Update Academic Record</h2>

        <?php if (!empty($alertMessage)): ?>
            <div><strong><?php echo $alertMessage; ?></strong></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <div class="input-box">
                <label for="sname">Student Name</label><br>
                <input type="text" id="sname" name="sname" value="<?php echo htmlspecialchars($studentname); ?>">
            </div><br>

            <div class="input-box">
                <label for="gradel">Grade Level</label><br>
                <input type="text" id="gradel" name="gradel" value="<?php echo htmlspecialchars($gradelevel); ?>">
            </div><br>

            <div class="input-box">
                <label for="sub">Subject</label><br>
                <input type="text" id="sub" name="sub" value="<?php echo htmlspecialchars($subject); ?>">
            </div><br>

            <div class="input-box">
                <label for="term">Term</label><br>
                <input type="text" id="term" name="term" value="<?php echo htmlspecialchars($term); ?>">
            </div><br>

            <div class="input-box">
                <label for="acyear">Academic Year</label><br>
                <input type="text" id="acyear" name="acyear" value="<?php echo htmlspecialchars($academic); ?>">
            </div><br>

            <div class="input-box">
                <label for="grade">Grade</label><br>
                <input type="number" id="grade" name="grade" value="<?php echo htmlspecialchars($grade); ?>">
            </div><br>

            <?php if (!empty($successMessage)): ?>
                <div><strong><?php echo $successMessage; ?></strong></div>
            <?php endif; ?>

            <button type="Submit" class="btn">Submit</button><br>
            <a href="updateacademics.php"><button class="btn-btn">Cancel</button></a>
        </form>
    </div>
</body>
</html>

