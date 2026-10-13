```php
<?php
session_start();

if (empty($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/db.php";

$message = "";
$error = "";

$subject = "";
$teacher = "";
$classDate = "";
$startTime = "";
$endTime = "";
$capacity = "30";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = trim($_POST["subject"] ?? "");
    $teacher = trim($_POST["teacher"] ?? "");
    $classDate = trim($_POST["class_date"] ?? "");
    $startTime = trim($_POST["start_time"] ?? "");
    $endTime = trim($_POST["end_time"] ?? "");
    $capacity = trim($_POST["capacity"] ?? "");

    $validDate = DateTime::createFromFormat("!Y-m-d", $classDate);
    $validDateOk = $validDate && $validDate->format("Y-m-d") === $classDate;

    if (
        $subject === "" ||
        $teacher === "" ||
        $classDate === "" ||
        $startTime === "" ||
        $endTime === "" ||
        $capacity === ""
    ) {
        $error = "Please fill in all fields.";
    } elseif (!$validDateOk) {
        $error = "Please enter a valid class date.";
    } elseif ($classDate < date("Y-m-d")) {
        $error = "The class date cannot be in the past.";
    } elseif (!preg_match('/^\d{2}:\d{2}$/', $startTime) ||
              !preg_match('/^\d{2}:\d{2}$/', $endTime)) {
        $error = "Please enter valid start and end times.";
    } elseif ($endTime <= $startTime) {
        $error = "End time must be later than start time.";
    } elseif (
        filter_var($capacity, FILTER_VALIDATE_INT) === false ||
        (int)$capacity < 1 ||
        (int)$capacity > 10000
    ) {
        $error = "Capacity must be a whole number between 1 and 10000.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO classes
            (subject, teacher, class_date, start_time, end_time, capacity)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $capacityNumber = (int)$capacity;
        $stmt->bind_param(
            "sssssi",
            $subject,
            $teacher,
            $classDate,
            $startTime,
            $endTime,
            $capacityNumber
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: add_class.php?success=1");
            exit;
        } else {
            $error = "Unable to save the class. Please try again.";
            $stmt->close();
        }
    }
}

if (isset($_GET["success"])) {
    $message = "Class added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Class | Admin Portal</title>

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
            gap: 18px;
        }

        nav a {
            color: #29466f;
        }

        main {
            max-width: 850px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .intro {
            margin-bottom: 25px;
        }

        .intro h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .intro p {
            color: #617694;
            line-height: 1.7;
        }

        .form-card {
            padding: 32px;
            border: 1px solid #e0e9f8;
            border-radius: 20px;
            background: white;
            box-shadow: 0 8px 25px #dfe8f5;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            font-weight: bold;
            color: #29466f;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #cbd8eb;
            border-radius: 10px;
            font-size: 15px;
            background: #fbfdff;
        }

        input:focus {
            outline: 2px solid #a8c7ff;
            border-color: #1764e8;
        }

        .hint {
            color: #71829b;
            font-size: 13px;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: #1764e8;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #104fc1;
        }

        .notice {
            padding: 14px 17px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: #e1f8e9;
            color: #176b3b;
        }

        .error {
            background: #ffeded;
            color: #a32626;
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
            color: #617694;
            text-align: center;
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }

            .form-card {
                padding: 22px;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="../index.php">🎓 Online Class Booking</a>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_bookings.php">Student Bookings</a>
        <a href="logout.php">Log Out</a>
    </nav>
</header>

<main>
    <section class="intro">
        <h1>➕ Add a New Class</h1>
        <p>Enter the class details below to make a new learning session available to students.</p>
    </section>

    <section class="form-card">

        <?php if ($message !== ""): ?>
            <div class="notice"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="notice error"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></div>
        <?php endif; ?>

        <form method="POST" action="add_class.php">
            <div class="form-grid">

                <div class="field">
                    <label for="subject">Subject Name</label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        maxlength="150"
                        placeholder="e.g. Mathematics"
                        value="<?php echo htmlspecialchars($subject, ENT_QUOTES, "UTF-8"); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label for="teacher">Teacher Name</label>
                    <input
                        type="text"
                        id="teacher"
                        name="teacher"
                        maxlength="150"
                        placeholder="e.g. John Smith"
                        value="<?php echo htmlspecialchars($teacher, ENT_QUOTES, "UTF-8"); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label for="class_date">Class Date</label>
                    <input
                        type="date"
                        id="class_date"
                        name="class_date"
                        min="<?php echo date("Y-m-d"); ?>"
                        value="<?php echo htmlspecialchars($classDate, ENT_QUOTES, "UTF-8"); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label for="capacity">Student Capacity</label>
                    <input
                        type="number"
                        id="capacity"
                        name="capacity"
                        min="1"
                        max="10000"
                        value="<?php echo htmlspecialchars($capacity, ENT_QUOTES, "UTF-8"); ?>"
                        required
                    >
                    <span class="hint">Maximum number of students allowed.</span>
                </div>

                <div class="field">
                    <label for="start_time">Start Time</label>
                    <input
                        type="time"
                        id="start_time"
                        name="start_time"
                        value="<?php echo htmlspecialchars($startTime, ENT_QUOTES, "UTF-8"); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label for="end_time">End Time</label>
                    <input
                        type="time"
                        id="end_time"
                        name="end_time"
                        value="<?php echo htmlspecialchars($endTime, ENT_QUOTES, "UTF-8"); ?>"
                        required
                    >
                </div>

            </div>

            <button type="submit">Save Class →</button>
        </form>

        <a class="back-link" href="dashboard.php">← Back to Admin Dashboard</a>
    </section>
</main>

<footer>
    Online Class Scheduling and Booking System
</footer>

</body>
</html>
```