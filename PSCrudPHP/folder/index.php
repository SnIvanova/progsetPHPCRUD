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

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['id'])) {
    $firstname = $_REQUEST['firstname'] ?? '';
    $lastname = $_REQUEST['lastname'] ?? '';
    $email = $_REQUEST['email'] ?? '';
    $password = $_REQUEST['password'] ?? '';
    $admin = $_REQUEST['admin'] ?? '';
    $id = intval($_REQUEST['id']);

    $result = $userDTO->updateUser([
        'id' => $id,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $password,
        'admin' => $admin,
    ]);

    if ($result) {
        header('Location: index.php?message=User Updated Successfully');
        exit;
    } else {
        echo "An error occurred during the update.";
    }
} elseif (!isset($_REQUEST['action']) && isset($_REQUEST['firstname'])) {
   
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
  header('Location: controller.php?email='.$_COOKIE["useremail"].'&password='.$_COOKIE["userpassword"]);
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
                    <table class="table table-hover align-middle table-striped m-3 justify-content-center">
                        <thead class="bg-primary table-light">
                            <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Surname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Admin</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody >
                    <?php if ($res): ?>
                        <?php foreach ($res as $record): ?>
                            <tr>
                                <td><?= htmlspecialchars($record["id"]) ?></td>
                                <td><?= htmlspecialchars($record["firstname"]) ?></td>
                                <td><?= htmlspecialchars($record["lastname"]) ?></td>
                                <td><?= htmlspecialchars($record["email"]) ?></td>
                                <td class="text-center">
                                    <?= $record["admin"] ? '<span class="badge bg-success rounded-pill">Yes</span>' : '<span class="badge bg-secondary rounded-pill">No</span>' ?>
                                </td>
                                <td class="d-flex flex-column justify-content-center align-items-center">
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


                                    <form action="index.php" method="post" 
                                    onsubmit="return confirm('Are you sure you want to delete this user?');" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($record["id"]) ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm mx-1" title="Delete User" aria-label="Delete User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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


    <!-- modify -->
<div class="modal fade" id="modificaUtente" tabindex="-1" aria-labelledby="modificaUtenteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modificaUtenteLabel">Modify User</h1>
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
                        <label for="lastname" class="form-label">Lastname</label>
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
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
       

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
$(document).ready(function() {
    $('#modificaUtente').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); 
        var id = button.data('id');
        var firstname = button.data('firstname');
        var lastname = button.data('lastname');
        var email = button.data('email');
      
        var admin = button.data('admin');
        
        var modal = $(this);
        modal.find('.modal-body #userId').val(id);
        modal.find('.modal-body #firstname').val(firstname);
        modal.find('.modal-body #lastname').val(lastname);
        modal.find('.modal-body #email').val(email);
        modal.find('.modal-body #password').val(password); 
        modal.find('.modal-body #admin').val(admin);
    });
});
</script>

</body>

</html>