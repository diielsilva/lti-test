<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <section id="message-container">

    </section>

    <form id="login-form">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" minlength="6" required>
        <button type="submit">Login</button>
        <a href="<?= site_url('/signup') ?>">Don't have an account? sign up</a>
    </form>

    <?php require_once(APPPATH . '/Views/pages/login/scripts.php') ?>

</body>

</html>