<?php require_once 'views/header.php'; ?>

<h1>Sensitive Data</h1>
<a href="/data/create">Create New Record</a>

<?php if (isset($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Data Field</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['data_field'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= $row['updated_at'] ?></td>
                <td>
                    <a href="/data/update?id=<?= $row['id'] ?>">Update</a>
                    <a href="/data/delete?id=<?= $row['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'views/footer.php'; ?>