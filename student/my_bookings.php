```php
<?php
session_start();

if (empty($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/db.php";

$studentId = (int)$_SESSION["student_id"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cancel_booking"])) {
    $bookingId = filter_input(INPUT_POST, "booking_id", FILTER_VALIDATE_INT);

    if ($bookingId && $bookingId > 0) {
        $stmt = $conn->prepare(
            "DELETE FROM bookings WHERE id = ? AND student_id = ?"
        );
        $stmt->bind_param("ii", $bookingId, $studentId);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $stmt->close();
            header("Location: my_bookings.php?cancelled=1");
            exit;
        }

        $stmt->close();
        $message = "This booking could not be cancelled.";
    } else {
        $message = "Invalid booking selected.";
    }
}

if (isset($_GET["booked"])) {
    $message = "Your class has been booked successfully!";
} elseif (isset($_GET["cancelled"])) {
    $message = "Your booking has been cancelled.";
}

$stmt = $conn->prepare(
    "SELECT
        b.id AS booking_id,
        b.booked_at,
        c.subject,
        c.teacher,
        c.class_date,
        c.start_time,
        c.end_time
     FROM bookings b
     INNER JOIN classes c ON c.id = b.class_id
     WHERE b.student_id = ?
     ORDER BY c.class_date DESC, c.start_time DESC"
);

$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | Online Class Booking</title>

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
            gap: 20px;
        }

        main {
            max-width: 1050px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .hero {
            padding: 32px;
            border-radius: 20px;
            background: linear-gradient(120deg, #dceaff, white);
            margin-bottom: 28px;
        }

        .hero h1 {
            margin-top: 0;
            font-size: 32px;
        }

        .hero p {
            color: #607593;
            line-height: 1.7;
        }

        .notice {
            padding: 15px 18px;
            border-radius: 10px;
            background: #e1f8e9;
            color: #176b3b;
            margin-bottom: 22px;
        }

        .error {
            background: #ffeded;
            color: #a32626;
        }

        .booking-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .booking-card {
            padding: 25px;
            border: 1px solid #e0e9f8;
            border-radius: 17px;
            background: white;
            box-shadow: 0 5px 18px #dfe8f5;
        }

        .icon {
            font-size: 34px;
        }

        .booking-card h2 {
            margin: 15px 0 8px;
            font-size: 23px;
        }

        .teacher {
            color: #607593;
        }

        .detail {
            margin: 13px 0;
            line-height: 1.5;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            background: #e1f8e9;
            color: #176b3b;
            font-size: 13px;
            font-weight: bold;
            margin: 5px 0 18px;
        }

        .cancel-button {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #fff0f0;
            color: #b42323;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .cancel-button:hover {
            background: #ffdede;
        }

        .empty {
            padding: 35px;
            border-radius: 16px;
            background: white;
            text-align: center;
            line-height: 1.8;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 10px;
            background: #1764e8;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        footer {
            padding: 25px;
            color: #607593;
            text-align: center;
        }

        @media (max-width: 700px) {
            .booking-list {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="../index.php">🎓 Online Class Booking</a>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="classes.php">Explore Classes</a>
        <a href="logout.php">Log Out</a>
    </nav>
</header>

<main>
    <section class="hero">
        <h1>My Bookings 📅</h1>
        <p>All your reserved classes in one place. Review your schedule or cancel a reservation you no longer need.</p>
    </section>

    <?php if ($message !== ""): ?>
        <div class="notice <?php echo (
            strpos($message, "could not") !== false ||
            strpos($message, "Invalid") !== false
        ) ? "error" : ""; ?>">
            <?php echo h($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <section class="booking-list">

            <?php while ($booking = $result->fetch_assoc()): ?>
                <article class="booking-card">
                    <div class="icon">📘</div>

                    <h2><?php echo h($booking["subject"]); ?></h2>

                    <p class="teacher">
                        👨‍🏫 Teacher: <?php echo h($booking["teacher"]); ?>
                    </p>

                    <p class="detail">
                        📅 <?php echo h(date("d M Y", strtotime($booking["class_date"]))); ?>
                    </p>

                    <p class="detail">
                        🕒
                        <?php echo h(date("g:i A", strtotime($booking["start_time"]))); ?>
                        -
                        <?php echo h(date("g:i A", strtotime($booking["end_time"]))); ?>
                    </p>

                    <p class="detail">
                        Booking date:
                        <?php echo h(date("d M Y, g:i A", strtotime($booking["booked_at"]))); ?>
                    </p>

                    <span class="status">✓ Booking Confirmed</span>

                    <form method="POST"
                          action="my_bookings.php"
                          onsubmit="return confirm('Are you sure you want to cancel this booking?');">

                        <input type="hidden"
                               name="booking_id"
                               value="<?php echo (int)$booking["booking_id"]; ?>">

                        <button class="cancel-button"
                                type="submit"
                                name="cancel_booking"
                                value="1">
                            Cancel Booking
                        </button>
                    </form>
                </article>
            <?php endwhile; ?>

        </section>
    <?php else: ?>
        <section class="empty">
            <h2>No bookings yet</h2>
            <p>You haven't reserved any classes. Explore the available classes to get started.</p>
            <a class="button" href="classes.php">Explore Classes →</a>
        </section>
    <?php endif; ?>
</main>

<footer>
    Online Class Scheduling and Booking System
</footer>

</body>
</html>
```