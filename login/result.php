<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "dgmk";
$password = "nouser1";
$database = "login"; // Database name
$table = "score";

// Establish connection to database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in, if not, redirect them to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve last exam score
$last_score = 0;
$username = $_SESSION['username'];
$sql_last_score = "SELECT score FROM $table WHERE username = ? ORDER BY id DESC LIMIT 1";
$stmt_last_score = $conn->prepare($sql_last_score);
$stmt_last_score->bind_param("s", $username);
$stmt_last_score->execute();
$result_last_score = $stmt_last_score->get_result();

if ($result_last_score->num_rows > 0) {
    $row = $result_last_score->fetch_assoc();
    $last_score = $row['score'];
}

$stmt_last_score->close();

// Retrieve past scores
$sql_past_scores = "SELECT score FROM $table WHERE username = ? ORDER BY id DESC";
$stmt_past_scores = $conn->prepare($sql_past_scores);
$stmt_past_scores->bind_param("s", $username);
$stmt_past_scores->execute();
$result_past_scores = $stmt_past_scores->get_result();

$past_scores = array();
while ($row = $result_past_scores->fetch_assoc()) {
    $past_scores[] = $row['score'];
}

$stmt_past_scores->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
        }

        h1 {
            color: #333;
        }

        .score {
            font-size: 4em;
            color: #007bff; /* Blue color */
            animation: animateScore 1s ease-in-out;
        }

        @keyframes animateScore {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        table {
            margin-top: 30px;
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff; /* Blue color */
            color: #fff;
        }

        td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quiz Result</h1>
        <p>Your last exam score:</p>
        <p class="score"><?php echo $last_score; ?></p>
        <table>
            <tr>
                <th>Past Scores</th>
            </tr>
            <?php foreach ($past_scores as $score) : ?>
                <tr>
                    <td><?php echo $score; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="dashboard.php">Well Done</a>
    </div>
</body>
</html>
