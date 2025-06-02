<?php
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['username'])) {
    header("Location: manajemen_tugas.php");
    exit;
}

// Data login sederhana
$valid_username = "admin";
$valid_password = "password123";

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi login
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;
        header("Location: manajemen_tugas.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Manajemen Tugas</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body { font-family: Arial; margin: 40px; }
        form { max-width: 300px; margin: auto; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 8px 0; }
        button { padding: 8px 16px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login - Manajemen Tugas</h2>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
