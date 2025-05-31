<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

// Count total rows
$count_sql = "SELECT COUNT(*) AS total FROM results WHERE name LIKE ? OR roll LIKE ?";
$stmt = $conn->prepare($count_sql);
$likeSearch = "%$search%";
$stmt->bind_param("ss", $likeSearch, $likeSearch);
$stmt->execute();
$totalRows = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch data
$sql = "SELECT * FROM results WHERE name LIKE ? OR roll LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $likeSearch, $likeSearch, $limit, $offset);
$stmt->execute();
$results = $stmt->get_result();

// Fetch data for GPA chart (all CGPA)
$cgpa_result = $conn->query("SELECT cgpa FROM results");
$cgpa_data = [];
while ($row = $cgpa_result->fetch_assoc()) {
    $cgpa_data[] = floatval($row['cgpa']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Results Management</h3>
        <a href="Home.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- Search Form -->
    <form class="mb-3" method="GET" action="">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Name or Roll" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Add Result Button -->
    <a href="insert_result.php" class="btn btn-success mb-3">Add New Result</a>

    <!-- Bulk CSV Upload -->
    <form action="bulk_upload.php" method="POST" enctype="multipart/form-data" class="mb-3">
        <div class="input-group">
            <input type="file" name="csv_file" accept=".csv" class="form-control" required>
            <button class="btn btn-secondary" type="submit">Upload CSV</button>
        </div>
    </form>

    <!-- Results Table -->
    <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Roll</th>
                <th>Registration</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Exam Year</th>
                <th>CGPA</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($results->num_rows > 0): ?>
                <?php while($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['roll']) ?></td>
                    <td><?= htmlspecialchars($row['registration']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['father_name']) ?></td>
                    <td><?= $row['exam_year'] ?></td>
                    <td><?= $row['cgpa'] ?></td>
                    <td>
                        <a href="edit_result.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_result.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No results found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page-1 ?>">Previous</a></li>
            <?php endif; ?>
            <?php for($p=1; $p <= $totalPages; $p++): ?>
                <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $p ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
            <?php if($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page+1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- GPA Chart -->
    <h4 class="mt-5">CGPA Analytics</h4>
    <canvas id="gpaChart" height="100"></canvas>
</div>

<script>
const ctx = document.getElementById('gpaChart').getContext('2d');
const gpaData = <?= json_encode($cgpa_data) ?>;
const labels = gpaData.map((_, i) => i + 1);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'CGPA',
            data: gpaData,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    },
    options: {
        scales: {
            y: {
                suggestedMin: 0,
                suggestedMax: 5
            }
        }
    }
});
</script>

</body>
</html>
