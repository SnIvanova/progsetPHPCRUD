<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container ">
        <div class="card login-card slideInRight  ">
            <div class="row no-gutter justify-content-end rounded-3">
                <div class="col-md-6 d-none d-md-flex bg-image order-md-1 rounded-3"></div>
                <div class="col-md-6  ">
                    <div class="card-body order-md-2">
                        <div class="brand-wrapper">
                            <img src="assets/img/logo.png" alt="logo" class="logo">
                        </div>
                        <p class="login-card-description">Sign into your account</p>
                        <?php
                            if (isset($_SESSION['error'])) {
                                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                                unset($_SESSION['error']);
                            }
                        ?>
                        <form action="controller.php" method="post">
                            <div class="form-floating mb-4">
                                <input type="email" name="email" id="floatingInput" class="form-control" placeholder="name@example.com" required autofocus value="some@example.com">
                                <label for="floatingInput">Email address</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" name="password" id="floatingPassword" class="form-control" placeholder="Password" required value="Pa$$w0rd!">
                                <label for="floatingPassword">Password</label>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check ">
                                        <input type="checkbox" name="remember" id="remember" class="form-check-input" checked>
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="#!" class="forgot-password-link">Forgot password?</a>
                                </div>
                            <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">
                        </form>
                        
                        <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
