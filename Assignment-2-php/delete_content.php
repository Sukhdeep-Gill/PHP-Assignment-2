<?php
session_start();

// Redirect to login if user is not logged in or no content ID provided
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

require 'inc/db.php';
require 'classes/Content.php';

// Connect to the database
$conn = getConnection();

// Create Content object
$content = new Content($conn);

// Fetch content by ID
$item = $content->getById($_GET['id']);

// Check if content exists and belongs to logged-in user
if ($item && $item['user_id'] == $_SESSION['user_id']) {
    // Delete the content if authorized
    $content->delete($item['id']);
}

// Redirect back to dashboard after deletion
header("Location: dashboard.php");
exit;
