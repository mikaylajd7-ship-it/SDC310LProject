<?php

require_once "config/database.php";

$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id) {
    header("Location: products.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT *
     FROM products
     WHERE id = ?
     AND active = 1"
);

$stmt->execute([$id]);

$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    die("Product not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $quantity = max(
        1,
        (int)($_POST["quantity"] ?? 1)
    );

    if ($quantity <= (int)$product["stock"]) {

        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        $_SESSION["cart"][$id] =
            ($_SESSION["cart"][$id] ?? 0)
            + $quantity;

        header("Location: cart.php");
        exit;

    } else {

        $error =
            "The requested quantity is not available.";
    }
}

$pageTitle = $product["name"];

require "includes/header.php";

?>

<h1>
    <?= htmlspecialchars($product["name"]) ?>
</h1>

<div class="detail">

    <div class="product-image large">

        <?= htmlspecialchars(
            $product["name"][0] ?? "P"
        ) ?>

    </div>

    <div>

        <p>
            <?= htmlspecialchars(
                $product["description"]
            ) ?>
        </p>

        <p class="price">
            $<?= number_format(
                (float)$product["price"],
                2
            ) ?>
        </p>

        <p>
            Available:
            <?= (int)$product["stock"] ?>
        </p>

        <?php if ($error): ?>

            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>

        <?php if ((int)$product["stock"] > 0): ?>

            <form method="post">

                <label>
                    Quantity

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        max="<?= (int)$product["stock"] ?>"
                        value="1"
                    >

                </label>

                <button
                    class="button"
                    type="submit"
                >
                    Add to Cart
                </button>

            </form>

        <?php else: ?>

            <p class="error">
                Out of stock.
            </p>

        <?php endif; ?>

    </div>

</div>

<?php require "includes/footer.php"; ?>
