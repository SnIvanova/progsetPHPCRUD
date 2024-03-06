<!DOCTYPE html>
<html>
<head>
    <title>Restricted Access Admin Panel</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/data">Home</a></li>
                <?php if (isset($_SESSION['user'])) : ?>
                    <li><a href="/logout">Logout</a></li>
                <?php else : ?>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>