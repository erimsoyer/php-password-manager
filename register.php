<?php
require_once "classes/User.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = new User();
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->register($username, $password)) {
        echo "User registered successfully.";
    } else {
        echo "Registration failed.";
    }
}
?>

<form method="post">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Register</button>
</form>
