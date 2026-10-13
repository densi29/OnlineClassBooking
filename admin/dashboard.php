```php
<?php
session_start();

if (empty($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

$adminName = htmlspecialchars(
    $_SESSION["admin_username"] ?? "Administrator",
    ENT_QUOTES,
    "UTF-8"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Online Class Booking</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #173568;
            background: #f2f6ff;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 20px 6%;
            background: white;
            box-shadow: 0 3px 15px #dce6f5;
        }

        .brand {
            color: #1758cf;
            font-size: 21px;
            font-weight: bold;
            text-decoration: none;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        nav a {
            color: #29466f;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            color: #1764e8;
        }

        main {
            max-width: 1100px;
            margin: 38px auto;
            padding: 0 22px;
        }

        .welcome {
            padding: 38px;
            border-radius: 22px;
            color: white;
            background: linear-gradient(120deg, #1557d5, #4a91ff);
            box-shadow: 0 12px 30px #d5e4ff;
        }

        .welcome-label {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .welcome h1 {
            margin: 16px 0 10px;
            font-size: clamp(29px, 4vw, 40px);
        }

        .welcome p {
            max-width: 650px;
            line-height: 1.8;
        }

        .section-title {
            margin: 35px 0 20px;
            font-size: 26px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .card {
            padding: 28px;
            border: 1px solid #e0e9f8;
            border-radius: 18px;
            background: white;
            box-shadow: 0 5px 18px #dfe8f5;
            transition: 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px #d4e2f7;
        }

        .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: #e8f1ff;
            font-size: 30px;
        }

        .card h2 {
            margin: 20px 0 10px;
            font-size: 21px;
        }

        .card p {
            min-height: 75px;
            color: #617694;
            line-height: 1.7;
        }

        .button {
            display: block;
            padding: 13px;
            border-radius: 10px;
            background: #1764e8;
            color: white;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }

        .button:hover {
            background: #104fc1;
        }

        .logout-button {
            color: #bd3030;
        }

        footer {
            padding: 25px;
            color: #617694;
            text-align: center;
        }

        @media (max-width: 800px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .card p {
                min-height: auto;
            }

            .welcome {
                padding: 28px;
            }
        }
    </style>
</head>
<body>

<header>
    <a class="brand" href="../index.php">🎓 Online Class Booking</a>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="add_class.php">Add Class</a>
        <a href="view_bookings.php">Student Bookings</a>
        <a class="logout-button" href="logout.php">Log Out</a>
    </nav>
</header>

<main>
    <section class="welcome">
        <div class="welcome-label">ADMINISTRATION PORTAL</div>

        <h1>Welcome, <?php echo $adminName; ?>! 👋</h1>

        <p>
            Manage your online class schedule, add new learning opportunities,
            and review student reservations from one convenient dashboard.
        </p>
    </section>

    <h2 class="section-title">Manage Your System</h2>

    <section class="grid">
        <article class="card">
            <div class="icon">➕</div>
            <h2>Add a Class</h2>
            <p>Create a new class by entering its subject, teacher, date, time, and available capacity.</p>
            <a class="button" href="add_class.php">Add New Class →</a>
        </article>

        <article class="card">
            <div class="icon">📋</div>
            <h2>Student Bookings</h2>
            <p>Review student names, email addresses, booked subjects, and class schedules.</p>
            <a class="button" href="view_bookings.php">View Bookings →</a>
        </article>

        <article class="card">
            <div class="icon">🏠</div>
            <h2>Visit Home Page</h2>
            <p>Open the main website to check the student-facing landing page and navigation.</p>
            <a class="button" href="../index.php">Visit Website →</a>
        </article>
    </section>
</main>

<footer>
    Online Class Scheduling and Booking System · Admin Portal
</footer>

</body>
</html>
```