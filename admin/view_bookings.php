```php
<?php
session_start();

if (empty($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/db.php";

$sql = "
    SELECT
        b.id AS booking_id,
        b.booked_at,
        s.name AS student_name,
        s.email,
        c.subject,
        c.teacher,
        c.class_date,
        c.start_time,
        c.end_time
    FROM bookings b
    INNER JOIN students s ON b.student_id = s.id
    INNER JOIN classes c ON b.class_id = c.id
    ORDER BY b.booked_at DESC
";

$result = $conn->query($sql);

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Bookings | Admin Portal</title>

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

        header a {
            color: #1758cf;
            text-decoration: none;
            font-weight: bold;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
        }

        nav a {
            color: #29466f;
        }

        main {
            max-width: 1250px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .hero {
            padding: 32px;
            margin-bottom: 25px;
            border-radius: 20px;
            background: linear-gradient(120deg, #dceaff, white);
        }

        .hero h1 {
            margin-top: 0;
            font-size: 32px;
        }

        .hero p {
            color: #607593;
            line-height: 1.7;
        }

        .summary {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px 24px;
            margin-bottom: 25px;
            border: 1px solid #e0e9f8;
            border-radius: 15px;
            background: white;
        }

        .summary-icon {
            font-size: 32px;
        }

        .summary strong {
            display: block;
            font-size: 24px;
            color: #1764e8;
        }

        .summary span {
            color: #607593;
        }

        .table-card {
            padding: 20px;
            border: 1px solid #e0e9f8;
            border-radius: 18px;
            background: white;
            box-shadow: 0 5px 18px #dfe8f5;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th {
            padding: 16px 14px;
            background: #edf4ff;
            color: #29466f;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        td {
            padding: 16px 14px;
            border-bottom: 1px solid #e7edf7;
            color: #415b7e;
            font-size: 14px;
            line-height: 1.6;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        .student-name {
            color: #173568;
            font-weight: bold;
        }

        .subject {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 8px;
            background: #e8f1ff;
            color: #1758cf;
            font-weight: bold;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #607593;
            line-height: 1.8;
        }

        .back-link {
            display: inline-block;
            margin-top: 22px;
            color: #1764e8;
            text-decoration: none;
            font-weight: bold;
        }

        footer {
            padding: 25px;
            color: #607593;
            text-align: center;
        }

        @media (max-width: 600px) {
            .hero {
                padding: 24px;
            }

            .hero h1 {
                font-size: 27px;
            }

            .table-card {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="../index.php">🎓 Online Class Booking</a>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="add_class.php">Add Class</a>
        <a href="logout.php">Log Out</a>
    </nav>
</header>

<main>
    <section class="hero">
        <h1>Student Bookings 📋</h1>
        <p>View student reservations, class schedules, and booking details in one place.</p>
    </section>

    <?php if (!$result): ?>

        <section class="table-card">
            <div class="empty">
                <h2>Unable to load bookings</h2>
                <p>Please check your database connection and try again.</p>
            </div>
        </section>

    <?php else: ?>

        <section class="summary">
            <div class="summary-icon">👥</div>
            <div>
                <strong><?php echo (int)$result->num_rows; ?></strong>
                <span>Total booking records</span>
            </div>
        </section>

        <section class="table-card">
            <?php if ($result->num_rows > 0): ?>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Class Date</th>
                                <th>Time</th>
                                <th>Booked On</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($booking = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="student-name">
                                        <?php echo h($booking["student_name"]); ?>
                                    </td>

                                    <td><?php echo h($booking["email"]); ?></td>

                                    <td>
                                        <span class="subject">
                                            <?php echo h($booking["subject"]); ?>
                                        </span>
                                    </td>

                                    <td><?php echo h($booking["teacher"]); ?></td>

                                    <td>
                                        <?php echo h(date(
                                            "d M Y",
                                            strtotime($booking["class_date"])
                                        )); ?>
                                    </td>

                                    <td>
                                        <?php echo h(date(
                                            "g:i A",
                                            strtotime($booking["start_time"])
                                        )); ?>
                                        –
                                        <?php echo h(date(
                                            "g:i A",
                                            strtotime($booking["end_time"])
                                        )); ?>
                                    </td>

                                    <td>
                                        <?php echo h(date(
                                            "d M Y, g:i A",
                                            strtotime($booking["booked_at"])
                                        )); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <div class="empty">
                    <h2>No bookings yet</h2>
                    <p>Student reservations will appear here when students book classes.</p>
                </div>

            <?php endif; ?>
        </section>

    <?php endif; ?>

    <a class="back-link" href="dashboard.php">← Back to Admin Dashboard</a>
</main>

<footer>
    Online Class Scheduling and Booking System · Admin Portal
</footer>

</body>
</html>
```