<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $portfolio = mysqli_real_escape_string($conn, $_POST['portfolio_url']);

  
    $sql_user = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";
    $conn->query($sql_user);
    $_SESSION['name'] = $name; 

    
    if (!empty($_POST['new_password'])) {
        $new_pass = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        $conn->query("UPDATE users SET password='$new_pass' WHERE id='$user_id'");
    }

    // Update/Insert Professional Profile
    $check = $conn->query("SELECT * FROM profiles WHERE user_id = '$user_id'");
    if ($check->num_rows > 0) {
        $sql_prof = "UPDATE profiles SET skills='$skills', bio='$bio', portfolio_url='$portfolio' WHERE user_id='$user_id'";
    } else {
        $sql_prof = "INSERT INTO profiles (user_id, skills, bio, portfolio_url) VALUES ('$user_id', '$skills', '$bio', '$portfolio')";
    }
    
    if ($conn->query($sql_prof)) {
        $success = "Account and Profile updated successfully!";
    }
}


$sql_fetch = "SELECT users.name, users.email, profiles.skills, profiles.bio, profiles.portfolio_url 
              FROM users 
              LEFT JOIN profiles ON users.id = profiles.user_id 
              WHERE users.id = '$user_id'";
$result = $conn->query($sql_fetch);
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Account & Profile</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .section-title { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 25px; color: #1a73e8; }
        label { display: block; margin-top: 15px; font-weight: bold; font-size: 0.9rem; }
        input, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-save { background: #1a73e8; color: white; border: none; padding: 15px; width: 100%; border-radius: 6px; cursor: pointer; font-size: 1rem; margin-top: 20px; }
        .btn-save:hover { background: #1557b0; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Account Settings</h1>
    <?php if ($success) echo "<div class='msg'>$success</div>"; ?>

    <form method="POST">
        <h3 class="section-title">Personal Information</h3>
        <label>Full Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required>

        <label>Email Address</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>

        <label>New Password (Leave blank to keep current)</label>
        <input type="password" name="new_password" placeholder="••••••••">

        <h3 class="section-title">Professional Profile</h3>
        <label>Skills</label>
        <input type="text" name="skills" value="<?php echo htmlspecialchars($data['skills'] ?? ''); ?>" placeholder="PHP, Java, Marketing...">

        <label>Bio</label>
        <textarea name="bio" rows="4"><?php echo htmlspecialchars($data['bio'] ?? ''); ?></textarea>

        <label>Portfolio URL</label>
        <input type="url" name="portfolio_url" value="<?php echo htmlspecialchars($data['portfolio_url'] ?? ''); ?>">

        <button type="submit" class="btn-save">Update Everything</button>
    </form>
    
    <p style="text-align: center;"><a href="candidate_dashboard.php" style="color: #666; text-decoration: none;">← Back to Home</a></p>
</div>

</body>
</html>