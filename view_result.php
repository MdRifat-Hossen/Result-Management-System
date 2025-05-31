<?php require "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>View Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 p-4 bg-white rounded shadow">
    <h3 class="mb-4 text-center">Search Student Result</h3>
    <form method="GET">
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="roll" class="form-control" placeholder="Roll" required>
            </div>
            <div class="col">
                <input type="text" name="registration" class="form-control" placeholder="Registration" required>
            </div>
            <div class="col">
                <input type="text" name="exam_year" class="form-control" placeholder="Exam Year" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Search Result</button>
    </form>

<?php
if (isset($_GET['roll'])) {
    $roll = $_GET['roll'];
    $reg = $_GET['registration'];
    $year = $_GET['exam_year'];

    $result = $conn->query("SELECT * FROM results WHERE roll='$roll' AND registration='$reg' AND exam_year='$year'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='mt-4'>
              <h5>Result of: {$row['name']}</h5>
              <p><strong>Roll:</strong> {$row['roll']} | <strong>Reg:</strong> {$row['registration']}</p>
              <p><strong>Father Name:</strong> {$row['father_name']}</p>
              <table class='table table-bordered'>
              <thead><tr><th>Subject</th><th>Grade</th></tr></thead><tbody>";

        for ($i = 1; $i <= 10; $i++) {
            echo "<tr><td>{$row["subject{$i}_name"]}</td><td>{$row["subject{$i}_grade"]}</td></tr>";
        }

        echo "</tbody></table>
              <p><strong>Total Credit:</strong> {$row['total_credit']} | 
              <strong>Earned:</strong> {$row['earned_credit']} | 
              <strong>CGPA:</strong> {$row['cgpa']}</p>";

        // 👇 Add Export PDF button
        echo "<form method='POST' action='export_pdf.php' target='_blank'>
                <input type='hidden' name='id' value='{$row['id']}'>
                <button type='submit' class='btn btn-danger'>Download PDF</button>
              </form>";
    

        echo "</div>";
    } else {
        echo "<p class='text-danger mt-3'>No result found.</p>";
    }
}
?>
<a href="Home.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
