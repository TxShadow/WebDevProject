<?php
include('config.php');
session_start();

// Security Gate: Only Candidates allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php");
    exit();
}

$candidate_id = $_SESSION['user_id'];

// SQL to fetch all applications sent by this specific candidate
$sql = "SELECT 
            applications.status as app_status,
            applications.created_at as applied_date,
            jobs.title as job_title,
            jobs.location as job_location
        FROM applications
        JOIN jobs ON applications.job_id = jobs.id
        WHERE applications.user_id = '$candidate_id'
        ORDER BY applications.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Applications | E-Recruitment</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; padding: 30px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h1 { color: #1c1e21; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; border-bottom: 1px solid #e4e6eb; text-align: left; }
        th { background: #f8f9fa; color: #65676b; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }
        
        /* Status Badge Styling */
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; }
        .pending { background: #fff3cd; color: #856404; }
        .shortlisted { background: #d4edda; color: #155724; }
        .rejected { background: #f8d7da; color: #721c24; }
        
        .nav-links { margin-bottom: 20px; }
        .nav-links a { text-decoration: none; color: #1877f2; font-weight: bold; margin-right: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="nav-links">
        <a href="candidate_dashboard.php">← Browse More Jobs</a>
        <a href="logout.php" style="color: #606770;">Logout</a>
    </div>

    <h1>My Job Applications</h1>
    <p>Track the status of the positions you have applied for.</p>

    <table>
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Location</th>
                <th>Date Applied</th>
                <th>Current Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Assign the CSS class based on the status in database
                    $status = $row['app_status']; 
                    
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($row['job_title']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['job_location']) . "</td>";
                    echo "<td>" . date('d M Y', strtotime($row['applied_date'])) . "</td>";
                    echo "<td><span class='badge $status'>" . ucfirst($status) . "</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center; padding: 40px; color: #65676b;'>You haven't applied for any jobs yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>