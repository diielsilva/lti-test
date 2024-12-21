<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
</head>

<body>
    <?php require_once(APPPATH . "/Views/templates/header.php") ?>

    <section id="message-container">

    </section>

    <fieldset>
        <legend>Create Category</legend>
        <form id="create-form">
            <input type="text" name="name" id="name" placeholder="Name" required>
            <button type="submit">Create Category</button>
        </form>
    </fieldset>

    <section id="categories-container">

    </section>

    <?php require_once(APPPATH . "/Views/pages/categories/scripts.php") ?>
</body>

</html>