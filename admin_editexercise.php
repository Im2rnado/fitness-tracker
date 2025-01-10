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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_exercise"])) {
        $type = $_POST['type'];
        $name = $_POST['name'];
        $durability = $_POST['durability'] . " mins";
        $description = $_POST['description'];

        $stmt = $conn->prepare("UPDATE exercises SET type=?, name=?, durability=?, description=? WHERE name=?");
        $stmt->bind_param("sssss", $type, $name, $durability, $description, $name);
        try {
            if ($stmt->execute()) {
                header("Location: admin_exercises.php?edit_success");
            } else {
                header("Location: admin_exercises.php?edit_error");
            }
        } catch (Exception $e) {
            header("Location: admin_exercises.php?error");
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["name"])) {
        $name = $_GET["name"];
        $name = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($name));
        $name = html_entity_decode($name, 0, 'UTF-8');

        $result = $conn->query("SELECT * FROM exercises WHERE name = '" . $name . "'");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $type = $row["type"];
                $name = $row["name"];
                $durability = $row["durability"];
                str_replace(" mins", "", $durability);
                $durability = (int) $durability;
                $description = $row["description"];
            }
        } else {
            echo "No data found for the given Exercise Name.";
        }
    }
    ?>
    <!----------------------->
    <title>Fitness - Edit Exercise</title>
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
        <p class="admin-titles">Edit Exercise</p>
        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <b><label class="form-label" for="type">Type:</label></b>
            <select class="form-input" id="type" name="type" required>
                <option value="cardio" <?php echo ($type == 'cardio') ? 'selected' : ''; ?>>Cardio</option>
                <option value="strength" <?php echo ($type == 'strength') ? 'selected' : '' ?>>Strength</option>
                <option value="flexibility" <?php echo ($type == 'flexibility') ? 'selected' : ''; ?>>Flexibility</option>
                <option value="balance" <?php echo ($type == 'balance') ? 'selected' : ''; ?>>Balance</option>
            </select>
            <br><br>

            <b><label class="form-label" for="name">Name:</label></b>
            <input class="form-input" type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            <br><br>

            <b><label class="form-label" for="durability">Durability (minutes):</label></b>
            <input class="form-input" type="number" id="durability" name="durability" value="<?php echo $durability; ?>"
                required>
            <br><br>

            <b><label class="form-label" for="description">Description:</label></b>
            <textarea class="form-input" type="text" id="description" name="description"required><?php echo $description; ?></textarea>
            <br><br>

            <button class="form-button" name="edit_exercise" type="submit">Edit</button>
        </form>
    </div>
</body>

</html>