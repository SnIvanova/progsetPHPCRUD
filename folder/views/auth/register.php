<?php require_once 'views/header.php'; ?>

<h1>Register</h1>
<?php if (isset($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>
<form method="post" action="/register">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">Register</button>
</form>

<?php require_once 'views/footer.php'; ?>