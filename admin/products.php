<?php

require_once "../config/database.php";

if (empty($_SESSION["user"]["is_admin"])) {

    header("Location: ../login.php");

    exit;
}

/* DELETE */
if (isset($_GET["delete"])) {

    $id = (int)$_GET["delete"];

    $stmt = $pdo->prepare(
        "DELETE FROM products WHERE id = ?"
    );

    $stmt->execute([$id]);

    header("Location: products.php");

    exit;
}

/* CREATE */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name =
        trim($_POST["name"]);

    $description =
        trim($_POST["description"]);

    $price =
        (float)$_POST["price"];

    $stock =
        (int)$_POST["stock"];

    $active =
        isset($_POST["active"])
        ? 1
        : 0;

    $stmt = $pdo->prepare(
        "INSERT INTO products
        (name, description, price, stock, active)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->execute([
        $name,
        $description,
        $price,
        $stock,
        $active
    ]);

    header("Location: products.php");

    exit;
}

/* READ */
$products =
    $pdo->query(
        "SELECT *
         FROM products
         ORDER BY id DESC"
    )->fetchAll();

$pageTitle = "Admin Products";

require "../includes/header.php";

?>

<h1>Product Management</h1>

<h2>Add Product</h2>

<form method="post" class="form">

<label>
    Name

    <input
        type="text"
        name="name"
        required
    >
</label>

<label>
    Description

    <textarea
        name="description"
        required
    ></textarea>
</label>

<label>
    Price

    <input
        type="number"
        name="price"
        step="0.01"
        min="0"
        required
    >
</label>

<label>
    Stock

    <input
        type="number"
        name="stock"
        min="0"
        required
    >
</label>

<label>
    <input
        type="checkbox"
        name="active"
        checked
    >

    Active
</label>

<button
    class="button"
    type="submit"
>
    Add Product
</button>

</form>

<h2>Products</h2>

<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Active</th>
    <th>Action</th>
</tr>

<?php foreach ($products as $product): ?>

<tr>

<td>
    <?= (int)$product["id"] ?>
</td>

<td>
    <?= htmlspecialchars($product["name"]) ?>
</td>

<td>
    $<?= number_format(
        (float)$product["price"],
        2
    ) ?>
</td>

<td>
    <?= (int)$product["stock"] ?>
</td>

<td>
    <?= $product["active"]
        ? "Yes"
        : "No" ?>
</td>

<td>

<a
    href="?delete=<?= (int)$product["id"] ?>"
    onclick="return confirm('Delete this product?')"
>
    Delete
</a>

</td>

</tr>

<?php endforeach; ?>

</table>

<?php require "../includes/footer.php"; ?>
