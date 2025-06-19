<?php
require 'db.php';

// Fetch the 5 most recent issues
$stmt = $conn->prepare("SELECT id, category, location, description, status, date_reported FROM issues ORDER BY date_reported DESC LIMIT 5");
$stmt->execute();
$issues = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NeighborlyFix - Home</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f4f4f4;
    }
    header {
      background-color: #4CAF50;
      color: white;
      text-align: center;
      padding: 20px 0;
    }
    nav {
      background-color: #333;
      padding: 10px;
      text-align: center;
    }
    nav a {
      color: white;
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
      cursor: pointer;
    }
    nav a:hover {
      color: #e9c46a;
    }
    .container {
      max-width: 1000px;
      margin: 30px auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    p, ol {
      font-size: 16px;
      line-height: 1.6;
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      display: none;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #4CAF50;
      color: white;
    }
    tr:hover {
      background-color: #f0f0f0;
    }
    footer {
      background-color: #333;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
    }
    #show-button {
      display: block;
      width: fit-content;
      margin: 25px auto;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    #show-button:hover {
      background-color: #388e3c;
    }
  </style>
</head>
<body>

<header>
  <h1>NeighborlyFix</h1>
  <p>Report local issues and make your community better</p>
</header>

<nav>
  <a href="login.php">Login</a>
  <a href="dashboard.php#report">Report Issue</a>
  <a onclick="toggleIssues()">Track Issues</a>
</nav>

<div class="container">
  <h2>Welcome to NeighborlyFix</h2>
  <p>NeighborlyFix is a community platform that enables residents to report and track neighborhood issues such as potholes, broken streetlights, and garbage. Your voice helps improve your environment!</p>

  <h2>How to Report an Issue</h2>
  <ol>
    <li><strong>Login</strong> to your account.</li>
    <li>Click <strong>"Report Issue"</strong> to open the issue form.</li>
    <li>Fill in the category, location, and description.</li>
    <li>Upload a photo (optional).</li>
    <li>Click <strong>Submit</strong>. Your issue will be listed and tracked.</li>
  </ol>

  <button id="show-button" onclick="toggleIssues()">Click to View Reported Issues</button>

  <table id="issues-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Category</th>
        <th>Location</th>
        <th>Description</th>
        <th>Status</th>
        <th>Date Reported</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($issues->num_rows > 0): ?>
        <?php while($row = $issues->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td><?= htmlspecialchars($row['location']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= htmlspecialchars($row['date_reported']) ?></td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6">No issues have been reported yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<footer>
  &copy; <?= date("Y") ?> NeighborlyFix. All rights reserved.
</footer>

<script>
function toggleIssues() {
  const table = document.getElementById("issues-table");
  const button = document.getElementById("show-button");
  if (table.style.display === "none" || table.style.display === "") {
    table.style.display = "table";
    if (button) button.textContent = "Hide Reported Issues";
  } else {
    table.style.display = "none";
    if (button) button.textContent = "Click to View Reported Issues";
  }
}
</script>

<?php
$stmt->close();
$conn->close();
?>
</body>
</html>
