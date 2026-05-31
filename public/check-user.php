<?php
// Direct check without Laravel
$conn = mysqli_connect('127.0.0.1', 'root', '', 'sealviking');
if (!$conn) die('Connection failed');
$result = mysqli_query($conn, "SELECT * FROM users WHERE email = 'gm01@sealonline.test'");
$user = mysqli_fetch_assoc($result);
echo $user ? 'User exists: ' . json_encode($user) : 'User NOT found';
mysqli_close($conn);
?>
