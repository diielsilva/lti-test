<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="<?= base_url("/categories") ?>">Categories</a></li>
            </ul>
            <ul>
                <li><a href="<?= base_url("/spents") ?>">Spents</a></li>
            </ul>
            <ul>
                <li><a href="<?= base_url("/reports") ?>">Report</a></li>
            </ul>
            <ul>
                <li>
                    <form action="<?= site_url('/logout') ?>" method="POST">
                        <button type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <form id="create-category-form">
        <input type="text" name="name" id="name" placeholder="Name" required>
        <button type="submit">Create Category</button>
    </form>

    <section id="categories-section">

    </section>

    <script>
        const form = document.querySelector("#create-category-form");

        const getCreateCategoryRequestBody = () => {
            const name = document.querySelector("#name").value;

            return JSON.stringify({
                name
            });
        }

        const createCategory = async () => {
            try {
                const category = getCreateCategoryRequestBody();
                const response = await fetch("<?= site_url('/categories') ?>", {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    body: category
                });
                const body = await response.json();

                if (body.errors.length > 0) {
                    alert(body.errors[0]);
                }

            } catch (exception) {
                console.log(exception);
            }
        }

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            createCategory();
        });
    </script>

</body>

</html>