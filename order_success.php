<?php

require_once "config/database.php";

$id =
    filter_input(
        INPUT_GET,
        "id",
        FILTER_VALIDATE_INT
    );

$pageTitle = "Order Complete";

require "includes/header.php";

?>

<h1>Order Complete</h1>

<p>
    Thank you for your purchase.
</p>

<p>
    Your order number is
    <strong>
        #<?= (int)$id ?>
    </strong>.
</p>

<a
    href="products.php"
    class="button"
>
    Continue Shopping
</a>

<?php require "includes/footer.php"; ?>
