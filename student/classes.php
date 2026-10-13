```php
<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/db.php";

$sql = "SELECT
            c.id,
            c.subject,
            c.teacher,
            c.class_date,
            c.start_time,
            c.end_time,
            c.capacity,
            (
                SELECT COUNT(*)
                FROM bookings b
                WHERE b.class_id = c.id
            ) AS booked_count
        FROM classes c
        WHERE c.class_date >= CURDATE()
        ORDER BY c.class_date ASC, c.start_time ASC";

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
    <title>Explore Classes</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f6ff;
            color: #173568;
        }

        header {
            background: white;
            padding: 20px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            box-shadow: 0 3px 15px #dce6f5;
        }

        header a {
            text-decoration: none;
            color: #1758cf;
            font-weight: bold;
        }

        nav {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        main {
            max-width: 1150px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .hero {
            background: linear-gradient(120deg, #dceaff, white);
            padding: 35px;
            border-radius: 20px;
            margin-bottom: 30px;
        }

        .hero h1 {
            margin-top: 0;
            font-size: 34px;
        }

        .hero p {
            color: #5d7190;
            line-height: 1.7;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .card {
            background: white;
            border: 1px solid #e0e9f8;
            border-radius: 17px;
            padding: 25px;
            box-shadow: 0 5px 18px #dfe8f5;
        }

        .icon {
            font-size: 35px;
        }

        .card h2 {
            font-size: 23px;
            margin-bottom: 8px;
        }

        .teacher {
            color: #667995;
        }

        .detail {
            margin: 13px 0;
            line-height: 1.5;
        }

        .seats {
            display: inline-block;
            padding: 8px 12px;
            background: #e1f8e9;
            color: #167340;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            margin: 8px 0 18px;
        }

        .full {
            background: #ffe7e7;
            color: #a32626;
        }

        .button {
            display: block;
            padding: 13px;
            text-align: center;
            text-decoration: none;
            color: white;
            background: #1764e8;
            border-radius: 10px;
            font-weight: bold;
        }

        .button:hover {
            background: #104fc1;
        }

        .disabled {
            background: #9aa9bd;
            cursor: not-allowed;
        }

        .message {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            line-height: 1.7;
        }

        footer {
            padding: 25px;
            text-align: center;
            color: #667995;
        }

        @media (max-width: 850px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 580px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 25px;
            }

            .hero h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="../index.php">🎓 Online Class Booking</a>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="logout.php">Log Out</a>
    </nav>
</header>

<main>
    <section class="hero">
        <h1>Explore Upcoming Classes 📚</h1>
        <p>Discover subjects, meet your teachers, and reserve your place in an upcoming class.</p>
    </section>

    <h2>Available Classes</h2>

    <?php if (!$result): ?>

        <div class="message">
            <h3>Unable to load classes</h3>
            <p>Please check your database connection and try again.</p>
        </div>

    <?php elseif ($result->num_rows === 0): ?>

        <div class="message">
            <h3>No upcoming classes available yet.</h3>
            <p>Please check back later for new classes.</p>
        </div>

    <?php else: ?>

        <section class="grid">

            <?php while ($class = $result->fetch_assoc()): ?>

                <?php
                $remaining = max(
                    0,
                    (int)$class["capacity"] - (int)$class["booked_count"]
                );

                $isFull = $remaining === 0;
                ?>

                <article class="card">
                    <div class="icon">📘</div>

                    <h2><?php echo h($class["subject"]); ?></h2>

                    <p class="teacher">
                        👨‍🏫 Teacher: <?php echo h($class["teacher"]); ?>
                    </p>

                    <p class="detail">
                        📅 <?php echo h(date("d M Y", strtotime($class["class_date"]))); ?>
                    </p>

                    <p class="detail">
                        🕒
                        <?php echo h(date("g:i A", strtotime($class["start_time"]))); ?>
                        -
                        <?php echo h(date("g:i A", strtotime($class["end_time"]))); ?>
                    </p>

                    <span class="seats <?php echo $isFull ? "full" : ""; ?>">
                        <?php
                        echo $isFull
                            ? "Fully Booked"
                            : $remaining . " seat(s) available";
                        ?>
                    </span>

                    <?php if ($isFull): ?>

                        <span class="button disabled">No Seats Available</span>

                    <?php else: ?>

                        <a class="button"
                           href="book_class.php?id=<?php echo (int)$class["id"]; ?>">
                            View &amp; Book Class →
                        </a>

                    <?php endif; ?>
                </article>

            <?php endwhile; ?>

        </section>

    <?php endif; ?>
</main>

<footer>
    Online Class Scheduling and Booking System
</footer>

</body>
</html>
```