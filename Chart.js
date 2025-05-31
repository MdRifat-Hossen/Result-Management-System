<div class="container mt-5">
    <h4>GPA Analytics</h4>
    <canvas id="gpaChart" style="width:100%; max-width:700px;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('gpaChart').getContext('2d');

<?php
// Prepare data for Chart.js
$gpa_data = [];
$gpa_labels = [];
$gpa_sql = "SELECT exam_year, AVG(cgpa) as avg_cgpa FROM results GROUP BY exam_year ORDER BY exam_year ASC";
$gpa_result = $conn->query($gpa_sql);
while ($row = $gpa_result->fetch_assoc()) {
    $gpa_labels[] = $row['exam_year'];
    $gpa_data[] = round($row['avg_cgpa'], 2);
}
?>

const labels = <?= json_encode($gpa_labels) ?>;
const data = <?= json_encode($gpa_data) ?>;

const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Average CGPA per Exam Year',
            data: data,
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                suggestedMin: 0,
                suggestedMax: 4
            }
        }
    }
});
</script>
