<?php

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email =
        trim($_POST["email"] ?? "");

    $password =
        $_POST["password"] ?? "";

    $stmt = $pdo->prepare(
        "SELECT
            id,
            name,
            email,
            password,
            is_admin
         FROM users
         WHERE email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if (
        $user
        && password_verify(
            $password,
            $user["password"]
        )
    ) {

        unset($user["password"]);

        $_SESSION["user"] = $user;

        $redirect =
            $_SESSION[
                "redirect_after_login"
            ] ?? "index.php";

        unset(
            $_SESSION[
                "redirect_after_login"
            ]
        );

        header(
            "Location: " . $redirect
        );

        exit;

    } else {

        $error =
            "Invalid email or password.";
    }
}

$pageTitle = "Login";

require "includes/header.php";

?>

<h1>Login</h1>

<?php if (isset($_GET["registered"])): ?>

<p class="success">
    Account created successfully.
    You can now log in.
</p>

<?php endif; ?>

<?php if ($error): ?>

<p class="error">
    <?= htmlspecialchars($error) ?>
</p>

<?php endif; ?>

<form method="post" class="form">

<label>
    Email

    <input
        type="email"
        name="email"
        required
    >
</label>

<label>
    Password

    <input
        type="password"
        name="password"
        required
    >
</label>

<button
    class="button"
    type="submit"
>
    Login
</button>

</form>

<?php require "includes/footer.php"; ?>
