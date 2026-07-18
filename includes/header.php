<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' - Octave' : 'Octave'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<nav class="navbar navbar-expand-lg navbar-dark site-nav px-3">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index.php">
            <img src="assets/images/logo.png" alt="Octave logo" width="36" height="36">
            Octave
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo in_array($currentPage, ['store.php', 'product.php'], true) ? 'active' : ''; ?>" href="store.php">Shop</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $currentPage === 'about.php' ? 'active' : ''; ?>" href="about.php">About</a></li>
            </ul>
            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link nav-cart <?php echo $currentPage === 'cart.php' ? 'active' : ''; ?>" href="cart.php">
                        🛒 Cart
                        <span class="badge"><?php echo getCartCount(); ?></span>
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <span class="nav-link nav-greeting">Hi, <?php echo e($_SESSION['full_name']); ?></span>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4">
