<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['email'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
        }

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
            background:#4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
        }

        button:hover {
            background:rgb(175, 163, 109);
        }

        .error {
            color: red;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
        }

        .register-link a {
            color: #28a745;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <div class="register-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>
</body>
</html>
