<?php
// PDO -> Php Data Object
require_once('db.php');
require_once('user.php');
require_once('db_pdo.php');
$config = require_once('config.php');

use DB\DB_PDO as DB;

$PDOConn = DB::getInstance($config);
$conn = $PDOConn->getConnection(); 

$userDTO = new UserDTO($conn);

if (isset($_REQUEST['firstname'])) {
    $firstname = $_REQUEST['firstname'];
    $lastname = $_REQUEST['lastname'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $admin = $_REQUEST['admin'];

    $res = $userDTO->saveUser([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $password,
        'admin' => $admin
    ]);
}

if (isset($_REQUEST['id']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['submit'])) {
    $firstname = $_REQUEST['firstname'] ?? '';
    $lastname = $_REQUEST['lastname'] ?? '';
    $email = $_REQUEST['email'] ?? '';
    $password = $_REQUEST['password'] ?? '';
    $admin = $_REQUEST['admin'] ?? '';
    $id = intval($_REQUEST['id']);

    // Assuming you have an updateUser method to handle user updates.
    $result = $userDTO->updateUser([
        'id' => $id,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $password,
        'admin' => $admin,
    ]);

    if ($result) {
        // Update was successful, redirect or notify the user accordingly.
        header('Location: index.php?message=User Updated Successfully');
        exit;
    } else {
        // Handle error in update.
        echo "An error occurred during the update.";
    }
}

if (isset($_REQUEST['id']) && $_REQUEST['action'] == 'delete') {
    $id = intval($_REQUEST['id']);

    $res = $userDTO->deleteUser($id);

    header('Location: index.php');
    exit;
}

$res = $userDTO->getAll();

// logout
session_start();
if(!isset($_SESSION['userLogin']) && isset($_COOKIE["useremail"]) && isset($_COOKIE["userpassword"])) {
  header('Location: http://localhost/Progetto-settimana-4_back-end/controller.php?email='.$_COOKIE["useremail"].'&password='.$_COOKIE["userpassword"]);
} else if(!isset($_SESSION['userLogin'])) {
  //print_r($_SESSION['userLogin']);
  header('Location: login.php');
} else 
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link  rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">SnIvanova</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h1 class="card-title mb-4 fw-bold">Admin Dashboard</h1>
                    <p class="card-text mb-4">Welcome to the Admin Control Panel. Here, you can manage and protect sensitive data effectively.</p>
                    <a href="create.php" class="btn btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#creaUtente">
                        <i class="bi bi-plus-circle"></i> Add User
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 card-title mb-0">User Management</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-primary table-light">
                            <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Surname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Password</th>
                        <th scope="col">Admin</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res): ?>
                        <?php foreach ($res as $record): ?>
                            <tr>
                                <td><?= htmlspecialchars($record["id"]) ?></td>
                                <td><?= htmlspecialchars($record["firstname"]) ?></td>
                                <td><?= htmlspecialchars($record["lastname"]) ?></td>
                                <td><?= htmlspecialchars($record["email"]) ?></td>
                                <td class='td-password'
                                style="max-width: 100px; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($record["password"]) ?>
                                </td>
                                <td class="text-center">
                                    <?= $record["admin"] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                                </td>
                                <td class="d-flex justify-content-center">
                                    <button class="btn btn-outline-warning btn-sm mx-1 editUserButton" data-bs-toggle="modal" data-bs-target="#modificaUtente" 
                                            data-id="<?= $record["id"] ?>"
                                            data-firstname="<?= htmlspecialchars($record["firstname"]) ?>"
                                            data-lastname="<?= htmlspecialchars($record["lastname"]) ?>"
                                            data-email="<?= htmlspecialchars($record["email"]) ?>"
                                            data-password="<?= htmlspecialchars($record["password"]) ?>"
                                            data-admin="<?= $record["admin"] ?>"
                                            title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>


                                    <a href="index.php?action=delete&id=<?= $record["id"] ?>" 
                                    class="btn btn-outline-danger btn-sm mx-1"
                                    onclick="return confirm('Are you sure you want to delete this user?');" 
                                    title="Delete">
                                    <i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    
</main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


    <!-- modale per l'aggiunta di un utente -->
    <div class="modal fade" id="creaUtente" tabindex="-1" aria-labelledby="creaUtenteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="creaUtenteLabel">Aggiungi Utente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">Name</label>
                            <input name="firstname" type="text" class="form-control" id="firstname"
                                aria-describedby="firstname">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Surname</label>
                            <input name="lastname" type="text" class="form-control" id="lastname"
                                aria-describedby="lastname">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" id="password">
                        </div>
                        <div class="mb-3">
                            <label for="admin" class="form-label">Admin</label>
                            <input name="admin" type="number" class="form-control" id="admin" aria-describedby="admin"
                                min="0" max="1">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- modale per la modifica -->
    <div class="modal fade" id="modificaUtente" tabindex="-1" aria-labelledby="modificaUtenteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modificaUtenteLabel">Modifica Utente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                    <input type="hidden" name="id" id="userId" value=""> 
                    <input type="hidden" name="action" value="edit">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">Name</label>
                            <input name="firstname" type="text" class="form-control" id="firstname"
                                aria-describedby="firstname">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Surname</label>
                            <input name="lastname" type="text" class="form-control" id="lastname"
                                aria-describedby="lastname">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" id="password">
                        </div>
                        <div class="mb-3">
                            <label for="admin" class="form-label">Admin</label>
                            <input name="admin" type="number" class="form-control" id="admin" aria-describedby="admin"
                                min="0" max="1">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a href="index.php?action=edit&id=<?= $record["id"] ?>" type="submit"
                                class="btn btn-primary">Save changes</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>