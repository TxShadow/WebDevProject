<?php
include('config.php');
session_start();

// Security Gate: Only Employers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

$employer_id = $_SESSION['user_id'];

// SQL to get applications for THIS employer's jobs
$sql = "SELECT 
            applications.id as app_id,
            applications.resume_path,
            applications.status as app_status,
            applications.created_at as applied_date,
            jobs.title as job_title,
            users.id as user_id, 
            users.name as candidate_name,
            users.email as candidate_email
        FROM applications
        JOIN jobs ON applications.job_id = jobs.id
        JOIN users ON applications.user_id = users.id
        WHERE jobs.user_id = '$employer_id'
        ORDER BY applications.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Applicants | E-Recruitment</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #2c3e50; color: white; }
        .btn-view { background: #3498db; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.9rem; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase; font-weight: bold; }
        .pending { background: #f1c40f; color: black; }
        .status-badge { padding: 5px 10px; border-radius: 12px; font-size: 0.8rem; font-weight: bold; }
        .pending { background: #ffeaa7; color: #d35400; }
        .shortlisted { background: #55efc4; color: #00b894; }
        .rejected { background: #ff7675; color: #d63031; }
    </style>
</head>
<body>

<div class="container">
    <h1>Received Applications</h1>
    <p><a href="employer_dashboard.php">← Back to Dashboard</a></p>

    <table>
        <thead>
            <tr>
                <th>Candidate Name</th>
                <th>Applied For</th>
                <th>Email</th>
                <th>Resume</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
       <tbody>
   <tbody>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $status_class = $row['app_status']; 
            
            echo "<tr>";
            
            echo "<td>" . htmlspecialchars($row['candidate_name']) . "<br>
                  <a href='view_profile.php?id=" . $row['user_id'] . "' style='font-size:0.8rem; color:#3498db; text-decoration:none;'>View Profile</a></td>";
            
            echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['candidate_email']) . "</td>";
            echo "<td><a href='" . $row['resume_path'] . "' target='_blank' class='btn-view'>Open PDF</a></td>";
            echo "<td>" . date('d M Y', strtotime($row['applied_date'])) . "</td>";
            echo "<td><span class='status-badge $status_class'>" . ucfirst($row['app_status']) . "</span></td>";
            
            echo "<td>";
            if ($row['app_status'] == 'pending') {
                echo "<a href='update_status.php?id=" . $row['app_id'] . "&status=shortlisted' style='color:green; font-weight:bold; text-decoration:none;'>Accept</a> | ";
                echo "<a href='update_status.php?id=" . $row['app_id'] . "&status=rejected' style='color:red; font-weight:bold; text-decoration:none;'>Reject</a>";
            } else {
                echo "<span style='color:gray;'>Decision Made</span>";
            }
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' style='text-align:center;'>No applications received yet.</td></tr>";
    }
    ?>
</tbody>
    </table>
</div>

</body>
</html>