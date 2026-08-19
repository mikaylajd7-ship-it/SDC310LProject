<?php

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name =
        trim($_POST["name"] ?? "");

    $email =
        trim($_POST["email"] ?? "");

    $password =
        $_POST["password"] ?? "";

    if (
        $name === ""
        || !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
        || strlen($password) < 6
    ) {

        $error =
            "Enter a name, valid email, and password of at least 6 characters.";

    } else {

        try {

            $stmt = $pdo->prepare(
                "INSERT INTO users
                (name, email, password)
                VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $name,
                $email,
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                )
            ]);

            header(
                "Location: login.php?registered=1"
            );

            exit;

        } catch (PDOException $e) {

            $error =
                "That email address may already be registered.";
        }
    }
}

$pageTitle = "Register";

require "includes/header.php";

?>

<h1>Create Account</h1>

<?php if ($error): ?>

<p class="error">
    <?= htmlspecialchars($error) ?>
</p>

<?php endif; ?>

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
        minlength="6"
        required
    >
</label>

<button
    class="button"
    type="submit"
>
    Register
</button>

</form>

<?php require "includes/footer.php"; ?>
