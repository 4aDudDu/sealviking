<?php
$host = '127.0.0.1';
$db = 'sealviking';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$password_hashed = password_hash('botakkontol', PASSWORD_BCRYPT);
$sql = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES ('gm01', 'gm01@sealonline.test', '$password_hashed', NOW(), NOW())";

if ($conn->query($sql) === TRUE) {
    echo "User created successfully!\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

$conn->close();
?>
