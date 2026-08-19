<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["remove"])) {

        $id = (int)$_POST["remove"];

        unset($_SESSION["cart"][$id]);

    } elseif (
        isset($_POST["update"])
        && isset($_POST["qty"])
    ) {

        foreach ($_POST["qty"] as $id => $quantity) {

            $id = (int)$id;
            $quantity = max(0, (int)$quantity);

            if ($quantity === 0) {

                unset($_SESSION["cart"][$id]);

            } else {

                $_SESSION["cart"][$id] = $quantity;
            }
        }
    }
}

$cart = $_SESSION["cart"] ?? [];

$items = [];

$total = 0;

if (!empty($cart)) {

    $ids = array_keys($cart);

    $placeholders =
        implode(
            ",",
            array_fill(0, count($ids), "?")
        );

    $stmt = $pdo->prepare(
        "SELECT *
         FROM products
         WHERE id IN ($placeholders)"
    );

    $stmt->execute($ids);

    foreach ($stmt->fetchAll() as $product) {

        $quantity =
            (int)$cart[$product["id"]];

        $lineTotal =
            $quantity *
            (float)$product["price"];

        $total += $lineTotal;

        $items[] = [
            $product,
            $quantity,
            $lineTotal
        ];
    }
}

$pageTitle = "Shopping Cart";

require "includes/header.php";

?>

<h1>Shopping Cart</h1>

<?php if (empty($items)): ?>

    <p>
        Your cart is empty.
    </p>

    <a
        href="products.php"
        class="button"
    >
        Continue Shopping
    </a>

<?php else: ?>

<form method="post">

<table>

<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Action</th>
</tr>

<?php foreach ($items as [$product, $quantity, $lineTotal]): ?>

<tr>

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

        <input
            type="number"
            name="qty[<?= (int)$product["id"] ?>]"
            value="<?= $quantity ?>"
            min="0"
        >

    </td>

    <td>
        $<?= number_format($lineTotal, 2) ?>
    </td>

    <td>

        <button
            name="remove"
            value="<?= (int)$product["id"] ?>"
        >
            Remove
        </button>

    </td>

</tr>

<?php endforeach; ?>

</table>

<p class="total">
    Order Total:
    $<?= number_format($total, 2) ?>
</p>

<button
    class="button secondary"
    name="update"
    value="1"
>
    Update Cart
</button>

<a
    href="checkout.php"
    class="button"
>
    Checkout
</a>

</form>

<?php endif; ?>

<?php require "includes/footer.php"; ?>
