<?php

require_once "config/database.php";

$pageTitle = "Products";

require "includes/header.php";

$stmt = $pdo->query(
    "SELECT *
     FROM products
     WHERE active = 1
     ORDER BY name"
);

$products = $stmt->fetchAll();

?>

<h1>Products</h1>

<div class="product-grid">

<?php foreach ($products as $product): ?>

    <article class="card">

        <div class="product-image">
            <?= htmlspecialchars($product["name"][0] ?? "P") ?>
        </div>

        <h2>
            <?= htmlspecialchars($product["name"]) ?>
        </h2>

        <p>
            <?= htmlspecialchars($product["description"]) ?>
        </p>

        <p class="price">
            $<?= number_format((float)$product["price"], 2) ?>
        </p>

        <p>
            Stock: <?= (int)$product["stock"] ?>
        </p>

        <a
            href="product.php?id=<?= (int)$product["id"] ?>"
            class="button"
        >
            View Product
        </a>

    </article>

<?php endforeach; ?>

</div>

<?php require "includes/footer.php"; ?>
