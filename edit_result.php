<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) die("Unauthorized access!");

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid ID");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $roll = $conn->real_escape_string($_POST['roll']);
    $registration = $conn->real_escape_string($_POST['registration']);
    $name = $conn->real_escape_string($_POST['name']);
    $father_name = $conn->real_escape_string($_POST['father_name']);
    $exam_year = intval($_POST['exam_year']);
    $cgpa = floatval($_POST['cgpa']);
    $total_credit = floatval($_POST['total_credit']);
    $earned_credit = floatval($_POST['earned_credit']);

    $subjects = [];
    for ($i=1; $i<=10; $i++) {
        $subjects["subject{$i}_name"] = $conn->real_escape_string($_POST["subject{$i}_name"]);
        $subjects["subject{$i}_grade"] = $conn->real_escape_string($_POST["subject{$i}_grade"]);
    }

    $sql = "UPDATE results SET 
        roll='$roll', registration='$registration', name='$name', father_name='$father_name',
        exam_year=$exam_year, cgpa=$cgpa, total_credit=$total_credit, earned_credit=$earned_credit,
        subject1_name='{$subjects['subject1_name']}', subject1_grade='{$subjects['subject1_grade']}',
        subject2_name='{$subjects['subject2_name']}', subject2_grade='{$subjects['subject2_grade']}',
        subject3_name='{$subjects['subject3_name']}', subject3_grade='{$subjects['subject3_grade']}',
        subject4_name='{$subjects['subject4_name']}', subject4_grade='{$subjects['subject4_grade']}',
        subject5_name='{$subjects['subject5_name']}', subject5_grade='{$subjects['subject5_grade']}',
        subject6_name='{$subjects['subject6_name']}', subject6_grade='{$subjects['subject6_grade']}',
        subject7_name='{$subjects['subject7_name']}', subject7_grade='{$subjects['subject7_grade']}',
        subject8_name='{$subjects['subject8_name']}', subject8_grade='{$subjects['subject8_grade']}',
        subject9_name='{$subjects['subject9_name']}', subject9_grade='{$subjects['subject9_grade']}',
        subject10_name='{$subjects['subject10_name']}', subject10_grade='{$subjects['subject10_grade']}'
        WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: admin_deshbord.php");
        exit();
    } else {
        $error = "Update failed: " . $conn->error;
    }
} else {
    $sql = "SELECT * FROM results WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) die("Result not found");
    $row = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Edit Student Result</h3>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">

    <input type="text" name="roll" class="form-control mb-2"
           value="<?= htmlspecialchars($row['roll']) ?>" placeholder="Enter Roll" required>

    <input type="text" name="registration" class="form-control mb-2"
           value="<?= htmlspecialchars($row['registration']) ?>" placeholder="Enter Registration Number" required>

    <input type="text" name="name" class="form-control mb-2"
           value="<?= htmlspecialchars($row['name']) ?>" placeholder="Enter Student Name" required>

    <input type="text" name="father_name" class="form-control mb-2"
           value="<?= htmlspecialchars($row['father_name']) ?>" placeholder="Enter Father's Name" required>

    <input type="number" name="exam_year" class="form-control mb-2"
           value="<?= $row['exam_year'] ?>" placeholder="Enter Exam Year" required>

    <input type="number" step="0.01" name="cgpa" class="form-control mb-2"
           value="<?= $row['cgpa'] ?>" placeholder="Enter CGPA" required>

    <input type="number" step="0.01" name="total_credit" class="form-control mb-2"
           value="<?= $row['total_credit'] ?>" placeholder="Enter Total Credit" required>

    <input type="number" step="0.01" name="earned_credit" class="form-control mb-2"
           value="<?= $row['earned_credit'] ?>" placeholder="Enter Earned Credit" required>

    <?php for($i=1; $i<=10; $i++): ?>
    <div class="row mb-2">
        <div class="col">
            <input type="text" name="subject<?= $i ?>_name" class="form-control"
                   value="<?= htmlspecialchars($row["subject{$i}_name"]) ?>" placeholder="Subject <?= $i ?> Name" required>
        </div>
        <div class="col">
            <input type="text" name="subject<?= $i ?>_grade" class="form-control"
                   value="<?= htmlspecialchars($row["subject{$i}_grade"]) ?>" placeholder="Subject <?= $i ?> Grade" required>
        </div>
    </div>
    <?php endfor; ?>

    <button class="btn btn-success w-100" type="submit">Update Result</button>
</form>
    <a href="admin_deshbord.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
