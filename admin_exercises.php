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
    $sql = "SELECT * FROM exercises";
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
        echo '<script>alert("Exercise Created Successfully");</script>';
    if (isset($_GET["deleted"]))
        echo '<script>alert("Exercise Deleted Successfully");</script>';
    if (isset($_GET["error"]))
        echo '<script>alert("Exercise could not be created");</script>';
    if (isset($_GET["edit_success"]))
        echo '<script>alert("Exercise Edited Successfully");</script>';
    if (isset($_GET["edit_error"]))
        echo '<script>alert("Exercise could not be edited");</script>';

    if (isset($_POST["submit_new"])) {
        $type = $_POST['type'];
        $name = $_POST['name'];
        $durability = $_POST['durability'] . " mins";
        $description = $_POST['description'];

        $sql = "INSERT INTO exercises (type, name, durability, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $type, $name, $durability, $description);
        try {
            if ($stmt->execute()) {
                header("Location: admin_exercises.php?success");
            } else {
                header("Location: admin_exercises.php?error");
            }
        } catch (Exception $e) {
            header("Location: admin_exercises.php?error");
        }
        exit();
    }

    if (isset($_POST['delete_exercise'])) {
        $name = $_POST['name'];
        $sql = "DELETE FROM exercises WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        header("Location: admin_exercises.php?deleted");
        exit();
    }
    ?>
    <!----------------------->
    <title>Fitness - Admin Exercises</title>
    <meta charset="utf-8">
    <meta name="description" content="fitness site">
    <meta name="keywords" content="fitness, health, gym">
    <meta name="author" content="riders">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleSheet.css">
    <script>
        function confirmDelete() {
            let conf = confirm("Are you sure you want to delete this exercise?");
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
            <p class="admin-titles">Manage Exercises</p>
            <table id="admin-table" class="admin-table">
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Durability</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($data as $exercise) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($exercise['type']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['name']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['durability']); ?></td>
                        <td style="font-size:medium;text-align:left;">
                            <?php echo substr(htmlspecialchars($exercise['description']), 0, 40); ?>
                        </td>
                        <td>
                            <form id="deleteForm-<?php echo $exercise['name']; ?>" method="post"
                                action="admin_exercises.php" style="display:inline;">
                                <input type="hidden" name="name" value="<?php echo $exercise['name']; ?>">
                                <button class="contact-button-no" name="delete_exercise" type="submit"
                                    onclick="return confirmDelete()">Delete</button>
                            </form>
                            <?php echo "<a href='admin_editexercise.php?name=" . $exercise['name'] . "'><button class='contact-button-yes'>Edit</button></a>"; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="admin-inside">
            <p class="admin-titles">New Exercise</p>
            <form class="form" method="post" action="admin_exercises.php">
                <b><label class="form-label" for="type">Type:</label></b>
                <select class="form-input" id="type" name="type" required>
                    <option value="cardio">Cardio</option>
                    <option value="strength">Strength</option>
                    <option value="flexibility">Flexibility</option>
                    <option value="balance">Balance</option>
                </select>
                <br><br>

                <b><label class="form-label" for="name">Name:</label></b>
                <input class="form-input" type="text" id="name" name="name" required>
                <br><br>

                <b><label class="form-label" for="durability">Durability (minutes):</label></b>
                <input class="form-input" type="number" id="durability" name="durability" required>
                <br><br>

                <b><label class="form-label" for="description">Description:</label></b>
                <textarea class="form-input" type="" id="description" name="description" required>
                </textarea>
                <br><br>

                <button class="form-button" name="submit_new" type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>