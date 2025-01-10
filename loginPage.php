<!DOCTYPE html>
<html>

<head>
    <!-----DEFINE DB CONNECT----->
    <?php
    include_once 'dbconnect.php';
    $table1 = "users";
    $valid_user = 0;
    session_start();
    ?>
    <!-----LOGIN VALIDATION----->
    <?php
    if (isset($_POST["submit"])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $sql = 'SELECT * FROM users WHERE username = \'' . $user . '\' AND password = md5(\'' . $pass . '\')';

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $valid_user = 1;
            $_SESSION['valid_user'] = "1";
            $_SESSION['username'] = $user;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['type'] = $row['type'];

            if ($row['type'] == "admin")
                header("Location: admin_dashboard.php");
            else if ($row['type'] == "user")
                header("Location: user_dashboard.php");
        }
    }

    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location: loginPage.php");
    }
    ?>
    <title>Fitness - Login</title>
    <meta charset="utf-8">
    <meta name="description" content="fitness site">
    <meta name="keywords" content="fitness, health, gym">
    <meta name="author" content="riders">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleSheet.css">
</head>

<body>

    <div class="login-container">
        <h1>Login</h1>

        <!-- Display  messages if login fails -->
        <?php if (isset($_POST["submit"]) && $valid_user == 0): ?>
            <p class="incorrect">Invalid username or password. Please try again.</p>
        <?php endif; ?>
        <?php if (isset($_GET["signup"]) && $valid_user == 0): ?>
            <p class="correct">Signed Up Successfully. Please login now.</p>
        <?php endif; ?>
        <?php if (isset($_GET["reset"]) && $valid_user == 0): ?>
            <p class="correct">Password Reset Successfully. Please login now.</p>
        <?php endif; ?>

        <!-- Login form -->
        <form action="loginPage.php" method="post">
            <?php
            if (isset($_SESSION['valid_user']) && $_SESSION['valid_user'] == "1") {
                echo '<h3>You are already logged in as ' . $_SESSION['username'] . '<br>Go to the main page or logout</h3>';
                echo '<a href="user_dashboard.php">Main Page</a><br>';
                echo '<a href="logoutPage.php">Logout</button>';
                echo '</form>';
                echo '</div>';
                echo '</body>';
                echo '</html>';
                die;
            }
            ?>
            <div class="form-group">
                <h2 for="username">Username:</h2>
                <input class="input-style" type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <h2 for="password">Password:</h2>
                <input class="input-style" type="password" id="password" name="password" required>
            </div>

            <br>

            <div class="form-group">
                <button name="submit">Login</button>
            </div>

            <!-- Reset Password link -->
            <div class="forgot-password">
                <a href="signupPage.php">Signup</a>
                <br>
                <a href="resetPage.php">Reset Password?</a>
            </div>

        </form>
    </div>
</body>

</html>