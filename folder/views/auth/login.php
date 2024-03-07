<?php require_once 'views/header.php'; ?>

<h1>Login</h1>
<?php if (isset($error)) : ?>
    <p><?= $error ?></p>
<?php elseif (isset($success)) : ?>
    <p><?= $success ?></p>
<?php endif; ?>
<form method="post" action="/login">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="/register">Register</a></p>
<p><a href="/reset">Forgot Password?</a></p>

<?php require_once 'views/footer.php'; ?>