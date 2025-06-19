<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user'];

// Fetch only the latest 5 issues reported by this user
$stmt = $conn->prepare("SELECT * FROM issues WHERE user_email = ? ORDER BY id DESC LIMIT 5");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$issues = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>NeighborlyFix - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            text-align: center;
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
        }
        nav a.logout {
            color: red;
            float: right;
            margin-right: 20px;
        }
        .container {
            padding: 20px;
        }
        .issue-form, .issue-list {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        img {
            max-width: 80px;
            height: auto;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
<header>
    <h1>NeighborlyFix</h1>
    <p>Report Local Issues. Track Progress.</p>
</header>

<nav>
    <a href="#report">Report Issue</a>
    <a href="#track">Track Issues</a>
    <a href="logout.php" class="logout">Logout</a>
    <a href="homepage.php">Home</a>
</nav>

<div class="container">
    <p>Logged in as <strong><?= htmlspecialchars($user_email) ?></strong></p>

    <section id="report" class="issue-form">
        <h2>Submit an Issue</h2>
        <form action="submit_issue.php" method="POST" enctype="multipart/form-data">
            <label for="category">Category:</label>
            <select name="category" id="category" onchange="toggleOtherCategory(this.value)">
                <optgroup label="Road and Transportation">
                    <option value="Potholes">Potholes</option>
                    <option value="Damaged road surfaces">Damaged road surfaces</option>
                    <option value="Faded road markings">Faded road markings</option>
                    <option value="Broken or missing traffic signs">Broken or missing traffic signs</option>
                    <option value="Malfunctioning traffic lights">Malfunctioning traffic lights</option>
                    <option value="Illegal parking">Illegal parking</option>
                </optgroup>
                <optgroup label="Lighting and Electricity">
                    <option value="Broken streetlights">Broken streetlights</option>
                    <option value="Exposed electrical wires">Exposed electrical wires</option>
                </optgroup>
                <optgroup label="Water and Sanitation">
                    <option value="Water pipe leaks">Water pipe leaks</option>
                    <option value="Blocked drains">Blocked drains</option>
                    <option value="Contaminated water supply">Contaminated water supply</option>
                </optgroup>
                <optgroup label="Waste Management">
                    <option value="Uncollected garbage">Uncollected garbage</option>
                    <option value="Illegal dumping">Illegal dumping</option>
                </optgroup>
                <optgroup label="Others">
                    <option value="Other">Other</option>
                </optgroup>
            </select>
            <input type="text" id="otherCategoryInput" name="other_category" placeholder="Please specify other issue" style="display:none;">

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="photo">Photo (optional):</label>
            <input type="file" name="photo" id="photo">

            <button type="submit">Submit Issue</button>
        </form>
    </section>

    <section id="track" class="issue-list">
        <h2>Your Reported Issues</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Photo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $issues->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                    <td>
                        <?php if ($row['photo']): ?>
                            <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Issue Photo">
                        <?php else: ?>
                            No Photo
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</div>

<footer>
    <p>&copy; <?= date("Y") ?> NeighborlyFix. All rights reserved.</p>
</footer>

<script>
function toggleOtherCategory(value) {
    var input = document.getElementById('otherCategoryInput');
    input.style.display = (value === 'Other') ? 'block' : 'none';
    input.required = (value === 'Other');
}
</script>

<?php
$stmt->close();
$conn->close();
?>
</body>
</html>
