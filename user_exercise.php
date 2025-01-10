<?php
// Database connection
include_once 'dbconnect.php';
session_start();
if (!isset($_SESSION['valid_user']) || $_SESSION['valid_user'] != "1") {
    session_destroy();
    header("Location: loginPage.php");
    exit();
}

// Assuming the user_id is fetched based on session or other authentication
$user_id = $_SESSION['user_id'];

// Fetch exercise types
$types_sql = "SELECT DISTINCT type FROM exercises";
$types_result = $conn->query($types_sql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_exercise'])) {
    $exercise = $_POST['exercise'];

    // Insert the selected exercise into the enrolled table
    $insert_sql = "INSERT INTO enrolled (user_id, exercise_name, enrolled_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("is", $user_id, $exercise);

    if ($stmt->execute()) {
        echo "<script>alert('Exercise added successfully');</script>";
    } else {
        echo "<script>alert('Error " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Handle delete exercise
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_exercise'])) {
    $exercise_name = $_POST['exercise_name'];

    // Delete the selected exercise from the enrolled table
    $delete_sql = "DELETE FROM enrolled WHERE user_id = ? AND exercise_name = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("is", $user_id, $exercise_name);

    if ($stmt->execute()) {
        echo "<script>alert('Exercise deleted successfully');</script>";
    } else {
        echo "<script>alert('Error " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch enrolled exercises
$enrolled_sql = "SELECT exercise_name, enrolled_date FROM enrolled WHERE user_id = ?";
$stmt = $conn->prepare($enrolled_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$enrolled_result = $stmt->get_result();
$enrolled_exercises = $enrolled_result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// Handle AJAX requests for fetching exercises and details
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'get_exercises') {
        $type = $_GET['type'];

        // Fetch exercises by type
        $exercises_sql = "SELECT name FROM exercises WHERE type = ?";
        $stmt = $conn->prepare($exercises_sql);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();

        $exercises = array();
        while ($row = $result->fetch_assoc()) {
            $exercises[] = $row;
        }

        echo json_encode($exercises);
        $stmt->close();
        $conn->close();
        exit();
    } elseif ($_GET['action'] == 'get_exercise_details') {
        $name = $_GET['name'];

        // Fetch exercise details by name
        $details_sql = "SELECT durability, description FROM exercises WHERE name = ?";
        $stmt = $conn->prepare($details_sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        $exercise = $result->fetch_assoc();
        echo json_encode($exercise);
        $stmt->close();
        $conn->close();
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Exercises</title>
    <link rel="stylesheet" href="user2.css">
    <script>
        async function fetchExercisesByType(type) {
            try {
                console.log(`Fetching exercises for type: ${type}`); // Debugging output
                const response = await fetch(`user_exercise.php?action=get_exercises&type=${type}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const exercises = await response.json();
                console.log('Exercises fetched:', exercises); // Debugging output
                const exerciseSelect = document.getElementById('exercise');
                exerciseSelect.innerHTML = '<option value="">Select Exercise</option>';
                exercises.forEach(exercise => {
                    const option = document.createElement('option');
                    option.value = exercise.name;
                    option.textContent = exercise.name;
                    exerciseSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching exercises:', error);
            }
        }

        async function fetchExerciseDetails(name) {
            try {
                console.log(`Fetching details for exercise: ${name}`); // Debugging output
                const response = await fetch(`user_exercise.php?action=get_exercise_details&name=${name}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const exercise = await response.json();
                console.log('Exercise details fetched:', exercise); // Debugging output
                document.getElementById('durability').value = exercise.durability;
                document.getElementById('description').value = exercise.description;
            } catch (error) {
                console.error('Error fetching exercise details:', error);
            }
        }
    </script>
</head>

<body>
    <div class="header">
        <h1>User's Exercises</h1>
        <div class="buttons">
            <button onclick="window.location.href='user_dashboard.php'">User Dashboard</button>
            <button onclick="window.location.href='user_profile.php'">Edit Profile</button>
            <button class="logout" onclick="window.location.href='logoutPage.php'">Logout</button>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Add Exercise</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="type">Exercise Type:</label>
                    <select id="type" name="type" onchange="fetchExercisesByType(this.value)" required>
                        <option value="">Select Type</option>
                        <?php
                        if ($types_result->num_rows > 0) {
                            while ($row = $types_result->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['type']) . '">' . htmlspecialchars($row['type']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exercise">Exercise Name:</label>
                    <select id="exercise" name="exercise" onchange="fetchExerciseDetails(this.value)" required>
                        <option value="">Select Exercise</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="durability">Durability:</label>
                    <input type="text" id="durability" name="durability" readonly>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" readonly>
                </div>
                <div class="form-group">
                    <button type="submit" name="add_exercise">Add Exercise</button>
                </div>
            </form>
        </div>

        <div class="form-container">
            <h2>Delete Exercise</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="exercise_name">Exercise Name to Delete:</label>

                    <select id="exercise_name" name="exercise_name" required>
                        <option value="">Select Exercise</option>
                        <?php
                        foreach ($enrolled_exercises as $exercise) {
                            echo '<option value="' . htmlspecialchars($exercise['exercise_name']) . '">' . htmlspecialchars($exercise['exercise_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="delete_exercise" class="delete-button">Delete Selected Exercise</button>
                </div>
            </form>

            <h2>Your Enrolled Exercises</h2>
            <table>
                <thead>
                    <tr>
                        <th>Exercise Name</th>
                        <th>Enrollment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($enrolled_exercises)) {
                        foreach ($enrolled_exercises as $exercise) {
                            echo "<tr><td>" . htmlspecialchars($exercise['exercise_name']) . "</td><td>" . htmlspecialchars($exercise['enrolled_date']) . "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No enrolled exercises found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>