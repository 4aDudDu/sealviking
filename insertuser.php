<?php
$password_hashed = password_hash('botakkontol', PASSWORD_BCRYPT);
$conn = new mysqli('127.0.0.1', 'root', '', 'sealviking');
$sql = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES ('gm01', 'gm01@sealonline.test', '" . $conn->real_escape_string($password_hashed) . "', NOW(), NOW())";
$conn->query($sql) ? print("Success") : print("Error: " . $conn->error);
$conn->close();
