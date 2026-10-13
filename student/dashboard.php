
<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

$studentName = $_SESSION["student_name"] ?? "Student";
$safeName = htmlspecialchars($studentName, ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | ClassBook</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #172554;
            background: #f4f7fc;
        }

        nav {
            min-height: 76px;
            padding: 18px 7%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 2px 12px rgba(15, 23, 42, 0.05);
        }

        .brand {
            color: #1d4ed8;
            text-decoration: none;
            font-size: 25px;
            font-weight: 800;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .student-label {
            color: #475569;
            font-size: 14px;
        }

        .logout-link {
            padding: 10px 16px;
            border-radius: 9px;
            background: #eff6ff;
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .logout-link:hover {
            background: #dbeafe;
        }

        .container {
            width: 86%;
            max-width: 1150px;
            margin: 42px auto;
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 46px 42px;
            border-radius: 22px;
            color: white;
            background: linear-gradient(120deg, #1d4ed8, #3b82f6 70%, #60a5fa);
        }

        .hero::after {
            content: "📚";
            position: absolute;
            right: 45px;
            top: 30px;
            font-size: 100px;
            opacity: 0.22;
        }

        .eyebrow {
            margin: 0 0 14px;
            color: #dbeafe;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .hero h1 {
            position: relative;
            z-index: 1;
            margin: 0 0 15px;
            font-size: clamp(28px, 4vw, 40px);
            line-height: 1.2;
        }

        .hero p {
            position: relative;
            z-index: 1;
            max-width: 570px;
            margin: 0;
            color: #eff6ff;
            font-size: 16px;
            line-height: 1.8;
        }

        .section-heading {
            margin: 38px 0 20px;
        }

        .section-heading h2 {
            margin: 0 0 8px;
            color: #0f172a;
            font-size: 25px;
        }

        .section-heading p {
            margin: 0;
            color: #64748b;
            line-height: 1.6;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .card {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 27px;
            min-height: 260px;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.035);
            transition: transform .2s, box-shadow .2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .card-icon {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            border-radius: 15px;
            background: #eff6ff;
            font-size: 27px;
        }

        .card h3 {
            margin: 21px 0 10px;
            color: #0f172a;
            font-size: 20px;
        }

        .card p {
            flex: 1;
            margin: 0 0 22px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
        }

        .card-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .card-link:hover {
            color: #1e40af;
        }

        footer {
            margin-top: 55px;
            padding: 25px 18px;
            text-align: center;
            color: #64748b;
            background: #fff;
            font-size: 13px;
        }

        @media (max-width: 850px) {
            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .container {
                width: 90%;
            }
        }

        @media (max-width: 580px) {
            nav {
                padding: 16px 5%;
            }

            .brand {
                font-size: 21px;
            }

            .nav-right {
                gap: 10px;
            }

            .student-label {
                display: none;
            }

            .container {
                width: 92%;
                margin: 25px auto;
            }

            .hero {
                padding: 32px 24px;
            }

            .hero::after {
                right: 12px;
                top: 14px;
                font-size: 65px;
            }

            .hero p {
                font-size: 14px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .card {
                min-height: 230px;
            }
        }
    </style>
</head>
<body>

<nav>
    <a class="brand" href="../index.php">ClassBook.</a>

    <div class="nav-right">
        <span class="student-label">Student Portal</span>
        <a class="logout-link" href="logout.php">Log Out ↗</a>
    </div>
</nav>

<main class="container">
    <section class="hero">
        <p class="eyebrow">YOUR LEARNING SPACE</p>
        <h1>Welcome back, <?= $safeName ?>! 👋</h1>
        <p>
            Ready to learn something new? Explore upcoming classes,
            reserve your place, and keep track of your bookings in one place.
        </p>
    </section>

    <section class="section-heading">
        <h2>What would you like to do?</h2>
        <p>Choose an option below to continue your learning journey.</p>
    </section>

    <section class="cards">
        <article class="card">
            <div class="card-icon">📚</div>
            <h3>Explore Classes</h3>
            <p>
                Discover upcoming classes, check schedules, and find
                a subject that interests you.
            </p>
            <a class="card-link" href="classes.php">Browse classes →</a>
        </article>

        <article class="card">
            <div class="card-icon">📅</div>
            <h3>My Bookings</h3>
            <p>
                View your reserved classes and check the dates and
                times of your sessions.
            </p>
            <a class="card-link" href="my_bookings.php">View bookings →</a>
        </article>

        <article class="card">
            <div class="card-icon">🏠</div>
            <h3>Home Page</h3>
            <p>
                Return to the main website to explore the class
                booking system.
            </p>
            <a class="card-link" href="../index.php">Visit home page →</a>
        </article>
    </section>
</main>

<footer>
    © <?= date("Y") ?> Online Class Scheduling and Booking System
</footer>

</body>
</html>
