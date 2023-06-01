<?php
$host = 'your_host';
$db   = 'your_database';
$user = 'your_username';
$pass = 'your_password';

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
