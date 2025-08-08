<?php
session_start();

// Redirect if user is not logged in or no content ID provided
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Edit Content";
require 'templates/header.php';
require 'inc/db.php';
require 'classes/Content.php';
require 'inc/upload.php';

// Establish database connection
$conn = getConnection();

// Create Content object to interact with content table
$content = new Content($conn);

// Fetch the content item by ID
$item = $content->getById($_GET['id']);

// Check if content exists and belongs to the logged-in user
if (!$item || $item['user_id'] != $_SESSION['user_id']) {
    die("Unauthorized access."); // Stop execution if unauthorized
}

$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form inputs
    $title = trim(isset($_POST['title']) ? $_POST['title'] : '');
    $body = trim(isset($_POST['body']) ? $_POST['body'] : '');
    $imagePath = $item['image']; // Keep current image path by default

    // Validate required fields
    if (!$title || !$body) {
        $error = "Title and description are required.";
    } else {
        // If a new image is uploaded, process the upload
        if (!empty($_FILES['image']['name'])) {
            $upload = uploadImage('image');
            if (isset($upload['error'])) {
                $error = $upload['error']; // Show upload error if any
            } else {
                $imagePath = $upload['path']; // Update image path to new upload
            }
        }

        // If no errors, update the content in the database
        if (!$error) {
            $content->update($item['id'], $title, $body, $imagePath);
            header("Location: dashboard.php"); // Redirect after update
            exit;
        }
    }
}
?>
<link rel="stylesheet" href="/css/style.css" />
<!-- Edit Content Form -->
<form method="POST" enctype="multipart/form-data" novalidate>
    <!-- Show error message if validation or upload failed -->
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Title input, pre-filled with current title -->
    <input type="text" name="title" required value="<?= htmlspecialchars($item['title']) ?>"><br>

    <!-- Body textarea, pre-filled with current content -->
    <textarea name="body" required><?= htmlspecialchars($item['body']) ?></textarea><br>

    <!-- Display current image if exists -->
    <?php if ($item['image']): ?>
        <img src="<?= htmlspecialchars($item['image']) ?>" alt="Current image" width="150"><br>
    <?php endif; ?>

    <!-- File input for uploading a new image -->
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif"><br>

    <!-- Submit button -->
    <button type="submit">Update Content</button>
</form>

<?php require 'templates/footer.php'; ?>
