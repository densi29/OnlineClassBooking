<?php
session_start();

if (!empty($_SESSION["admin_logged_in"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// Use server environment variables when available.
$adminUsername = getenv("ADMIN_USERNAME");
$adminPassword = getenv("ADMIN_PASSWORD");

// Otherwise, load private local settings.
if (
    $adminUsername === false ||
    $adminUsername === "" ||
    $adminPassword === false ||
    $adminPassword === ""
) {
    $secretsFile = __DIR__ . "/../config/admin_secrets.php";

    if (is_file($secretsFile)) {
        $localSecrets = require $secretsFile;

        if (is_array($localSecrets)) {
            $adminUsername = $localSecrets["username"] ?? "";
            $adminPassword = $localSecrets["password"] ?? "";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (
        is_string($adminUsername) &&
        is_string($adminPassword) &&
        $adminUsername !== "" &&
        $adminPassword !== "" &&
        hash_equals($adminUsername, $username) &&
        hash_equals($adminPassword, $password)
    ) {
        session_regenerate_id(true);
        $_SESSION["admin_logged_in"] = true;

        header("Location: dashboard.php");
        exit;
    }

    $error = "Invalid username or password, or admin login is not configured.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Online Class Booking</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Admin Login</h1>

    <?php if ($error !== ""): ?>
        <p>
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">Username</label>
        <input
            type="text"
            id="username"
            name="username"
            autocomplete="username"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            autocomplete="current-password"
            required
        >

        <button type="submit">Log In</button>
    </form>

    <p><a href="../student/login.php">Student Login</a></p>
</body>
</html>
