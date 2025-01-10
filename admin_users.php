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
    $table = "users";
    $sql = "SELECT * FROM " . $table;
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
    if (isset($_GET["success"]))
        echo '<script>alert("User Created Successfully");</script>';
    if (isset($_GET["deleted"]))
        echo '<script>alert("User Deleted Successfully");</script>';
    if (isset($_GET["error"]))
        echo '<script>alert("User could not be created due to duplicate username");</script>';
    if (isset($_GET["edit_success"]))
        echo '<script>alert("User Edited Successfully");</script>';
    if (isset($_GET["edit_error"]))
        echo '<script>alert("User could not be edited due to duplicate username");</script>';

    if (isset($_POST["submit_new"])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $type = $_POST['type'];
        $sql = "INSERT INTO users (username, password, type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $type);
        try {
            if ($stmt->execute()) {
                header("Location: admin_users.php?success");
            } else {
                header("Location: admin_users.php?error");
            }
        } catch (Exception $e) {
            header("Location: admin_users.php?error");
        }
        exit();
    }

    if (isset($_POST['delete_user'])) {
        $userId = $_POST['id'];
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        header("Location: admin_users.php?deleted");
        exit();
    }
    ?>
    <!----------------------->
    <title>Fitness - Admin Users</title>
    <meta charset="utf-8">
    <meta name="description" content="fitness site">
    <meta name="keywords" content="fitness, health, gym">
    <meta name="author" content="riders">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleSheet.css">
    <script>
        function confirmDelete() {
            let conf = confirm("Are you sure you want to delete this user?")
            if (conf) {
                return true;
            }
            else {
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
            <p class="admin-titles">Manage Users</p>
            <table id="admin-table" class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($data as $user) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['type']); ?></td>
                        <td>
                            <form id="deleteForm-<?php echo $user['id']; ?>" method="post" action="admin_users.php"
                                style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <button class="contact-button-no" name="delete_user" type="submit"
                                    onclick="return confirmDelete()">Delete</button>
                            </form>
                            <?php echo "<a href='admin_edituser.php?user_id=" . $user['id'] . "'><button class='contact-button-yes'>Edit</button></a>"; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="admin-inside">
            <p class="admin-titles">Create New User</p>
            <form class="form" method="post" action="admin_users.php">
                <b><label class="form-label" for="username">Username:</label></b>
                <input class="form-input" type="text" id="username" name="username" required>
                <br><br>

                <b><label class="form-label" for="password">Password:</label></b>
                <input class="form-input" type="password" id="password" name="password" required>
                <br><br>

                <b><label class="form-label" for="type">Type:</label></b>
                <select class="form-input" id="type" name="type" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <br><br>

                <button class="form-button" name="submit_new" type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>