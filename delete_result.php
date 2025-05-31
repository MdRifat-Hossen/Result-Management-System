<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin'])) die("Unauthorized access!");

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid ID");

$conn->query("DELETE FROM results WHERE id = $id");
header("Location: admin_deshbord.php");
exit();
?>
