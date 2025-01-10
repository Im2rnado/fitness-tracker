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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_user"])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $type = $_POST['type'];
        $id = $_POST['id'];

        $stmt = $conn->prepare("UPDATE users SET username=?, password=?, type=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $password, $type, $id);
        try {
            if ($stmt->execute()) {
                header("Location: admin_users.php?edit_success");
            } else {
                header("Location: admin_users.php?edit_error");
            }
        } catch (Exception $e) {
            header("Location: admin_users.php?error");
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["user_id"])) {
        $id = $_GET["user_id"];

        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $username = $row["username"];
                $password = "********";
                $type = $row["type"];
            }
        } else {
            echo "No data found for the given User ID.";
        }
    }
    ?>
    <!----------------------->
    <title>Fitness - Edit User</title>
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
    <div class="admin-inside">
        <p class="admin-titles">Edit User</p>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <b><label class="form-label" for="username">Username:</label></b>
            <input class="form-input" type="text" id="username" name="username" value="<?php echo $username; ?>"
                required>
            <br><br>

            <b><label class="form-label" for="password">Password:</label></b>
            <input class="form-input" type="password" id="password" name="password" value="<?php echo $password; ?>"
                required>
            <br><br>

            <b><label class="form-label" for="type">Type:</label></b>
            <select class="form-input" id="type" name="type" required>
                <option value="user" <?php echo ($type == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo ($type == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
            <br><br>

            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <button class="form-button" name="edit_user" type="submit">Edit</button>
        </form>
    </div>
</body>

</html>