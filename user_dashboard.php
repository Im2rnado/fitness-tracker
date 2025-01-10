<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="user.css">
</head>

<body>
    <?php
    include_once 'dbconnect.php';
    session_start();
    if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != "1") {
        session_destroy();
        header("Location: loginPage.php");
        exit();
    }

    // Assuming the user_id is fetched based on session or other authentication
    $user_id = $_SESSION['user_id'];
    
    // Fetch user information
    $user_sql = "SELECT username FROM users WHERE id = $user_id";
    $user_result = $conn->query($user_sql);

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $username = $user['username'];
    } else {
        echo "User not found.";
        exit();
    }

    // Fetch enrolled exercises
    $enrolled_sql = "SELECT exercise_name, enrolled_date FROM enrolled WHERE user_id = $user_id";
    $enrolled_result = $conn->query($enrolled_sql);

    // Fetch user detailed information
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
    ?>

    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
        <div class="buttons">
            <button onclick="window.location.href='user_profile.php'">Edit Profile</button>
            <button onclick="window.location.href='user_exercise.php'">Edit Exercises</button>
            <button class="logout" onclick="window.location.href='logoutPage.php'">Logout</button>
        </div>
    </div>

    <div class="dashboard">
        <h2>Your Enrolled Exercises</h2>
        <table>
            <tr>
                <th>Exercise Name</th>
                <th>Enrollment Date</th>
            </tr>
            <?php
            if ($enrolled_result->num_rows > 0) {
                while ($row = $enrolled_result->fetch_assoc()) {
                    echo "<tr><td>" . htmlspecialchars($row['exercise_name']) . "</td><td>" . htmlspecialchars($row['enrolled_date']) . "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No enrolled exercises found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="user-info">
        <h2>Your Information</h2>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
        <p><strong>Weight:</strong> <?php echo htmlspecialchars($weight); ?> kg</p>
        <p><strong>Height:</strong> <?php echo htmlspecialchars($height); ?> cm</p>
        <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?></p>
    </div>

    <?php
    $conn->close();
    ?>
</body>

</html>