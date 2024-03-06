<?php require_once 'views/header.php'; ?>

<h1>Update Data Record</h1>
<?php if (isset($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>
<form method="post" action="/data/update">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    <label for="data_field">Data Field:</label>
    <input type="text" name="data_field" id="data_field" value="<?= $data['data_field'] ?>" required>
    <br>
    <button type="submit">Update</button>
</form>

<?php require_once 'views/footer.php'; ?>