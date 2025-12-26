<?php
$host = "localhost";
$username = "root";
$password = "12345";
$dbname  = "e-recruitment";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>