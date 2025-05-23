<?php
require_once __DIR__ . "/classes/PasswordManager.php";
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['aes_key'])) {
    die("Access denied. Please log in.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $manager = new PasswordManager();
    $success = $manager->savePassword(
        $_SESSION['username'],
        $_POST['service_name'],
        $_POST['password'],
        $_SESSION['aes_key']
    );
    echo $success ? "Password saved successfully." : "Failed to save password.";
}
?>

<form method="post">
    <label>Service Name: <input type="text" name="service_name" required></label><br>
    <label>Password: <input type="text" name="password" required></label><br>
    <button type="submit">Save Password</button>
</form>
