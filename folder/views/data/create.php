<?php require_once 'views/header.php'; ?>

<h1>Create New Record</h1>
<?php if (isset($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>
<form method="post" action="/data/create">
    <label for="data_field">Data Field:</label>
    <input type="text" name="data_field" id="data_field" required>
    <br>
    <button type="submit">Create</button>
</form>

<?php require_once 'views/footer.php'; ?>