<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Add Content";
require 'templates/header.php';
require 'inc/db.php';
require 'classes/Content.php';
require 'inc/upload.php';

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Grab and sanitize the title and body inputs
    $title = trim(isset($_POST['title']) ? $_POST['title'] : '');
    $body = trim(isset($_POST['body']) ? $_POST['body'] : '');

    // Handle image upload (if any)
    $upload = uploadImage('image');

    // Basic validation: title and body are required
    if (!$title || !$body) {
        $error = "Title and description are required.";
    }
    // Check if upload returned an error
    elseif (isset($upload['error'])) {
        $error = $upload['error'];
    }
    else {
        // If all good, save content to the database
        $conn = getConnection();
        $content = new Content($conn);
        $content->add($_SESSION['user_id'], $title, $body, $upload['path']);

        // Redirect to dashboard after successful save
        header("Location: dashboard.php");
        exit;
    }
}
?>
<link rel="stylesheet" href="/css/style.css" />
<!-- Content submission form -->
<form method="POST" enctype="multipart/form-data" novalidate>
    <?php if ($error): ?>
        <!-- Show error message if validation fails -->
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <input type="text" name="title" placeholder="Title" required value="<?= htmlspecialchars(isset($_POST['title']) ? $_POST['title'] : '') ?>"><br>

    <textarea name="body" placeholder="Description" required><?= htmlspecialchars(isset($_POST['body']) ? $_POST['body'] : '') ?></textarea><br>

    <!-- Image upload input -->
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif"><br>

    <button type="submit">Add Content</button>
</form>

<?php require 'templates/footer.php'; ?>
