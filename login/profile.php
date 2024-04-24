<?php
session_start();

// Check if user is logged in, if not, redirect them to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "dgmk";
$password = "nouser1";
$database = "login"; // Database name
$table = "users"; // Table name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user details from the database based on the username in the session
$username = $_SESSION['username'];
$sql = "SELECT fullname, college, branch, specialization, dob FROM $table WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container Styles */
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
        }

        /* Heading Styles */
        h2 {
            color: #333;
            text-align: center;
        }

        /* User Details Styles */
        .user-details {
            margin-top: 20px;
        }

        .user-details p {
            margin-bottom: 10px;
            color: #555;
        }

        /* Logout Link Styles */
        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if(isset($user) && !empty($user)): ?>
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
            <div class="user-details">
                <h3>User Details:</h3>
                <p><strong>Full Name:</strong> <?php echo $user['fullname']; ?></p>
                <p><strong>College:</strong> <?php echo $user['college']; ?></p>
                <p><strong>Branch:</strong> <?php echo $user['branch']; ?></p>
                <p><strong>Specialization:</strong> <?php echo $user['specialization']; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $user['dob']; ?></p>
            </div>
            <a class="logout-link" href="index.html">Logout</a>
        <?php else: ?>
            <p>User details not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
