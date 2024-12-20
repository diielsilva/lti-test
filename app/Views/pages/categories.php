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
        const categoriesSection = document.querySelector("#categories-section");

        const removeCategory = async (id) => {
            try {

                const response = await fetch("<?= site_url('/categories') ?>", {
                    headers: {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    method: "DELETE",
                    body: JSON.stringify({
                        id
                    })
                });

                if (response.status === 204) {
                    findCategoriesByOnlineUser();
                } else {
                    alert("Category not found!");
                }
            } catch (exception) {
                console.log(exception);
            }
        }

        const renderCategories = (categories) => {
            categoriesSection.innerHTML = '';

            if (categories.length === 0) {
                const message = document.createElement("p");

                message.innerText = "You don't have saved categories";

                categoriesSection.appendChild(message);
            } else {
                const categoriesList = document.createElement("ul");

                categories.forEach(category => {
                    const listItem = document.createElement("li");
                    const item = document.createElement("p");
                    const remove = document.createElement("button");

                    remove.innerText = "Remove Category";
                    remove.onclick = () => {
                        removeCategory(category.id)
                    };

                    item.innerText = `ID = ${category.id}, Name = ${category.name} `;

                    item.appendChild(remove);

                    listItem.appendChild(item);

                    categoriesList.appendChild(listItem);
                });

                categoriesSection.appendChild(categoriesList);
            }
        }

        const findCategoriesByOnlineUser = async () => {
            try {
                const response = await fetch("<?= site_url('/categories/all') ?>")
                const body = await response.json();

                renderCategories(body.content);

            } catch (exception) {
                console.log(exception);
            }
        }

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
                } else {
                    findCategoriesByOnlineUser();
                }

            } catch (exception) {
                console.log(exception);
            }
        }

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            createCategory();
        });

        window.addEventListener("load", () => {
            findCategoriesByOnlineUser();
        });
    </script>

</body>

</html>