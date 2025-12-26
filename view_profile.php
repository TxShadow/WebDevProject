<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $candidate_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "SELECT users.name, users.email, profiles.skills, profiles.bio, profiles.portfolio_url 
            FROM users 
            LEFT JOIN profiles ON users.id = profiles.user_id 
            WHERE users.id = '$candidate_id'";
    
    $result = $conn->query($sql);
    $candidate = $result->fetch_assoc();
} else {
    header("Location: view_applicants.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Candidate Profile | <?php echo htmlspecialchars($candidate['name']); ?></title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 40px; }
        .profile-container { max-width: 800px; margin: auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .header { border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .name { font-size: 2rem; color: #2c3e50; margin: 0; }
        .email { color: #7f8c8d; }
        .section-title { color: #1a73e8; margin-top: 30px; font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; }
        .skills-tag { display: inline-block; background: #e8f0fe; color: #1a73e8; padding: 5px 12px; border-radius: 20px; margin-right: 5px; font-size: 0.9rem; }
        .bio { line-height: 1.6; color: #444; background: #fafafa; padding: 15px; border-radius: 8px; }
        .btn-back { display: inline-block; margin-top: 30px; text-decoration: none; color: #666; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="header">
        <h1 class="name"><?php echo htmlspecialchars($candidate['name']); ?></h1>
        <p class="email"><?php echo htmlspecialchars($candidate['email']); ?></p>
    </div>

    <h3 class="section-title">Technical Skills</h3>
    <p>
        <?php 
        if(!empty($candidate['skills'])) {
            $skills_array = explode(',', $candidate['skills']);
            foreach($skills_array as $skill) {
                echo "<span class='skills-tag'>" . htmlspecialchars(trim($skill)) . "</span>";
            }
        } else {
            echo "No skills listed.";
        }
        ?>
    </p>

    <h3 class="section-title">Professional Summary</h3>
    <div class="bio">
        <?php echo nl2br(htmlspecialchars($candidate['bio'] ?? 'No biography provided.')); ?>
    </div>

    <?php if(!empty($candidate['portfolio_url'])): ?>
    <h3 class="section-title">Portfolio / Links</h3>
    <a href="<?php echo htmlspecialchars($candidate['portfolio_url']); ?>" target="_blank" style="color: #1a73e8;">View Portfolio Listing</a>
    <?php endif; ?>

    <br>
    <a href="view_applicants.php" class="btn-back">← Back to Applicants</a>
</div>

</body>
</html>