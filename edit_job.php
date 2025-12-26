<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

$job_id = mysqli_real_escape_string($conn, $_GET['id']);
$employer_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM jobs WHERE id = '$job_id' AND user_id = '$employer_id'");
$job = $result->fetch_assoc();

if (!$job) { die("Job not found or unauthorized."); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE jobs SET 
            title='$title', 
            location='$location', 
            salary='$salary', 
            description='$description',
            status='$status' 
            WHERE id='$job_id' AND user_id='$employer_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: employer_dashboard.php?msg=Job updated successfully");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Job</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .form-container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-update { background: #3498db; color: white; border: none; padding: 15px; width: 100%; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Job Posting</h2>
    <form method="POST">
        <label>Job Title</label>
        <input type="text" name="title" value="<?php echo $job['title']; ?>" required>

        <label>Location</label>
        <input type="text" name="location" value="<?php echo $job['location']; ?>" required>

      <label>Monthly Salary (Optional)</label>
    <input 
        list="salary-options" 
        name="salary" 
        placeholder="e.g. RM 3,000 - RM 4,500" 
         value="<?php echo htmlspecialchars($job['salary']); ?>"
    >

    <datalist id="salary-options">
        <option value="RM 2,000 - RM 3,500">
        <option value="RM 3,500 - RM 5,000">
        <option value="RM 5,000 - RM 7,000">
        <option value="Above RM 7,000">
    </datalist>

        <label>Status</label>
        <select name="status">
            <option value="Open" <?php if($job['status'] == 'Open') echo 'selected'; ?>>Open</option>
            <option value="Closed" <?php if($job['status'] == 'Closed') echo 'selected'; ?>>Closed</option>
        </select>

        <label>Description</label>
        <textarea name="description" rows="6" required><?php echo $job['description']; ?></textarea>

        <button type="submit" class="btn-update">Update Job</button>
    </form>
    <a href="employer_dashboard.php" style="display:block; text-align:center; margin-top:10px;">Cancel</a>
</div>

</body>
</html>