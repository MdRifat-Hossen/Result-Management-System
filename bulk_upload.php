<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, "r");
    if ($handle !== FALSE) {
        // Skip header row (if exists)
        $header = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            // CSV Columns order:
            // roll, registration, name, father_name, exam_year, cgpa, total_credit, earned_credit,
            // subject1_name, subject1_grade, ..., subject10_name, subject10_grade
            
            $roll = $conn->real_escape_string($data[0]);
            $registration = $conn->real_escape_string($data[1]);
            $name = $conn->real_escape_string($data[2]);
            $father_name = $conn->real_escape_string($data[3]);
            $exam_year = intval($data[4]);
            $cgpa = floatval($data[5]);
            $total_credit = floatval($data[6]);
            $earned_credit = floatval($data[7]);
            
            $subjects = [];
            for ($i = 0; $i < 10; $i++) {
                $subjects['subject'.($i+1).'_name'] = $conn->real_escape_string($data[8 + $i*2]);
                $subjects['subject'.($i+1).'_grade'] = $conn->real_escape_string($data[9 + $i*2]);
            }

            // Insert SQL
            $sql = "INSERT INTO results 
            (roll, registration, name, father_name, exam_year, cgpa, total_credit, earned_credit,
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

            $conn->query($sql);
        }
        fclose($handle);
        header("Location: admin_deshbord.php");
        exit();
    } else {
        echo "Failed to open CSV file.";
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
