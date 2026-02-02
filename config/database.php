<?php
$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "indus_agrii";
$port = 3307; // change ONLY if my.ini says 3307

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);
