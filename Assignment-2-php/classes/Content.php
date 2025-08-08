<?php
// Content management class
class Content {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create content
    public function add($userId, $title, $body, $image) {
        $stmt = $this->conn->prepare("INSERT INTO app_contents (user_id, title, body, image) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $title, $body, $image]);
    }

    // Read all content
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM app_contents ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Read content by ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM app_contents WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Update content
    public function update($id, $title, $body, $image) {
        $stmt = $this->conn->prepare("UPDATE app_contents SET title = ?, body = ?, image = ? WHERE id = ?");
        return $stmt->execute([$title, $body, $image, $id]);
    }

    // Delete content
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM app_contents WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
