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

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO $table (fullname, username, password, college, branch, specialization, dob) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fullname, $username, $password, $college, $branch, $specialization, $dob);

    // Set parameters and execute
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password for security
    $college = $_POST["college"];
    $branch = $_POST["branch"];
    $specialization = $_POST["specialization"];
    $dob = $_POST["dob"];

    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Failed to signup. Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
