<?php
$pageTitle = "Home";
require 'templates/header.php';
?>
<link rel="stylesheet" href="/css/style.css" />
<section class="hero">
    <h2>Welcome to my website. This website is secure and user-friendly platform that allows users to register, log in, and manage their own content. With features like content creation, editing, deletion, and image uploads. </h2>
    <?php if (isset($_SESSION['username'])): ?>
        <p>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        <a href="dashboard.php" class="btn">Go to Dashboard</a>
    <?php else: ?>
        <p>Please <a href="login.php">login</a> or <a href="register.php">register</a>.</p>
    <?php endif; ?>
</section>
<?php
require 'templates/footer.php';
?>
