<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_with_string($conn, $_POST['title']);
    $location = mysqli_real_escape_with_string($conn, $_POST['location']);
    $salary = mysqli_real_escape_with_string($conn, $_POST['salary']);
    $description = mysqli_real_escape_with_string($conn, $_POST['description']);
    $user_id = $_SESSION['user_id'];

   $sql = "INSERT INTO jobs (user_id, title, description, location, salary, status, created_at) 
        VALUES ('$user_id', '$title', '$description', '$location', '$salary', 'Open', NOW())";
    if ($conn->query($sql) === TRUE) {
        header("Location: employer_dashboard.php?msg=Job posted successfully");
    } else {
        echo "Error: " . $conn->error;
    }
}

// Function to clean input data
function mysqli_real_escape_with_string($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post a New Job</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .form-container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #27ae60; color: white; border: none; padding: 15px; width: 100%; cursor: pointer; font-size: 16px; border-radius: 4px; }
        button:hover { background: #219150; }
        .back-link { display: block; margin-top: 15px; text-align: center; color: #666; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Post a New Vacancy</h2>
    <form method="POST">
        <label>Job Title</label>
        <input type="text" name="title" placeholder="e.g. Junior Web Developer" required>

        <label>Location</label>
<select name="location" id="locationSelect" onchange="checkLocation(this)" required>
    <option value="" disabled selected>Select location</option>
    <option value="Remote">Remote</option>
    <option value="Kuala Lumpur">Kuala Lumpur</option>
    <option value="other">Others (Please specify)</option>
</select>

<input type="text" name="custom_location" id="customLocation" placeholder="Enter your city" style="display:none; margin-top: 10px;">

<script>
function checkLocation(select) {
    const customInput = document.getElementById('customLocation');
    if (select.value === 'other') {
        customInput.style.display = 'block';
        customInput.required = true;
    } else {
        customInput.style.display = 'none';
        customInput.required = false;
    }
}
</script>

        <label>Monthly Salary (Optional)</label>
        <input list="salary-options" name="salary" placeholder="e.g. RM 3,000 - RM 4,500">

            <datalist id="salary-options">
                <option value="RM 2,000 - RM 3,500">
                <option value="RM 3,500 - RM 5,000">
                <option value="RM 5,000 - RM 7,000">
                <option value="Above RM 7,000">
            </datalist>

        <label>Job Description</label>
        <textarea name="description" rows="6" placeholder="Describe the roles, responsibilities, and requirements..." required></textarea>

        <button type="submit">Publish Job</button>
    </form>
    <a href="employer_dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>