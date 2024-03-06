<?php
session_start();


$timeout = 1800; // 30 minutes in seconds


if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout) {
    session_unset();
    session_destroy();
    header('Location: /login');
    exit;
}


$_SESSION['LAST_ACTIVITY'] = time();


require_once 'config/database.php';
require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/DataController.php';


$authController = new AuthController();
$dataController = new DataController();


$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);


if ($path === '/login') {
    $authController->loginAction();
} elseif ($path === '/logout') {
    $authController->logoutAction();
} elseif ($path === '/register') {
    $authController->registerAction();
} elseif ($path === '/reset') {
    $authController->resetAction();
} elseif ($path === '/error') {
    $viewFile = 'views/error.php';
    require_once 'views/layout.php';
}


if (isset($_SESSION['user'])) {
    if ($path === '/data') {
        $dataController->indexAction();
    } elseif ($path === '/data/create') {
        $dataController->createAction();
    } elseif ($path === '/data/update') {
        $dataController->updateAction();
    } elseif ($path === '/data/delete') {
        $dataController->deleteAction();
    }
} else {
    
    header('Location: /login');
    exit;
}
?>
