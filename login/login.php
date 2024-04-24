<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Retrieve username and password from POST data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute SQL query to fetch user data
    $stmt = $conn->prepare("SELECT id, username, password FROM $table WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user["password"])) {
            // Password is correct, set session variables or redirect as needed
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            echo json_encode($user); // Return user data for client-side handling
        } else {
            // Password is incorrect
            http_response_code(401); // Unauthorized
            echo "Invalid password";
        }
    } else {
        // User does not exist
        http_response_code(404); // Not Found
        echo "User not found";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
