<?php

require_once "config/database.php";

if (empty($_SESSION["cart"])) {

    header("Location: cart.php");

    exit;
}

if (!isset($_SESSION["user"])) {

    $_SESSION[
        "redirect_after_login"
    ] = "checkout.php";

    header("Location: login.php");

    exit;
}

$cart = $_SESSION["cart"];

$ids = array_keys($cart);

$placeholders =
    implode(
        ",",
        array_fill(
            0,
            count($ids),
            "?"
        )
    );

$stmt = $pdo->prepare(
    "SELECT *
     FROM products
     WHERE id IN ($placeholders)"
);

$stmt->execute($ids);

$products = $stmt->fetchAll();

$total = 0;

foreach ($products as $product) {

    $total +=
        (int)$cart[$product["id"]]
        * (float)$product["price"];
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $address =
        trim($_POST["address"] ?? "");

    if ($address === "") {

        $error =
            "Shipping address is required.";

    } else {

        try {

            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                "INSERT INTO orders
                (user_id, total, shipping_address, status)
                VALUES (?, ?, ?, 'Pending')"
            );

            $stmt->execute([
                $_SESSION["user"]["id"],
                $total,
                $address
            ]);

            $orderId =
                (int)$pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                "INSERT INTO order_items
                (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)"
            );

            $stockStmt = $pdo->prepare(
                "UPDATE products
                 SET stock = stock - ?
                 WHERE id = ?
                 AND stock >= ?"
            );

            foreach ($products as $product) {

                $quantity =
                    (int)$cart[$product["id"]];

                $stockStmt->execute([
                    $quantity,
                    $product["id"],
                    $quantity
                ]);

                if ($stockStmt->rowCount() !== 1) {

                    throw new RuntimeException(
                        "Not enough stock."
                    );
                }

                $itemStmt->execute([
                    $orderId,
                    $product["id"],
                    $quantity,
                    $product["price"]
                ]);
            }

            $pdo->commit();

            $_SESSION["cart"] = [];

            header(
                "Location: order_success.php?id="
                . $orderId
            );

            exit;

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error =
                "The order could not be completed.";
        }
    }
}

$pageTitle = "Checkout";

require "includes/header.php";

?>

<h1>Checkout</h1>

<?php if ($error): ?>

<p class="error">
    <?= htmlspecialchars($error) ?>
</p>

<?php endif; ?>

<p>
    Order Total:
    <strong>
        $<?= number_format($total, 2) ?>
    </strong>
</p>

<form method="post" class="form">

<label>
    Shipping Address

    <textarea
        name="address"
        required
    ></textarea>
</label>

<button
    type="submit"
    class="button"
>
    Place Order
</button>

</form>

<?php require "includes/footer.php"; ?>
