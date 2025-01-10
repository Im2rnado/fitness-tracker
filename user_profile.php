<?php
// Database connection
include_once 'dbconnect.php';
session_start();
if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != "1") {
    session_destroy();
    header("Location: loginPage.php");
    exit();
}

// Assuming the user_id is fetched based on session or other authentication
$user_id = $_SESSION['user_id'];

// Fetch user information from info_user
$user_info_sql = "SELECT age, weight, height, city FROM info_user WHERE user_id = $user_id";
$user_info_result = $conn->query($user_info_sql);

if ($user_info_result->num_rows > 0) {
    $user_info = $user_info_result->fetch_assoc();
    $age = $user_info['age'];
    $weight = $user_info['weight'];
    $height = $user_info['height'];
    $city = $user_info['city'];
} else {
    header("Location: infoPage.php");
    exit();
}

// Update user information if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_age = $_POST['age'];
    $new_weight = $_POST['weight'];
    $new_height = $_POST['height'];
    $new_city = $_POST['city'];

    $update_sql = "UPDATE info_user SET age = '$new_age', weight = '$new_weight', height = '$new_height', city = '$new_city' WHERE user_id = $user_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Record updated successfully');</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User's profile information </title>
    <link rel="stylesheet" href="user3.css">
</head>

<body>

    <div class="header">
        <h1>User's profile information </h1>
        <div class="buttons">
            <button onclick="window.location.href='user_dashboard.php'">User Dashboard</button>
            <button onclick="window.location.href='user_exercise.php'">Edit Exercises</button>
            <button class="logout" onclick="window.location.href='logoutPage.php'">Logout</button>
        </div>
    </div>

    <div class="form-container">
        <h2>Edit Profile</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>" required>
            </div>
            <div class="form-group">
                <label for="weight">Weight:</label>
                <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($weight); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="height">Height:</label>
                <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($height); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Update Profile</button>
            </div>
        </form>
    </div>

</body>

</html>