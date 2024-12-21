<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('/assets/styles.css') ?>">
</head>

<body>
    <section id="message-container">

    </section>

    <div class="container">
        <h1>Login</h1>
        <form id="login-form">
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" minlength="6" required>
            <button type="submit" class="submit-button">Login</button>
        </form>
        <div>
            <a href="<?= site_url('/register') ?>">Don't have an account? Register</a>
        </div>
    </div>

    <?php require_once(APPPATH . '/Views/pages/login/scripts.php') ?>

</body>

</html>