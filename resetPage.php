<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="styleSheet.css">
    <?php
    if ($_POST) {
        if (isset($_POST['reset']) && $_POST['reset'] == "Reset Password") {
            include_once 'dbconnect.php';

            $username = $_POST['username1'];
            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            // Check if the new passwords match
            if ($newPassword !== $confirmPassword) {
                echo '<script>alert("New passwords do not match. Please try again.");</script>';
            } else {
                // Check if the username and old password match
                $query = "SELECT * FROM users WHERE username='$username' AND password=md5('$oldPassword')";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // Username and old password match, update the password
                    $updateQuery = "UPDATE users SET password=md5('$newPassword') WHERE username='$username'";

                    try {
                        $conn->query($updateQuery);
                        header("Location: loginPage.php?reset=success");
                    } catch (Exception $e) {
                        echo '<p class="incorrect">' . $e->getMessage() . '</p>';
                    }
                } else {
                    echo '<script>alert("Invalid username or old password. Please try again.");</script>';
                }
            }

            $conn->close();
        }
    }
    ?>
</head>

<body>
    <div class="login-container">
        <h1>Reset Password</h1>
        <form action="resetPage.php" method="post">

            <div class="form-group">
                <h2 for="username1">Username:</h2>
                <input class="input-style" type="text" id="username1" name="username1" required>
            </div>

            <div class="form-group">
                <h2 for="oldPassword">Old Password:</h2>
                <input class="input-style" type="password" id="oldPassword" name="oldPassword" required>
            </div>

            <div class="form-group">
                <h2 for="newPassword">New Password:</h2>
                <input class="input-style" type="password" id="newPassword" name="newPassword" required>
            </div>

            <div class="form-group">
                <h2 for="confirmPassword">Re-Enter New Password:</h2>
                <input class="input-style" type="password" id="confirmPassword" name="confirmPassword" required>
            </div>

            <br>

            <div class="form-group">
                <button type="submit" name="reset" value="Reset Password">Reset Password</button>
            </div>
        </form>
    </div>
</body>

</html>