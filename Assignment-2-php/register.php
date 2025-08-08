<?php
session_start();
$pageTitle = "Register";

require 'templates/header.php';
require 'inc/db.php';
require 'classes/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Grab and sanitize user input
    $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';

    // Basic validation: check email format and password confirmation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Connect to the database and create a new user instance
        $conn = getConnection();
        $user = new User($conn);

        // Attempt to register the user without an image (passing null)
        if ($user->register($username, $email, $password, null)) {
            // On success, redirect to login page
            header("Location: login.php");
            exit;
        } else {
            // If username or email already exists, show error
            $error = "Username or email already exists.";
        }
    }
}
?>
<link rel="stylesheet" href="css/style.css" />
<!-- Registration form -->
<form method="POST" novalidate>
    <!-- Display errors if any -->
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Username input -->
    <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars(isset($_POST['username']) ? $_POST['username'] : '') ?>"><br>

    <!-- Email input -->
    <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : '') ?>"><br>

    <!-- Password input -->
    <input type="password" name="password" placeholder="Password" required><br>

    <!-- Confirm password input -->
    <input type="password" name="confirm" placeholder="Confirm Password" required><br>

    <!-- Removed the image upload input -->

    <!-- Submit button -->
    <button type="submit">Register</button>
</form>

<?php require 'templates/footer.php'; ?>
