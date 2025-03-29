<?php
// Database configuration
$host = 'localhost';
$user = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password
$database = 'school_management';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Function to sanitize input data
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Function to redirect with message
function redirect($url, $message = '', $type = 'success') {
    if (!empty($message)) {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }
    
    // Handle both absolute and relative URLs
    if (strpos($url, '/') === 0) {
        // If URL starts with /, ensure it has the correct base path
        if (strpos($url, '/school/') !== 0) {
            $url = '/school' . $url;
        }
    } else if (strpos($url, 'http') !== 0) {
        // If it's not an absolute URL and doesn't start with /, add the base path
        $url = '/school/' . $url;
    }
    
    header("Location: $url");
    exit();
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>