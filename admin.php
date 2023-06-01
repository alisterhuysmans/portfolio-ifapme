<?php
session_start();
require_once 'config.php';

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Logout user
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <form method="post" action="">
        <input type="submit" name="logout" value="Logout">
    </form>
    <!-- Your HTML code for the admin panel goes here -->
</body>
</html>
