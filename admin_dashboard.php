<!DOCTYPE html>
<html>

<head>
    <?php
    include_once 'dbconnect.php';
    session_start();
    if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != "1") {
        session_destroy();
        header("Location: loginPage.php");
        exit();
    }

    // Fetch data from users table
    $users_sql = "SELECT * FROM users";
    $users_result = $conn->query($users_sql);
    $users_data = array();
    if ($users_result->num_rows > 0) {
        while ($row = $users_result->fetch_assoc()) {
            $users_data[] = $row;
        }
    }

    // Fetch data from info table
    $info_sql = "SELECT info_user.user_id, users.username, info_user.age, info_user.weight, info_user.height, info_user.city 
            FROM info_user 
            JOIN users ON info_user.user_id = users.id";
    $info_result = $conn->query($info_sql);
    $info_data = array();
    if ($info_result->num_rows > 0) {
        while ($row = $info_result->fetch_assoc()) {
            $info_data[] = $row;
        }
    }

    // Fetch data from exercises table
    $exercises_sql = "SELECT * FROM exercises";
    $exercises_result = $conn->query($exercises_sql);
    $exercises_data = array();
    if ($exercises_result->num_rows > 0) {
        while ($row = $exercises_result->fetch_assoc()) {
            $exercises_data[] = $row;
        }
    }

    // Fetch data from enrolled table
    $enrolled_sql = "SELECT enrolled.user_id, users.username, enrolled.exercise_name, enrolled.enrolled_date 
            FROM enrolled 
            JOIN users ON enrolled.user_id = users.id";
    $enrolled_result = $conn->query($enrolled_sql);
    $enrolled_data = array();
    if ($enrolled_result->num_rows > 0) {
        while ($row = $enrolled_result->fetch_assoc()) {
            $enrolled_data[] = $row;
        }
    }
    ?>
    <title>Fitness - Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="description" content="fitness site">
    <meta name="keywords" content="fitness, health, gym">
    <meta name="author" content="riders">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleSheet.css">
</head>

<body class="body">
    <div class="admin-logo"><img src="assets/logo.png" class="admin-logo-img"></div>
    <div class="admin-navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_exercises.php">Exercises</a>
        <a href="admin_enrolled.php">Enrolled</a>
        <a class="logout" href="logoutPage.php">Logout</a>
        <!-- Add other links as necessary -->
    </div>

    <h1 class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

    <div class="admin-div">
        <div class="admin-inside" style="width:500px;">
            <p class="admin-titles">All Users</p>
            <table id="admin-table" class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Type</th>
                </tr>
                <?php foreach ($users_data as $user) { ?>
                    <tr>
                        <td style="font-size:large;"><?php echo htmlspecialchars($user['id']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($user['type']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="admin-inside" style="width:610px;">
            <p class="admin-titles">Enrollments</p>
            <table id="admin-table" class="admin-table">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Exercise Name</th>
                    <th>Enrolled Date</th>
                </tr>
                <?php foreach ($enrolled_data as $enrollment) { ?>
                    <tr>
                        <td style="font-size:large;"><?php echo htmlspecialchars($enrollment['user_id']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($enrollment['username']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($enrollment['exercise_name']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($enrollment['enrolled_date']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <div class="admin-div" style="margin-top:-7%;">
        <div class="admin-inside" style="width:450px;">
            <p class="admin-titles">Users Info</p>
            <table id="admin-table" class="admin-table">
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Weight</th>
                    <th>Height</th>
                    <th>City</th>
                </tr>
                <?php foreach ($info_data as $info) { ?>
                    <tr>
                        <td style="font-size:large;"><?php echo htmlspecialchars($info['username']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($info['age']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($info['weight']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($info['height']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($info['city']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="admin-inside" style="width:650px;">
            <p class="admin-titles">Exercises</p>
            <table id="admin-table" class="admin-table">
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Durability</th>
                    <th>Description</th>
                </tr>
                <?php foreach ($exercises_data as $exercise) { ?>
                    <tr>
                        <td style="font-size:large;"><?php echo htmlspecialchars($exercise['type']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($exercise['name']); ?></td>
                        <td style="font-size:large;"><?php echo htmlspecialchars($exercise['durability']); ?></td>
                        <td style="font-size:medium;">
                            <?php echo substr(htmlspecialchars($exercise['description']), 0, 170); ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>

</html>