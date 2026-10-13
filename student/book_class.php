
<?php
session_start();

if (empty($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/db.php";

$studentId = (int)$_SESSION["student_id"];
$classId = filter_var(
    $_GET["id"] ?? $_POST["class_id"] ?? "",
    FILTER_VALIDATE_INT
);

$message = "";
$class = null;

if (!$classId || $classId < 1) {
    $message = "Invalid class selected.";
} else {
    $stmt = $conn->prepare(
        "SELECT id, subject, teacher, class_date, start_time,
                end_time, capacity
         FROM classes
         WHERE id = ?"
    );

    $stmt->bind_param("i", $classId);
    $stmt->execute();
    $result = $stmt->get_result();
    $class = $result->fetch_assoc();
    $stmt->close();

    if (!$class) {
        $message = "This class could not be found.";
    } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
        if ($class["class_date"] < date("Y-m-d")) {
            $message = "You cannot book a class in the past.";
        } else {
            try {
                $conn->begin_transaction();

                $stmt = $conn->prepare(
                    "SELECT capacity, class_date
                     FROM classes
                     WHERE id = ?
                     FOR UPDATE"
                );
                $stmt->bind_param("i", $classId);
                $stmt->execute();
                $stmt->bind_result($capacity, $classDate);
                $classExists = $stmt->fetch();
                $stmt->close();

                if (!$classExists) {
                    throw new Exception("This class is no longer available.");
                }

                if ($classDate < date("Y-m-d")) {
                    throw new Exception("You cannot book a class in the past.");
                }

                $stmt = $conn->prepare(
                    "SELECT id FROM bookings
                     WHERE student_id = ? AND class_id = ?"
                );
                $stmt->bind_param("ii", $studentId, $classId);
                $stmt->execute();
                $stmt->store_result();
                $alreadyBooked = $stmt->num_rows > 0;
                $stmt->close();

                if ($alreadyBooked) {
                    throw new Exception("You have already booked this class.");
                }

                $stmt = $conn->prepare(
                    "SELECT COUNT(*) FROM bookings WHERE class_id = ?"
                );
                $stmt->bind_param("i", $classId);
                $stmt->execute();
                $stmt->bind_result($bookingCount);
                $stmt->fetch();
                $stmt->close();

                if ($bookingCount >= $capacity) {
                    throw new Exception("Sorry, this class is fully booked.");
                }

                $stmt = $conn->prepare(
                    "INSERT INTO bookings (student_id, class_id)
                     VALUES (?, ?)"
                );
                $stmt->bind_param("ii", $studentId, $classId);
                $stmt->execute();
                $stmt->close();

                $conn->commit();

                header("Location: my_bookings.php?booked=1");
                exit;
            } catch (Throwable $e) {
                $conn->rollback();
                $message = $e instanceof mysqli_sql_exception
                    ? "Booking failed. You may already have booked this class."
                    : $e->getMessage();
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
    <title>Book a Class | Online Class Booking</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h1>Book a Class</h1>

    <p><a href="classes.php">Back to Available Classes</a></p>

    <?php if ($message !== ""): ?>
        <p style="color: red;">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <?php if ($class): ?>
        <h2><?= htmlspecialchars($class["subject"], ENT_QUOTES, "UTF-8") ?></h2>

        <p>
            <strong>Teacher:</strong>
            <?= htmlspecialchars($class["teacher"], ENT_QUOTES, "UTF-8") ?>
        </p>

        <p>
            <strong>Date:</strong>
            <?= htmlspecialchars($class["class_date"], ENT_QUOTES, "UTF-8") ?>
        </p>

        <p>
            <strong>Time:</strong>
            <?= htmlspecialchars($class["start_time"], ENT_QUOTES, "UTF-8") ?>
            –
            <?= htmlspecialchars($class["end_time"], ENT_QUOTES, "UTF-8") ?>
        </p>

        <?php if ($_SERVER["REQUEST_METHOD"] !== "POST" && $class["class_date"] >= date("Y-m-d")): ?>
            <form method="POST" action="">
                <input type="hidden" name="class_id" value="<?= (int)$class["id"] ?>">
                <p>Are you sure you want to book this class?</p>
                <button type="submit">Confirm Booking</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
