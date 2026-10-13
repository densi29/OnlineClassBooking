
<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (isset($_SESSION["student_id"])) {
    header("Location: dashboard.php");
    exit;
}

$name = "";
$email = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($name === "" || $email === "" || $password === "" || $confirm_password === "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Your password must contain at least 8 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "The passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT id FROM students WHERE email = ? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $existing = $check->get_result()->fetch_assoc();
        $check->close();

        if ($existing) {
            $error = "An account with this email already exists. Please log in.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO students (name, email, password) VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
                $stmt->close();
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
    <title>Student Registration | ClassBook</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: #172554;
            background: linear-gradient(135deg, #eff6ff, #f8fafc 55%, #dbeafe);
            display: flex;
            flex-direction: column;
        }

        nav {
            min-height: 76px;
            padding: 18px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            color: #1d4ed8;
            text-decoration: none;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -0.7px;
        }

        .nav-link {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 700;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 18px 50px;
        }

        .register-layout {
            width: 100%;
            max-width: 960px;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 22px 60px rgba(30, 64, 175, 0.13);
        }

        .welcome-panel {
            padding: 48px 38px;
            color: white;
            background: linear-gradient(145deg, #1d4ed8, #2563eb 60%, #60a5fa);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-icon {
            width: 72px;
            height: 72px;
            display: grid;
            place-items: center;
            border-radius: 22px;
            background: rgba(255,255,255,0.17);
            font-size: 36px;
            margin-bottom: 25px;
        }

        .welcome-panel h1 {
            margin: 0 0 16px;
            font-size: 33px;
            line-height: 1.2;
        }

        .welcome-panel p {
            margin: 0;
            color: #dbeafe;
            line-height: 1.8;
            font-size: 15px;
        }

        .benefit {
            margin-top: 25px;
            line-height: 2.3;
            font-size: 14px;
        }

        .form-panel {
            padding: 42px 40px;
        }

        .form-panel h2 {
            margin: 0 0 9px;
            color: #0f172a;
            font-size: 29px;
        }

        .subtitle {
            color: #64748b;
            margin: 0 0 25px;
            line-height: 1.6;
            font-size: 14px;
        }

        .error {
            padding: 13px 14px;
            margin-bottom: 20px;
            border-radius: 10px;
            color: #b91c1c;
            background: #fef2f2;
            border: 1px solid #fecaca;
            font-size: 14px;
            line-height: 1.5;
        }

        label {
            display: block;
            margin: 0 0 8px;
            color: #334155;
            font-size: 14px;
            font-weight: 700;
        }

        input {
            display: block;
            width: 100%;
            padding: 13px 14px;
            margin-bottom: 18px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37,99,235,.12);
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            border: 0;
            border-radius: 10px;
            color: white;
            background: #2563eb;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .submit-btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .login-text {
            margin: 22px 0 0;
            text-align: center;
            color: #64748b;
            font-size: 14px;
            line-height: 1.8;
        }

        .login-text a, .back-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
        }

        .back-link {
            display: block;
            margin-top: 17px;
            text-align: center;
            font-size: 13px;
            font-weight: 400;
        }

        footer {
            padding: 18px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 720px) {
            nav { padding: 18px 5%; }
            .register-layout {
                grid-template-columns: 1fr;
                max-width: 480px;
            }
            .welcome-panel { padding: 30px 28px; }
            .welcome-panel h1 { font-size: 28px; }
            .welcome-icon {
                width: 58px;
                height: 58px;
                font-size: 29px;
                margin-bottom: 18px;
            }
            .benefit { margin-top: 14px; }
            .form-panel { padding: 32px 28px; }
        }
    </style>
</head>
<body>

<nav>
    <a class="brand" href="../index.php">ClassBook.</a>
    <a class="nav-link" href="login.php">Student Login</a>
</nav>

<main>
    <div class="register-layout">
        <section class="welcome-panel">
            <div class="welcome-icon">🎓</div>
            <h1>Start your learning journey.</h1>
            <p>Create your student account to explore classes and manage your bookings in one place.</p>
            <div class="benefit">
                ✓ &nbsp; Create your personal account<br>
                ✓ &nbsp; Find classes that interest you<br>
                ✓ &nbsp; Book and manage sessions
            </div>
        </section>

        <section class="form-panel">
            <h2>Create Account</h2>
            <p class="subtitle">Fill in your details to get started.</p>

            <?php if ($error !== ""): ?>
                <div class="error">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <label for="name">Full name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your full name"
                    autocomplete="name"
                    value="<?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>"
                    maxlength="100"
                    required
                >

                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    autocomplete="email"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>"
                    maxlength="255"
                    required
                >

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="At least 8 characters"
                    autocomplete="new-password"
                    minlength="8"
                    required
                >

                <label for="confirm_password">Confirm password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Enter your password again"
                    autocomplete="new-password"
                    minlength="8"
                    required
                >

                <button class="submit-btn" type="submit">Create Account →</button>
            </form>

            <p class="login-text">
                Already have an account?
                <a href="login.php">Sign in</a>
            </p>

            <a class="back-link" href="../index.php">← Back to home</a>
        </section>
    </div>
</main>

<footer>© <?= date("Y") ?> ClassBook. Learn something new every day.</footer>

<script>
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");

    function checkPasswords() {
        if (confirmPassword.value !== "" && password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords do not match.");
        } else {
            confirmPassword.setCustomValidity("");
        }
    }

    password.addEventListener("input", checkPasswords);
    confirmPassword.addEventListener("input", checkPasswords);
</script>

</body>
</html>
