<?php
require_once "DB.php";

class User {
    private $db;

    public function __construct() {
        $this->db = (new DB())->getConnection();
    }

    public function register($username, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $encryptionKey = bin2hex(random_bytes(16));

        $aesEncryptedKey = openssl_encrypt(
            $encryptionKey,
            "AES-128-CTR",
            $password,
            0,
            "1234567891011121"
        );

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
            $decryptedKey = openssl_decrypt(
                $user['aes_key_encrypted'],
                "AES-128-CTR",
                $password,
                0,
                "1234567891011121"
            );
            return $decryptedKey;
        }

        return false;
    }

    public function changePassword($username, $oldPassword, $newPassword) {
        // Get user
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($oldPassword, $user['password_hash'])) {
            return false; // Old password incorrect
        }

        // Decrypt current AES key using old password
        $aesKey = openssl_decrypt(
            $user['aes_key_encrypted'],
            "AES-128-CTR",
            $oldPassword,
            0,
            "1234567891011121"
        );

        if (!$aesKey) {
            return false; // Decryption failed
        }

        // Hash new password
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Re-encrypt same AES key using new password
        $newEncryptedKey = openssl_encrypt(
            $aesKey,
            "AES-128-CTR",
            $newPassword,
            0,
            "1234567891011121"
        );

        // Update DB
        $sql = "UPDATE users SET password_hash = ?, aes_key_encrypted = ? WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newHash, $newEncryptedKey, $username]);
    }
}
