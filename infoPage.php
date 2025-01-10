<!DOCTYPE html>
<html>

<head>
    <title>Fill In Info</title>
    <link rel="stylesheet" href="styleSheet.css">
    <?php
    include_once 'dbconnect.php';
    session_start();
    if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != "1") {
        session_destroy();
        header("Location: loginPage.php");
        exit();
    }

    if ($_POST) {
        if (isset($_POST['submit']) && $_POST['submit'] == "submit") {
            $user_id = $_SESSION['user_id'];
            $age = $_POST['age1'];
            $weight = $_POST['weight1'];
            $height = $_POST['height1'];
            $city = $_POST['city1'];
    
            // Create SQL query
            $sql = "INSERT INTO info_user (user_id, age, weight, height, city) VALUES ('$user_id', '$age', '$weight', '$height', '$city')";

            // Execute the query
            try {
                $conn->query($sql);
                header("Location: user_dashboard.php");
            } catch (Exception $e) {
                echo '<p class="incorrect">' . $e->getMessage() . '</p>';
            }

            // Close the database connection
            $conn->close();
        }
    }
    ?>
</head>

<body>
    <div class="login-container">
        <h1>Fill In Your Info</h1>
        <form action="infoPage.php" method="post">
            <div class="form-group">
                <h2 for="age1">Age:</h2>
                <input class="input-style" type="number" min="5" max="110" id="age1" name="age1" required>
            </div>

            <div class="form-group">
                <h2 for="weight1">Weight (kg):</h2>
                <input class="input-style" type="number" min="10" max="300"  id="weight1" name="weight1" required>
            </div>

            <div class="form-group">
                <h2 for="height1">Height (cm):</h2>
                <input class="input-style" type="number" min="10" max="250"  id="height1" name="height1" required>
            </div>

            <div class="form-group">
                <h2 for="city1">City:</h2>
                <input class="input-style" type="text" id="city1" name="city1" required>
            </div>

            <br>

            <div class="form-group">
                <button type="submit" name="submit" value="submit">Submit</button>
            </div>

        </form>
    </div>
</body>

</html>