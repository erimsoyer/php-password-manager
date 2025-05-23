<?php
require_once "DB.php";

class PasswordManager {
    private $db;

    public function __construct() {
        $this->db = (new DB())->getConnection();
    }

    public function savePassword($username, $service, $plainPassword, $aesKey) {
        // Find user ID
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) return false;

        // Encrypt the password with user's AES key
        $encrypted = openssl_encrypt(
            $plainPassword,
            "AES-128-CTR",
            $aesKey,
            0,
            "1234567891011121"
        );

        // Save to DB
        $stmt = $this->db->prepare("INSERT INTO passwords (user_id, service_name, encrypted_password) VALUES (?, ?, ?)");
        return $stmt->execute([$user['id'], $service, $encrypted]);
    }
}
