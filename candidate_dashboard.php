<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php");
    exit();
}

$search_query = "";
$location_query = "";

$sql = "SELECT jobs.*, users.name as employer_name 
        FROM jobs 
        JOIN users ON jobs.user_id = users.id 
        WHERE status = 'Open'";

if (isset($_GET['search']) || isset($_GET['location'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $location_query = mysqli_real_escape_string($conn, $_GET['location']);
    
    if (!empty($search_query)) {
        $sql .= " AND jobs.title LIKE '%$search_query%'";
    }
    if (!empty($location_query)) {
        $sql .= " AND jobs.location LIKE '%$location_query%'";
    }
}

$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Jobs | E-Recruitment</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .search-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .search-box input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .search-box button { padding: 10px 20px; background: #1a73e8; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .container { max-width: 900px; margin: auto; }
        .job-card { background: white; padding: 20px; margin-bottom: 15px; border-radius: 8px; border-left: 5px solid #1a73e8; }
        .job-card h3 { margin-top: 0; color: #1a73e8; display: flex; align-items: center; gap: 10px; }
        .job-card p { margin: 8px 0; color: #555; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; }
        .apply-btn { background: #1a73e8; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Find Your Next Job</h1>
        
        <form class="search-box" method="GET" action="candidate_dashboard.php">
            <input type="text" name="search" placeholder="Job title, keywords..." value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="text" name="location" placeholder="City or Remote" value="<?php echo htmlspecialchars($location_query); ?>">
            <button type="submit">Search</button>
            <a href="candidate_dashboard.php" style="padding-top:10px; font-size: 0.8rem; color: #666;">Clear</a>
        </form>

        <p><a href="my_applications.php">View My Applications</a> |<a href="profile.php">my profil</a> | <a href="logout.php">Logout</a></p>

        <?php if ($result->num_rows > 0): ?>
            <p>Found <?php echo $result->num_rows; ?> jobs:</p>
          <?php while($row = $result->fetch_assoc()): ?>
        <div class="job-card">
        <?php 
            // 1. Logic to pick an icon based on the job title
            $title_lower = strtolower($row['title']);
            $icon = "💼"; // Default Briefcase

            if (strpos($title_lower, 'web') !== false || strpos($title_lower, 'dev') !== false || strpos($title_lower, 'software') !== false) {
                $icon = "💻"; // Tech icon
            } elseif (strpos($title_lower, 'design') !== false || strpos($title_lower, 'art') !== false) {
                $icon = "🎨"; // Design icon
            } elseif (strpos($title_lower, 'manager') !== false || strpos($title_lower, 'admin') !== false) {
                $icon = "👔"; // Management icon
            }
        ?>

        <h3><?php echo $icon; ?> <?php echo htmlspecialchars($row['title']); ?></h3>
        
        <p>
            <strong>Company:</strong> <?php echo htmlspecialchars($row['employer_name']); ?> | 
            <strong>Location:</strong> 📍 <?php echo htmlspecialchars($row['location']); ?>
        </p>
        
        <p>
            <strong>Salary:</strong> 💵 
            <?php echo !empty($row['salary']) ? htmlspecialchars($row['salary']) : "Negotiable"; ?>
        </p>

        <p><?php echo nl2br(htmlspecialchars(substr($row['description'], 0, 150))); ?>...</p>

        <a href="apply_job.php?id=<?php echo $row['id']; ?>" class="apply-btn">Apply Now</a>
    </div>
<?php endwhile; ?>
        <?php else: ?>
            <div style="background: white; padding: 40px; text-align: center; border-radius: 8px;">
                <p>No jobs found matching your search. Try different keywords.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>