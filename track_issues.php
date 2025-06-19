<?php
require 'db.php'; // Make sure this connects to your DB

// Fetch all reported issues
$stmt = $conn->prepare("SELECT ID, description, category, status, date_reported, location FROM issues ORDER BY date_reported DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Track Reported Issues</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f1f1f1;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #2a9d8f;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .container {
      max-width: 1000px;
      margin: 30px auto;
      background: white;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th {
      background-color: #264653;
      color: white;
      padding: 10px;
    }
    td {
      padding: 10px;
    }
    tr:hover {
      background-color: #f0f0f0;
    }
    .back-link {
      display: block;
      margin: 10px auto;
      text-align: center;
    }
    .back-link a {
      color: #2a9d8f;
      text-decoration: none;
      font-weight: bold;
    }
    .back-link a:hover {
      color: #e76f51;
    }
  </style>
</head>
<body>

<header>
  <h1>Track Reported Issues</h1>
  <p>View all issues reported by the community</p>
</header>

<div class="container">
  <h2>Reported Issues</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>ID</th>
        <th>Description</th>
        <th>Category</th>
        <th>Status</th>
        <th>Location</th>
        <th>Date Reported</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['ID']); ?></td>
        <td><?php echo htmlspecialchars($row['description']); ?></td>
        <td><?php echo htmlspecialchars($row['category']); ?></td>
        <td><?php echo htmlspecialchars($row['status']); ?></td>
        <td><?php echo htmlspecialchars($row['location']); ?></td>
        <td><?php echo htmlspecialchars($row['date_reported']); ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No issues have been reported yet.</p>
  <?php endif; ?>

  <div class="back-link">
    <a href="homepage.php">← Back to Home</a>
  </div>
</div>

</body>
</html>
