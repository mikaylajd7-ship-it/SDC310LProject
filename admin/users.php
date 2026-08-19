<?php

require_once "../config/database.php";

if (empty($_SESSION["user"]["is_admin"])) {

    header("Location: ../login.php");

    exit;
}

/* DELETE USER */
if (isset($_GET["delete"])) {

    $id = (int)$_GET["delete"];

    if (
        $id !==
        (int)$_SESSION["user"]["id"]
    ) {

        $stmt = $pdo->prepare(
            "DELETE FROM users WHERE id = ?"
        );

        $stmt->execute([$id]);
    }

    header("Location: users.php");

    exit;
}

/* READ USERS */
$users =
    $pdo->query(
        "SELECT
            id,
            name,
            email,
            is_admin,
            created_at
         FROM users
         ORDER BY id DESC"
    )->fetchAll();

$pageTitle = "Admin Users";

require "../includes/header.php";

?>

<h1>User Management</h1>

<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Admin</th>
    <th>Created</th>
    <th>Action</th>
</tr>

<?php foreach ($users as $user): ?>

<tr>

<td>
    <?= (int)$user["id"] ?>
</td>

<td>
    <?= htmlspecialchars(
        $user["name"]
    ) ?>
</td>

<td>
    <?= htmlspecialchars(
        $user["email"]
    ) ?>
</td>

<td>
    <?= $user["is_admin"]
        ? "Yes"
        : "No" ?>
</td>

<td>
    <?= htmlspecialchars(
        $user["created_at"]
    ) ?>
</td>

<td>

<?php if (
    (int)$user["id"]
    !==
    (int)$_SESSION["user"]["id"]
): ?>

<a
    href="?delete=<?= (int)$user["id"] ?>"
    onclick="return confirm('Delete this user?')"
>
    Delete
</a>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</table>

<?php require "../includes/footer.php"; ?>
