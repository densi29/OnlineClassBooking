<?php
$conn = new mysqli("localhost", "root", "", "online_class_booking");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>