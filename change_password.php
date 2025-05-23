<?php
require_once __DIR__ . "/classes/User.php";
session_start();

if (!isset($_SESSION['username'])) {
    die("Access denied. Please log in.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = new User();
    $success = $user->changePassword(
        $_SESSION['username'],
        $_POST['old_password'],
        $_POST['new_password']
    );

    if ($success) {
        echo "Password changed and AES key re-encrypted successfully.";
        session_destroy(); // Force logout after password change
    } else {
        echo "Password change failed. Check your old password.";
    }
}
?>

<form method="post">
    <label>Old Password: <input type="password" name="old_password" required></label><br>
    <label>New Password: <input type="password" name="new_password" required></label><br>
    <button type="submit">Change Password</button>
</form>
