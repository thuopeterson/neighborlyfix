<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user'];
$category = $_POST['category'];
$location = $_POST['location'];
$description = $_POST['description'];
$status = "Pending";
$photo_path = "";

if (!empty($_FILES["photo"]["name"])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir);
    $photo_path = $target_dir . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path);
}

$stmt = $conn->prepare("INSERT INTO issues (user_email, category, location, description, photo, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $user_email, $category, $location, $description, $photo_path, $status);

if ($stmt->execute()) {
    header("Location: index.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
