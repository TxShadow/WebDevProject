<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

$my_id = $_SESSION['user_id'];


$sql = "SELECT * FROM jobs WHERE user_id = '$my_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employer Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; }
        .main { margin-left: 290px; padding: 20px; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
        .status-open { color: green; font-weight: bold; }
        .btn-add { background: #27ae60; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>RecruitMe</h2>
        <p>Welcome, <?php echo $_SESSION['name']; ?></p>
        <hr>
        <a href="post_job.php" style="color:white; display:block; margin: 10px 0;">Post New Job</a>
        <a href="view_applicants.php" style="color:white; display:block; margin: 10px 0;">View Applicants</a>
        <a href="logout.php" style="color:white; display:block; margin: 10px 0;">Logout</a>
    </div>

    <div class="main">
        <h1>Your Job Listings</h1>
        <a href="post_job.php" class="btn-add">+ Post a New Job</a>

        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Posted Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['salary'] . "</td>";
                        echo "<td class='status-open'>" . $row['status'] . "</td>";
                        $date_display = (!empty($row['created_at'])) 
                                     ? date('d M Y', strtotime($row['created_at'])) 
                                     : "Not Set";

                        echo "<td>" . $date_display . "</td>";
                        echo "<td>
                                <a href='edit_job.php?id=" . $row['id'] . "'>Edit</a> | 
                                <a href='delete_job.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>You haven't posted any jobs yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>