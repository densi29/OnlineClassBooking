```php
<?php
// Temporary error reporting to help diagnose a blank page.
// We can disable display_errors after troubleshooting.
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/db.php';

$error = '';
$registered = isset($_GET['registered']);

// Redirect students who are already logged in.
if (isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare(
            'SELECT id, name, email, password
             FROM students
             WHERE email = ?
             LIMIT 1'
        );

        if (!$stmt) {
            $error = 'Unable to process your login. Please try again.';
        } else {
            $stmt->bind_param('s', $email);
            $stmt->execute();

            $result = $stmt->get_result();
            $student = $result->fetch_assoc();

            $stmt->close();

            if ($student && password_verify($password, $student['password'])) {
                session_regenerate_id(true);

                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name'] = $student['name'];

                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Incorrect email or password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | Online Class Scheduling</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #eef4ff, #f8faff);
            color: #1f2937;
            display: flex;
            flex-direction: column;
        }

        header {
            background: #ffffff;
            padding: 20px 7%;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        header a {
            color: #2563eb;
            font-size: 21px;
            font-weight: bold;
            text-decoration: none;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 18px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(31, 41, 55, 0.10);
        }

        h1 {
            margin: 0 0 10px;
            text-align: center;
            font-size: 28px;
            color: #1d4ed8;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin: 0 0 28px;
            line-height: 1.5;
        }

        .notice {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.5;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            margin-bottom: 19px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .links {
            text-align: center;
            margin-top: 22px;
            font-size: 14px;
            line-height: 2;
        }

        .links a {
            color: #2563eb;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        footer {
            padding: 18px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 25px 20px;
            }

            h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>
    <header>
        <a href="../index.php">Online Class Scheduling</a>
    </header>

    <main>
        <section class="login-card">
            <h1>Welcome Back!</h1>
            <p class="subtitle">Log in to book and manage your classes.</p>

            <?php if ($registered): ?>
                <div class="notice success">
                    Registration successful! You can now log in.
                </div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="notice error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="email"
                    required
                >

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >

                <button type="submit">Log In</button>
            </form>

            <div class="links">
                Don't have an account?
                <a href="register.php">Create an account</a>
                <br>
                <a href="../index.php">Back to Home</a>
            </div>
        </section>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Online Class Scheduling System
    </footer>
</body>
</html>
