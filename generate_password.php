<?php
require_once __DIR__ . "/classes/PasswordGenerator.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gen = new PasswordGenerator();
    $password = $gen->generate(
        $_POST['length'],
        $_POST['lowercase'],
        $_POST['uppercase'],
        $_POST['numbers'],
        $_POST['special']
    );
}
?>

<form method="post">
    <label>Total Length: <input type="number" name="length" required></label><br>
    <label>Lowercase: <input type="number" name="lowercase" required></label><br>
    <label>Uppercase: <input type="number" name="uppercase" required></label><br>
    <label>Numbers: <input type="number" name="numbers" required></label><br>
    <label>Special Chars: <input type="number" name="special" required></label><br>
    <button type="submit">Generate</button>
</form>

<?php if (!empty($password)): ?>
    <p><strong>Generated Password:</strong> <?= htmlspecialchars($password) ?></p>
<?php endif; ?>
