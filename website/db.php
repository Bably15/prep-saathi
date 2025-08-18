<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ebooks_db"; // Make sure this database exists in phpMyAdmin

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
