<?php
require_once "classes/User.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = new User();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $decryptedKey = $user->login($username, $password);
    if ($decryptedKey) {
        $_SESSION['username'] = $username;
        $_SESSION['aes_key'] = $decryptedKey; // Store decrypted key in session
        echo "Login successful. Key decrypted.";
        // header("Location: dashboard.php"); // Optional: redirect to dashboard
    } else {
        echo "Login failed.";
    }
}
?>

<form method="post">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
