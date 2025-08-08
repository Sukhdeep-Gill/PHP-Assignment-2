<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Dashboard";

// Include header and necessary classes/files
require 'templates/header.php';
require 'inc/db.php';
require 'classes/Content.php';

// Create database connection and fetch all content
$conn = getConnection();
$content = new Content($conn);
$items = $content->getAll();
?>
<link rel="stylesheet" href="/css/style.css" />

<h2>Your Content</h2>
<!-- Link to add new content -->
<a href="add_content.php" class="btn">Add New Content</a>

<?php if (empty($items)): ?>
    <!-- Show message if no content found -->
    <p>No content found.</p>
<?php else: ?>
    <!-- Loop through each content item and display -->
    <?php foreach ($items as $item): ?>
        <div class="content-item">
            <!-- Content title -->
            <h3><?= htmlspecialchars($item['title']) ?></h3>

            <!-- Content body with line breaks -->
            <p><?= nl2br(htmlspecialchars($item['body'])) ?></p>

            <?php if (!empty($item['image'])): ?>
                <!-- Display image if exists -->
                <img src="<?= htmlspecialchars($item['image']) ?>"
                     alt="<?= htmlspecialchars($item['title']) ?>"
                     width="150" />
            <?php endif; ?>

            <?php if ($item['user_id'] == $_SESSION['user_id']): ?>
                <!-- Show edit/delete options only for content owner -->
                <p>
                    <a href="edit_content.php?id=<?= $item['id'] ?>">Edit</a> |
                    <a href="delete_content.php?id=<?= $item['id'] ?>" onclick="return confirm('Delete this content?')">Delete</a>
                </p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
// Include footer
require 'templates/footer.php';
?>
