<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="<?= base_url('/assets/styles.css') ?>">
</head>

<body>
    <?php require_once(APPPATH . "/Views/templates/header.php") ?>

    <div class="container">
        <section id="message-container">

        </section>

        <h1>Categories</h1>

        <form id="create-form">
            <input type="text" name="name" id="name" placeholder="Name" required>
            <button type="submit" class="submit-button">Create Category</button>
        </form>

        <section id="categories-container">

        </section>
    </div>

    <?php require_once(APPPATH . "/Views/pages/categories/scripts.php") ?>
</body>

</html>