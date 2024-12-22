<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="<?= base_url('/assets/styles.css') ?>">
</head>

<body>
    <?php require_once(APPPATH . "/Views/templates/header.php") ?>

    <div class="container">
        <section id="message-container">

        </section>

        <h1>User</h1>
        <form id="update-form">
            <input type="text" name="name" id="name" value="<?= $name ?>" placeholder="Name" required>
            <input type="email" name="email" id="email" value="<?= $email ?>" placeholder="Email" required>
            <input type="password" name="password" id="password" minlength="6" placeholder="Password" required>
            <button type="submit" class="submit-button">Update User</button>
        </form>

        <button id="delete-button" class="submit-button">Delete Account</button>
    </div>

    <?php require_once(APPPATH . "/Views/pages/users/scripts.php") ?>

</body>

</html>