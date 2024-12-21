<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <?php require_once(APPPATH . "/Views/templates/header.php") ?>

    <section id="message-container">

    </section>

    <fieldset>
        <legend>Update User</legend>
        <form id="update-form">
            <input type="text" name="name" id="name" value="<?= $name ?>" required>
            <input type="email" name="email" id="email" value="<?= $email ?>" required>
            <input type="password" name="password" id="password" minlength="6" required>
            <button type="submit">Edit User</button>
        </form>
    </fieldset>

    <button id="delete-button">Delete Account</button>

    <?php require_once(APPPATH . "/Views/pages/users/scripts.php") ?>

</body>

</html>