<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?= base_url('/assets/styles.css') ?>">
</head>

<body>

    <div class="container">
        <section id="message-container">

        </section>
        <h1>Register User</h1>
        <form id="register-form">
            <input type="text" name="name" id="name" placeholder="Name" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" minlength="6" required>
            <button type="submit" class="submit-button">Register</button>

        </form>
        <div>
            <a href="<?= site_url('/') ?>">Already have an account? Login</a>
        </div>
    </div>

    <?php require_once(APPPATH . "/Views/pages/register/scripts.php") ?>
</body>

</html>