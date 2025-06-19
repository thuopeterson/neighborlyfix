<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $number = trim($_POST['number']);

    if (!$firstName || !$lastName || !$email || !$password) {
        $error = "Please fill in all required fields.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $number);

        if ($stmt->execute()) {
            // ✅ Redirect to login page instead of index.php
            header("Location: login.php");
            exit;
        } else {
            $error = ($conn->errno === 1062) ? "This email is already registered." : "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
        }

        button:hover {
            background: #218838;
        }

        .error {
            color: red;
            text-align: center;
        }

        .login-link {
            text-align: center;
            margin-top: 10px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2>Create Account</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="firstName" placeholder="First Name" required>
        <input type="text" name="lastName" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="number" placeholder="Phone Number (Optional)">
        <button type="submit">Register</button>
    </form>
    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>
</body>
</html>
