<?php

require_once "config/database.php";

$pageTitle = "Home";

require "includes/header.php";

$stmt = $pdo->query(
    "SELECT *
     FROM products
     WHERE active = 1
     ORDER BY id DESC
     LIMIT 6"
);

$products = $stmt->fetchAll();

?>

<section class="hero">

    <h1>
        Welcome to Mikayla Shop
    </h1>

    <p>
        A simple PHP and MySQL online store
        demonstrating database-driven web application
        development.
    </p>

    <a href="products.php" class="button">
        Shop Products
    </a>

</section>

<h2>Featured Products</h2>

<div class="product-grid">

<?php foreach ($products as $product): ?>

    <article class="card">

        <div class="product-image">
            <?= htmlspecialchars($product["name"][0] ?? "P") ?>
        </div>

        <h3>
            <?= htmlspecialchars($product["name"]) ?>
        </h3>

        <p>
            <?= htmlspecialchars($product["description"]) ?>
        </p>

        <strong>
            $<?= number_format((float)$product["price"], 2) ?>
        </strong>

        <br>

        <a
            class="button secondary"
            href="product.php?id=<?= (int)$product["id"] ?>"
        >
            View Product
        </a>

    </article>

<?php endforeach; ?>

</div>

<?php require "includes/footer.php"; ?>
