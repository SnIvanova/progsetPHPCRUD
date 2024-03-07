<?php require_once 'views/header.php'; ?>

<h1>Reset Password</h1>
<?php if (isset($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>
<form method="post" action="/reset">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" id="new_password" required>
    <br>
    <button type="submit">Reset Password</button>
</form>

<?php require_once 'views/footer.php'; ?>