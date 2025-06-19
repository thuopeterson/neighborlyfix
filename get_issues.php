<?php
require 'db.php';

$result = $conn->query("SELECT id, category, location, status FROM issues ORDER BY id DESC");
$issues = [];

while ($row = $result->fetch_assoc()) {
  $issues[] = $row;
}

header('Content-Type: application/json');
echo json_encode($issues);
?>
