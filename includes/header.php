<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;

if (isset($_SESSION["cart"])) {
    $cartCount = array_sum($_SESSION["cart"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($pageTitle ?? "Mikayla Shop") ?>
    </title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="site-header">

    <div class="container nav">

        <a href="index.php" class="brand">
            Mikayla Shop
        </a>

        <nav>

            <a href="index.php">Home</a>

            <a href="products.php">Products</a>

            <a href="cart.php">
                Cart (<?= $cartCount ?>)
            </a>

            <?php if (isset($_SESSION["user"])): ?>

                <a href="logout.php">Logout</a>

            <?php else: ?>

                <a href="login.php">Login</a>

                <a href="register.php">Register</a>

            <?php endif; ?>

            <?php if (!empty($_SESSION["user"]["is_admin"])): ?>

                <a href="admin/products.php">Admin</a>

            <?php endif; ?>

        </nav>

    </div>

</header>

<main class="container">
