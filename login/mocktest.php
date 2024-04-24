<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "dgmk";
$password = "nouser1";
$database = "login"; // Database name
$table = "score"; // Table name

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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;

    // Validate each question
    // Assuming the correct answers are stored in an array
    $correct_answers = array("a", "b", "a", "c", "b", "c", "a", "b", "a", "b");

    // Loop through each question and check if it's correct
    for ($i = 1; $i <= 10; $i++) {
        $question_name = "q" . $i;
        if (isset($_POST[$question_name]) && $_POST[$question_name] == $correct_answers[$i - 1]) {
            $score++;
        }
    }

    // Store the score in the database
    $username = $_SESSION['username'];
    $sql_insert_score = "INSERT INTO $table (username, score) VALUES (?, ?)";
    $stmt_insert_score = $conn->prepare($sql_insert_score);
    $stmt_insert_score->bind_param("si", $username, $score);
    $stmt_insert_score->execute();
    $stmt_insert_score->close();

    // Redirect to a page showing the result or any other appropriate page
    header("Location: result.php?score=$score");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment</title>
    <script>
        function validateForm() {
            var score = 0;
            var correctAnswers = ["a", "b", "a", "c", "b", "c", "a", "b", "a", "b"];

            for (var i = 1; i <= 10; i++) {
                var questionName = "q" + i;
                var selectedOption = document.querySelector('input[name="' + questionName + '"]:checked');

                if (selectedOption !== null && selectedOption.value === correctAnswers[i - 1]) {
                    score++;
                }
            }

            alert("Your score is: " + score);

            return true;
        }
    </script>
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
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            margin-top: 0; /* Add margin to the top */
        }
        h2 {
            margin-bottom: 10px;
            color: #555;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        input[type="submit"] {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            max-width: 200px;
            margin: 20px auto 0;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
        <h1>Quiz</h1>
        <h2>Question 1: What is the capital of France?</h2>
        <input type="radio" name="q1" value="a"> Paris<br>
        <input type="radio" name="q1" value="b"> London<br>
        <input type="radio" name="q1" value="c"> Rome<br>
        <input type="radio" name="q1" value="d"> Berlin<br>

        <!-- Repeat the same for other questions -->

        <h2>Question 2: What is the largest planet in our solar system?</h2>
        <input type="radio" name="q2" value="a"> Mercury<br>
        <input type="radio" name="q2" value="b"> Venus<br>
        <input type="radio" name="q2" value="c"> Jupiter<br>
        <input type="radio" name="q2" value="d"> Saturn<br>

        <h2>Question 3: Who wrote the play "Romeo and Juliet"?</h2>
        <input type="radio" name="q3" value="a"> William Shakespeare<br>
        <input type="radio" name="q3" value="b"> Charles Dickens<br>
        <input type="radio" name="q3" value="c"> Jane Austen<br>
        <input type="radio" name="q3" value="d"> Mark Twain<br>

        <h2>Question 4: What is the chemical symbol for water?</h2>
        <input type="radio" name="q4" value="a"> Wa<br>
        <input type="radio" name="q4" value="b"> Wo<br>
        <input type="radio" name="q4" value="c"> H2O<br>
        <input type="radio" name="q4" value="d"> Wt<br>

        <h2>Question 5: What is the largest mammal?</h2>
        <input type="radio" name="q5" value="a"> Elephant<br>
        <input type="radio" name="q5" value="b"> Blue Whale<br>
        <input type="radio" name="q5" value="c"> Giraffe<br>
        <input type="radio" name="q5" value="d"> Gorilla<br>

        <h2>Question 6: Which planet is known as the Red Planet?</h2>
        <input type="radio" name="q6" value="a"> Venus<br>
        <input type="radio" name="q6" value="b"> Jupiter<br>
        <input type="radio" name="q6" value="c"> Mars<br>
        <input type="radio" name="q6" value="d"> Saturn<br>

        <h2>Question 7: Who discovered penicillin?</h2>
        <input type="radio" name="q7" value="a"> Alexander Fleming<br>
        <input type="radio" name="q7" value="b"> Louis Pasteur<br>
        <input type="radio" name="q7" value="c"> Robert Koch<br>
        <input type="radio" name="q7" value="d"> Jonas Salk<br>

        <h2>Question 8: What is the chemical symbol for gold?</h2>
        <input type="radio" name="q8" value="a"> Ag<br>
        <input type="radio" name="q8" value="b"> Au<br>
        <input type="radio" name="q8" value="c"> G<br>
        <input type="radio" name="q8" value="d"> Go<br>

        <h2>Question 9: Who painted the Mona Lisa?</h2>
        <input type="radio" name="q9" value="a"> Leonardo da Vinci<br>
        <input type="radio" name="q9" value="b"> Pablo Picasso<br>
        <input type="radio" name="q9" value="c"> Vincent van Gogh<br>
        <input type="radio" name="q9" value="d"> Michelangelo<br>

        <h2>Question 10: What is the capital of Japan?</h2>
        <input type="radio" name="q10" value="a"> Beijing<br>
        <input type="radio" name="q10" value="b"> Tokyo<br>
        <input type="radio" name="q10" value="c"> Seoul<br>
        <input type="radio" name="q10" value="d"> Bangkok<br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
