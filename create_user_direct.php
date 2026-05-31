<?php
$host = '127.0.0.1';
$db = 'sealviking';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Pakai MD5 (Format standar Seal Online)
$password_hashed = md5('botakkontol');

// Jika ternyata gamenya pakai Plaintext (Teks biasa tanpa enkripsi), aktifkan kode di bawah ini dan hapus kode MD5 di atas:
// $password_hashed = 'botakkontol';

$sql = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES ('gm01', 'gm01@sealonline.test', '$password_hashed', NOW(), NOW())";

if ($conn->query($sql) === TRUE) {
    echo "User created successfully!\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

$conn->close();
?>
