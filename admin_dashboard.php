<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Control Panel</title>
    <style>
        body { font-family: sans-serif; background: #2c3e50; color: white; padding: 40px; }
        .stats-container { display: flex; gap: 20px; margin-top: 20px; }
        .card { background: #34495e; padding: 20px; border-radius: 10px; flex: 1; text-align: center; }
        .btn-delete { background: #e74c3c; color: white; padding: 5px; border-radius: 3px; text-decoration: none; }
    </style>
</head>
<body>
    <h1>System Administration</h1>
    <p>Welcome, Master Admin. You have full control over the e-recruitment system.</p>

    <div class="stats-container">
        <div class="card">
            <h3>Total Users</h3>
            <p style="font-size: 24px;">45</p>
        </div>
        <div class="card">
            <h3>Active Jobs</h3>
            <p style="font-size: 24px;">12</p>
        </div>
    </div>

    <h2>Manage Job Postings</h2>
    <table border="1" width="100%" style="border-collapse: collapse;">
        <tr>
            <th>Job Title</th>
            <th>Posted By</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>Web Designer</td>
            <td>Company ABC</td>
            <td><a href="#" class="btn-delete">Remove Job</a></td>
        </tr>
    </table>

    <br>
    <a href="logout.php" style="color: #ecf0f1;">Logout</a>
</body>
</html>