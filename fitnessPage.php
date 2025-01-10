<!DOCTYPE html>
<html>

<head>
    <?php
    // Start session and check if user is logged in
    session_start();
    if (!$_SESSION['username']) {
        header("Location: loginPage.php"); // Redirect to login page if not logged in
        exit();
    }

    // Define arrays of exercises and descriptions for each category
    $cardio_exercises = array(
        "Jumping Jacks" => "Jumping jacks are a full-body workout that engage many muscle groups and help improve cardiovascular fitness.",
        "Running in Place" => "Running in place is a convenient cardio exercise that can be done indoors or outdoors, helping to improve endurance and burn calories.",
        "High Knees" => "High knees are a dynamic exercise that targets the lower body muscles, including the quadriceps, hamstrings, and glutes, while also providing a cardiovascular workout.",
        "Burpees" => "Burpees are a high-intensity exercise that work multiple muscle groups, including the chest, arms, shoulders, core, and legs, making them a great full-body workout.",
        "Jump Rope" => "Jumping rope is a classic cardio exercise that improves coordination, endurance, and cardiovascular health, while also toning muscles throughout the body.",
        "Mountain Climbers" => "Mountain climbers are a dynamic exercise that target the core, shoulders, chest, and legs, while also providing a cardiovascular challenge.",
        "Sprinting" => "Sprinting is an explosive cardio exercise that builds speed, power, and endurance, while also targeting the leg muscles.",
        "Box Jumps" => "Box jumps are a plyometric exercise that strengthen the legs, hips, and core, while also improving explosiveness and athletic performance."
    );

    $strength_exercises = array(
        "Push-ups" => "Push-ups are a classic bodyweight exercise that primarily target the chest, shoulders, and triceps, helping to build upper body strength.",
        "Squats" => "Squats are a compound exercise that target the muscles of the lower body, including the quadriceps, hamstrings, and glutes, while also engaging the core and stabilizing muscles.",
        "Plank" => "Plank is an isometric exercise that strengthens the core, shoulders, and back muscles, while also improving posture and stability.",
        "Lunges" => "Lunges are a unilateral exercise that target the muscles of the legs, including the quadriceps, hamstrings, and glutes, while also improving balance and coordination.",
        "Sit-ups" => "Sit-ups are a classic abdominal exercise that target the rectus abdominis muscle, helping to strengthen the core and improve abdominal definition.",
        "Deadlifts" => "Deadlifts are a compound exercise that target the muscles of the lower body, including the hamstrings, glutes, and lower back, while also engaging the core and upper body muscles.",
        "Pull-ups" => "Pull-ups are a challenging bodyweight exercise that primarily target the muscles of the back, including the latissimus dorsi and biceps, while also engaging the shoulders and core.",
        "Bicep Curls" => "Bicep curls are an isolation exercise that target the biceps muscles, helping to build strength and definition in the arms."
    );

    $flexibility_exercises = array(
        "Forward Bend" => "Forward bend stretches the hamstrings and lower back, promoting flexibility and relieving tension.",
        "Quad Stretch" => "Quad stretch targets the quadriceps muscles in the front of the thigh, helping to improve flexibility and prevent injury.",
        "Shoulder Stretch" => "Shoulder stretch helps to release tension in the shoulders and upper back, improving range of motion and reducing stiffness.",
        "Hip Flexor Stretch" => "Hip flexor stretch targets the muscles at the front of the hip, helping to alleviate tightness and improve flexibility.",
        "Hamstring Stretch" => "Hamstring stretch targets the muscles at the back of the thigh, helping to improve flexibility and prevent injury.",
        "Cobra Pose" => "Cobra pose is a yoga posture that stretches the muscles of the chest, abdomen, and spine, while also strengthening the back muscles.",
        "Child's Pose" => "Child's pose is a relaxing yoga posture that stretches the muscles of the back, hips, and thighs, while also promoting relaxation and stress relief.",
        "Pigeon Pose" => "Pigeon pose is a yoga posture that stretches the hip flexors, glutes, and outer thighs, helping to improve flexibility and alleviate tightness."
    );

    $balance_exercises = array(
        "Tree Pose" => "Tree pose is a yoga posture that helps improve balance, concentration, and focus.",
        "Single-leg Deadlift" => "Single-leg deadlifts improve balance and stability while targeting the hamstrings, glutes, and lower back.",
        "Standing Leg Lift" => "Standing leg lifts strengthen the muscles of the legs and hips, while also improving balance and coordination.",
        "Warrior III Pose" => "Warrior III pose is a yoga posture that challenges balance and strengthens the muscles of the legs, core, and back.",
        "Chair Pose" => "Chair pose is a yoga posture that strengthens the muscles of the legs and core, while also improving balance and concentration.",
        "Side Plank" => "Side plank is a core-strengthening exercise that also challenges balance and stability, while targeting the muscles of the obliques and shoulders.",
        "Bird Dog" => "Bird dog is a core-stabilizing exercise that improves balance and coordination, while also strengthening the muscles of the core, back, and hips.",
        "Bosu Ball Squats" => "Bosu ball squats are a balance exercise that target the muscles of the legs and core, while also improving stability and coordination."
    );

    // Get the current exercise type and index
    $exercise_type = isset($_POST["exercise_type"]) ? $_POST["exercise_type"] : "cardio"; // Default to cardio
    $current_index = 0;

    // Switch exercise type and set current index accordingly
    if (isset($_POST["submit1"])) {
        switch ($exercise_type) {
            case "cardio":
                $exercise_type = "strength";
                break;
            case "strength":
                $exercise_type = "flexibility";
                break;
            case "flexibility":
                $exercise_type = "balance";
                break;
            case "balance":
                $exercise_type = "cardio"; // Loop back to cardio
                break;
        }
    }

    switch ($exercise_type) {
        case "cardio":
            $current_index = isset($_POST["current_index"]) ? ($_POST["current_index"] + 1) % count($cardio_exercises) : 0; // This line of code is responsible for updating the current index of the exercise being displayed when the user clicks the "Next Category" button.
            $exercise_list = $cardio_exercises;
            break;
        case "strength":
            $current_index = isset($_POST["current_index"]) ? ($_POST["current_index"] + 1) % count($strength_exercises) : 0;
            $exercise_list = $strength_exercises;
            break;
        case "flexibility":
            $current_index = isset($_POST["current_index"]) ? ($_POST["current_index"] + 1) % count($flexibility_exercises) : 0;
            $exercise_list = $flexibility_exercises;
            break;
        case "balance":
            $current_index = isset($_POST["current_index"]) ? ($_POST["current_index"] + 1) % count($balance_exercises) : 0;
            $exercise_list = $balance_exercises;
            break;
    }

    // Welcome message for the user
    $welcome_message = "Welcome " . $_SESSION['username'];
    ?>

    <title>Fitness Page</title>
    <link rel="stylesheet" href="styleSheet.css">
</head>

<body>
    <!-- Page header with welcome message -->
    <div class="page-head">
        <text><?php echo $welcome_message; ?></text>
        <a href="loginPage.php" class="logout-btn">
            <img src="images/logout.png" alt="Logout">
        </a>
    </div>

    <!-- Page container -->
    <div class="page-container">
        <div>
            <?php
            // Display exercises based on selected exercise type
            switch ($exercise_type) {
                case "cardio":
                    echo "<h1>Cardio Exercises</h1>";
                    break;
                case "strength":
                    echo "<h1>Strength Exercises</h1>";
                    break;
                case "flexibility":
                    echo "<h1>Flexibility Exercises</h1>";
                    break;
                case "balance":
                    echo "<h1>Balance Exercises</h1>";
                    break;
            }
            ?>

            <!-- List of exercises with descriptions -->
            <ul>
                <?php
                foreach ($exercise_list as $exercise => $description) {
                    echo "<li>";
                    echo "<span class='exercise-name'>$exercise</span>"; // Exercise name
                    echo "<span class='exercise-description'> $description</span>"; // Exercise description
                    echo "</li>";
                }
                ?>
            </ul>

            <!-- Form to switch exercise categories -->
            <form action="" method="post">
                <input type="hidden" name="exercise_type" value="<?php echo $exercise_type; ?>">
                <input type="hidden" name="current_index" value="<?php echo $current_index; ?>">
                <div class="form-group">
                    <button name="submit1">Next Category</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>