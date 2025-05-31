<?php
// Show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load TCPDF
require_once('TCPDF/tcpdf.php');
include 'db.php'; // your db connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $result = $conn->query("SELECT * FROM results WHERE id=$id");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Custom PDF with watermark
        class MYPDF extends TCPDF {
            public function Header() {
                $this->SetAlpha(0.05);
                $this->Image('download.png', 50, 40, 110, 110, '', '', '', false, 300);
                $this->SetAlpha(1);
            }
        }

        $pdf = new MYPDF();
        $pdf->SetTitle("Student Result Sheet");
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        // University Header
        $pdf->Image('download.jpg', 12, 10, 18);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 6, 'Pabna University of Science and Technology', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Department of Information and Communication Engineering', 0, 1, 'C');
        $pdf->Cell(0, 5, '3rd Year 1st Semester | Year: 2025', 0, 1, 'C');
        $pdf->Ln(2);

        // QR Code
        $student_data = "Name: {$row['name']}\nRoll: {$row['roll']}\nReg: {$row['registration']}";
        $style = ['border' => 0, 'vpadding' => 'auto', 'hpadding' => 'auto', 'fgcolor' => [0,0,0], 'bgcolor' => false];
        $pdf->write2DBarcode($student_data, 'QRCODE,H', 165, 12, 22, 22, $style, 'N');

        // Student Info
        $html = "<h4 style='text-align:center;'>Result Sheet</h4>
        <table cellpadding='2' style='font-size:9pt;'>
            <tr><td width='25%'><strong>Name:</strong></td><td width='75%'>{$row['name']}</td></tr>
            <tr><td><strong>Roll:</strong></td><td>{$row['roll']}</td></tr>
            <tr><td><strong>Registration:</strong></td><td>{$row['registration']}</td></tr>
            <tr><td><strong>Father's Name:</strong></td><td>{$row['father_name']}</td></tr>
        </table><br>";

        // Subjects & Grades Table
        $html .= '<table border="1" cellpadding="3" style="font-size:8.5pt; text-align:center;">
            <thead>
                <tr style="background-color:#e6e6e6;">
                    <th width="70%">Subject</th>
                    <th width="30%">Grade</th>
                </tr>
            </thead><tbody>';
        for ($i = 1; $i <= 10; $i++) {
            $subject = $row["subject{$i}_name"];
            $grade = $row["subject{$i}_grade"];
            if (!empty($subject) && !empty($grade)) {
                $html .= "<tr><td align='left'>$subject</td><td>$grade</td></tr>";
            }
        }
        $html .= "</tbody></table><br>";

        // Summary
        $html .= "<table cellpadding='2' style='font-size:9pt;'>
            <tr><td width='40%'><strong>Total Credit:</strong></td><td width='60%'>{$row['total_credit']}</td></tr>
            <tr><td><strong>Earned Credit:</strong></td><td>{$row['earned_credit']}</td></tr>
            <tr><td><strong>CGPA:</strong></td><td>{$row['cgpa']}</td></tr>
        </table><br>";

        // Grading System
        $html .= "<h5 style='text-align:center;'>Grading System</h5>";
        $html .= '<table border="1" cellpadding="3" style="font-size:8pt;">
            <thead style="background-color:#f2f2f2;">
                <tr>
                    <th width="40%">Marks Range</th>
                    <th width="30%">Grade</th>
                    <th width="30%">Grade Point</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>80 – 100</td><td align="center">A+</td><td align="center">4.00</td></tr>
                <tr><td>75 – 79</td><td align="center">A</td><td align="center">3.75</td></tr>
                <tr><td>70 – 74</td><td align="center">A−</td><td align="center">3.50</td></tr>
                <tr><td>65 – 69</td><td align="center">B+</td><td align="center">3.25</td></tr>
                <tr><td>60 – 64</td><td align="center">B</td><td align="center">3.00</td></tr>
                <tr><td>55 – 59</td><td align="center">B−</td><td align="center">2.75</td></tr>
                <tr><td>50 – 54</td><td align="center">C+</td><td align="center">2.50</td></tr>
                <tr><td>45 – 49</td><td align="center">C</td><td align="center">2.25</td></tr>
                <tr><td>40 – 44</td><td align="center">D</td><td align="center">2.00</td></tr>
                <tr><td>00 – 39</td><td align="center">F</td><td align="center">0.00</td></tr>
            </tbody>
        </table><br>';

    

    

        // Output the HTML to PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('result_sheet.pdf', 'I');
    } else {
        echo "No result found for the provided ID.";
    }
} else {
    echo "Invalid request. Please provide student ID via POST.";
}
?>
