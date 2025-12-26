<?php
include('config.php');
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    die("Unauthorized access.");
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $app_id = mysqli_real_escape_string($conn, $_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $allowed_statuses = ['shortlisted', 'rejected'];
    if (in_array($status, $allowed_statuses)) {
        
        $sql = "UPDATE applications SET status = '$status' WHERE id = '$app_id'";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: view_applicants.php?msg=Status updated to " . $status);
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}
?>