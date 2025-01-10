<!DOCTYPE html>
<html>

<head>
    <!----------------------->
    <?php
    include_once 'dbconnect.php';
    session_start();
    if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != "1") {
        session_destroy();
        header("Location: loginPage.php");
        exit();
    }
    ?>
    <!----------------------->
    <?php
    $sql = "SELECT enrolled.user_id, users.username, enrolled.exercise_name, enrolled.enrolled_date 
            FROM enrolled 
            JOIN users ON enrolled.user_id = users.id";
    $result = $conn->query($sql);
    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    ?>
    <!----------------------->
    <?php
    if (isset($_GET["deleted"]))
        echo '<script>alert("Enrollment Deleted Successfully");</script>';

    if (isset($_POST["submit_new"])) {
        $username = $_POST['username'];
        $exercise_name = $_POST['exercise_name'];
        $enrolled_date = $_POST['enrolled_date'];

        // Fetch user ID based on username
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        if ($user_id) {
            $sql = "INSERT INTO enrolled (user_id, exercise_name, enrolled_date) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $user_id, $exercise_name, $enrolled_date);
            if ($stmt->execute()) {
                echo "<script>alert('Enrollment created successfully');</script>";
            } else {
                echo "<script>alert('Error " . $stmt->error . "');</script>";
            }
            exit();
        } else {
            echo "<script>alert('Username not found');</script>";
        }
    }

    if (isset($_POST['delete_enrollment'])) {
        $user_id = $_POST['user_id'];
        $exercise_name = $_POST['exercise_name'];
        $sql = "DELETE FROM enrolled WHERE user_id = ? AND exercise_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $exercise_name);
        $stmt->execute();
        header("Location: admin_enrolled.php?deleted");
        exit();
    }
    ?>
    <!----------------------->
    <title>Fitness - Admin Enrolled</title>
    <meta charset="utf-8">
    <meta name="description" content="fitness site">
    <meta name="keywords" content="fitness, health, gym">
    <meta name="author" content="riders">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleSheet.css">
    <script>
        function confirmDelete() {
            let conf = confirm("Are you sure you want to delete this enrollment?");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }
    </script>
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

    <div class="admin-div">
        <div class="admin-inside">
            <p class="admin-titles">Manage Enrollments</p>
            <table id="admin-table" class="admin-table">
                <tr>
                    <th>Username</th>
                    <th>Exercise Name</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($data as $enrollment) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($enrollment['username']); ?></td>
                        <td><?php echo htmlspecialchars($enrollment['exercise_name']); ?></td>
                        <td><?php echo htmlspecialchars($enrollment['enrolled_date']); ?></td>
                        <td>
                            <form id="deleteForm-<?php echo $enrollment['user_id'] . '-' . $enrollment['exercise_name']; ?>"
                                method="post" action="admin_enrolled.php" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $enrollment['user_id']; ?>">
                                <input type="hidden" name="exercise_name"
                                    value="<?php echo $enrollment['exercise_name']; ?>">
                                <button class="contact-button-no" name="delete_enrollment" type="submit"
                                    onclick="return confirmDelete()">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="admin-inside">
            <p class="admin-titles">New Enrollment</p>
            <form class="form" method="post" action="admin_enrolled.php">
                <b><label class="form-label" for="username">Username:</label></b>
                <input class="form-input" type="text" id="username" name="username" required>
                <br><br>

                <b><label class="form-label" for="exercise_name">Exercise Name:</label></b>
                <input class="form-input" type="text" id="exercise_name" name="exercise_name" required>
                <br><br>

                <b><label class="form-label" for="enrolled_date">Enrolled Date:</label></b>
                <input class="form-input" type="date" id="enrolled_date" name="enrolled_date" required>
                <br><br>

                <button class="form-button" name="submit_new" type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>