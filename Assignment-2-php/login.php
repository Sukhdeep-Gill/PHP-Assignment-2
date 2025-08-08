<?php
session_start();
$pageTitle = "Login";

require 'templates/header.php';
require 'inc/db.php';
require 'classes/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $conn = getConnection();

    // Create a User object to handle login logic
    $user = new User($conn);

    // Try to log in with the provided username and password
    $login = $user->login($_POST['username'], $_POST['password']);

    if ($login) {
        // If login is successful, save user info in session
        $_SESSION['user_id'] = $login['id'];
        $_SESSION['username'] = $login['username'];

        // Redirect the user to the dashboard page
        header("Location: dashboard.php");
        exit;
    } else {
        // If login fails, show an error message
        $error = "Invalid username or password.";
    }
}
?>
<link rel="stylesheet" href="/css/style.css" />
<!-- Login form -->
<form method="POST" novalidate>
    <!-- Show error message if there is one -->
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Username input field -->
    <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars(isset($_POST['username']) ? $_POST['username'] : '') ?>"><br>

    <!-- Password input field -->
    <input type="password" name="password" placeholder="Password" required><br>

    <!-- Submit button -->
    <button type="submit">Login</button>
</form>

<?php require 'templates/footer.php'; ?>

