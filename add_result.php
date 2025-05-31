<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) die("Unauthorized access!");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    $sql = "INSERT INTO results (roll, registration, name, father_name, exam_year, cgpa, total_credit, earned_credit,
            subject1_name, subject1_grade, subject2_name, subject2_grade, subject3_name, subject3_grade,
            subject4_name, subject4_grade, subject5_name, subject5_grade, subject6_name, subject6_grade,
            subject7_name, subject7_grade, subject8_name, subject8_grade, subject9_name, subject9_grade,
            subject10_name, subject10_grade) VALUES (
                '$roll', '$registration', '$name', '$father_name', $exam_year, $cgpa, $total_credit, $earned_credit,
                '{$subjects['subject1_name']}', '{$subjects['subject1_grade']}',
                '{$subjects['subject2_name']}', '{$subjects['subject2_grade']}',
                '{$subjects['subject3_name']}', '{$subjects['subject3_grade']}',
                '{$subjects['subject4_name']}', '{$subjects['subject4_grade']}',
                '{$subjects['subject5_name']}', '{$subjects['subject5_grade']}',
                '{$subjects['subject6_name']}', '{$subjects['subject6_grade']}',
                '{$subjects['subject7_name']}', '{$subjects['subject7_grade']}',
                '{$subjects['subject8_name']}', '{$subjects['subject8_grade']}',
                '{$subjects['subject9_name']}', '{$subjects['subject9_grade']}',
                '{$subjects['subject10_name']}', '{$subjects['subject10_grade']}'
            )";

    if ($conn->query($sql)) {
        header("Location: admin_deshbord.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Add New Student Result</h3>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="roll" placeholder="Roll" class="form-control mb-2" required>
        <input type="text" name="registration" placeholder="Registration" class="form-control mb-2" required>
        <input type="text" name="name" placeholder="Student Name" class="form-control mb-2" required>
        <input type="text" name="father_name" placeholder="Father's Name" class="form-control mb-2" required>
        <input type="number" name="exam_year" placeholder="Exam Year" class="form-control mb-2" required>
        <input type="number" step="0.01" name="cgpa" placeholder="CGPA" class="form-control mb-2" required>
        <input type="number" step="0.01" name="total_credit" placeholder="Total Credit" class="form-control mb-2" required>
        <input type="number" step="0.01" name="earned_credit" placeholder="Earned Credit" class="form-control mb-2" required>

        <?php for($i=1; $i<=10; $i++): ?>
        <div class="row mb-2">
            <div class="col">
                <input type="text" name="subject<?= $i ?>_name" placeholder="Subject <?= $i ?> Name" class="form-control" required>
            </div>
            <div class="col">
                <input type="text" name="subject<?= $i ?>_grade" placeholder="Subject <?= $i ?> Grade" class="form-control" required>
            </div>
        </div>
        <?php endfor; ?>

        <button class="btn btn-primary w-100" type="submit">Add Result</button>
    </form>
    <a href="admin_deshbord.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
