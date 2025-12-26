<?php
include('config.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $job_id = mysqli_real_escape_string($conn, $_GET['id']);
    $employer_id = $_SESSION['user_id'];

    $sql = "DELETE FROM jobs WHERE id = '$job_id' AND user_id = '$employer_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: employer_dashboard.php?msg=Job deleted successfully");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>