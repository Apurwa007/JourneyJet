<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "journeyjet";
$port = "3308";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header("Location: index.html"); // Redirect to the homepage
            exit();
        } else {
            echo '<script>alert("Invalid Username or Password!"); window.location.href="sign_in.html"</script>';
        }
    } else {
        echo '<script>alert("Invalid Username or Password!"); window.location.href="sign_in.html"</script>';
    }

    $stmt->close();
}

$conn->close();
?>
