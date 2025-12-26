<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php");
    exit();
}

$job_id = $_GET['id']; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);
    
    
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); } 
    
    $file_name = time() . "_" . basename($_FILES["resume"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        
        $sql = "INSERT INTO applications (job_id, user_id, resume_path, cover_letter, status, created_at) 
                VALUES ('$job_id', '$user_id', '$target_file', '$cover_letter', 'pending', NOW())";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Application submitted!'); window.location='candidate_dashboard.php';</script>";
        }
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Job</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 50px; }
        .form-box { background: white; padding: 30px; max-width: 500px; margin: auto; border-radius: 10px; }
        textarea, input { width: 100%; margin-bottom: 15px; padding: 10px; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Submit Your Application</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Upload Resume (PDF only):</label>
            <input type="file" name="resume" accept=".pdf" required>

            <label>Cover Letter:</label>
            <textarea name="cover_letter" rows="5" placeholder="Why should we hire you?"></textarea>

            <button type="submit" style="background:#28a745; color:white; padding:10px; width:100%; border:none; cursor:pointer;">
                Submit Application
            </button>
        </form>
        <a href="candidate_dashboard.php">Cancel</a>
    </div>
</body>
</html>