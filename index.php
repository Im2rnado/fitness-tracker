<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <h1 class="Fh1">Fitness Tracker</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="user_profile.php">Profile</a></li>
                <li><a href="user_exercise.php">Workouts</a></li>
                <li><a href="signupPage.php">Signup</a></li>
                <li><a href="loginPage.php">Login</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Welcome to Your Fitness Tracker</h2>
            <p>Track your workouts, nutrition, and progress all in one place.</p>
        </section>
        <section>
            <h2>Features</h2>
            <ul>
                <li>Personalized Workout Plans 💪</li>
                <li>Many exercises to choose from 🏋️‍♂️</li>
                <li>Progress Monitoring ✅</li>
                <li>Profile Customization 🙍‍♂️</li>
            </ul>
        </section>
        <section>
            <h2>Get Started</h2>
            <p>Ready to start your fitness journey? <a href="signupPage.php">Sign up now</a> or <a
                    href="loginPage.php">log in</a> to your account.</p>
        </section>
        <br>
    </main>
    <br>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Fitness Tracker. All rights reserved.</p>
    </footer>
</body>

</html>