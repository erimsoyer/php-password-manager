<?php
require_once "DB.php";

class User {
    private $db;

    public function __construct() {
        $this->db = (new DB())->getConnection();
    }

    public function register($username, $password) {
        // 1. Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 2. Generate encryption key (random)
        $encryptionKey = bin2hex(random_bytes(16)); // 32 hex chars = 128 bits

        // 3. Encrypt key using user's plain password
        $aesEncryptedKey = openssl_encrypt(
            $encryptionKey,
            "AES-128-CTR",
            $password,
            0,
            "1234567891011121" // 16-byte IV – should be random & stored securely
        );

        // 4. Store in DB
        $sql = "INSERT INTO users (username, password_hash, aes_key_encrypted) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$username, $passwordHash, $aesEncryptedKey]);
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Decrypt the AES key using user's plain password
            $decryptedKey = openssl_decrypt(
                $user['aes_key_encrypted'],
                "AES-128-CTR",
                $password,
                0,
                "1234567891011121" // Same IV used during encryption
            );
            return $decryptedKey;
        }

        return false;
    }
}
