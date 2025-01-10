<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="styleSheet.css">
    <?php
    if ($_POST) {
        if (isset($_POST['register']) && $_POST['register'] == "Register") {
            include_once 'dbconnect.php';

            $username = $_POST['username1'];
            $password = $_POST['password1'];
            $type = 'user'; // Set the default user type
    
            // Create SQL query
            $sql = "INSERT INTO users (username, password, type) VALUES ('$username', md5('$password'), '$type')";

            // Execute the query
            try {
                $conn->query($sql);
                header("Location: loginPage.php?signup=success");
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
        <h1>Sign Up</h1>
        <form action="signupPage.php" method="post">
            <div class="form-group">
                <h2 for="username1">Username:</h2>
                <input class="input-style" type="text" id="username1" name="username1" required>
            </div>

            <div class="form-group">
                <h2 for="password1">Password:</h2>
                <input class="input-style" type="password" id="password1" name="password1" required>
            </div>

            <br>

            <div class="form-group">
                <button type="submit" name="register" value="Register">Sign Up</button>
            </div>

            <!-- Reset Password link -->
            <div class="forgot-password">
                <a href="loginPage.php">Login</a>
                <br>
                <a href="resetPage.php">Reset Password?</a>
            </div>

        </form>
    </div>
</body>

</html>