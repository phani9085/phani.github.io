<?php
session_start(); // Resume session
if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
    // Redirect to login page if user is not logged in
    header("Location: index.html");
    exit();
}

// Retrieve user details from session variables
$username = $_SESSION["username"];

// Logout script URL
$logout_url = "index.html";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            font-size: 18px;
            margin-bottom: 20px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
<body>
    <div>
        <h1>Thank You, <?php echo $username; ?>!</h1>
    </div>
    
    <!-- Logout button -->
    <form action="<?php echo $logout_url; ?>" method="post">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
