<?php
$conn = new mysqli("localhost", "root", "", "SocialMediaDB");
$message = "";

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Get stored hash for this username
    $sql = "SELECT password FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $storedHash = $row["password"];

        if (password_verify($password, $storedHash)) {
            $message = "Login Successful";
        } else {
            $message = "Login Unsuccessful";
        }
    } else {
        $message = "Login Unsuccessful";
    }

    $stmt->close();
}

$conn->close();
?>