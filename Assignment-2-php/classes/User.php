<?php
// User management class
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register user
    public function register($username, $email, $password, $image) {
        // Check for existing user
        $stmt = $this->conn->prepare("SELECT id FROM app_users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) return false;

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO app_users (username, email, password, image) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hash, $image]);
    }

    // Login user
    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM app_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}

