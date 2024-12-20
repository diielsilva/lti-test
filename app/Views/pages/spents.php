<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    <form id="create-spent-form">
        <input type="number" name="spent-value" id="spent-value" placeholder="Value" required>
        <input type="date" name="spent-date" id="spent-date" placeholder="Date" required>
        <select id="spent-category" required>
            <option selected disabled value="">Category</option>
        </select>
        <textarea id="spent-description" required></textarea>
        <button type="submit">Create Spent</button>
    </form>

    <section id="spents-section"></section>

    <script>
        const availableCategories = [];
        const createForm = document.querySelector("#create-spent-form");
        const spentsSection = document.querySelector("#spents-section");

        function renderSpents(spents) {
            spentsSection.innerHTML = "";

            if (spents.length > 0) {
                const spentList = document.createElement("ul");

                spents.forEach(spent => {
                    const listItem = document.createElement("li");
                    const spentCard = document.createElement("div");
                    const category = availableCategories.filter((category) => category.id === spent.category_id);

                    spentCard.innerText = `ID = ${spent.id}, Category = ${category[0].name}, Value = ${spent.value}, Description = ${spent.description}, Date = ${spent.created_at}`;

                    listItem.appendChild(spentCard);
                    spentList.appendChild(listItem);

                });

                spentsSection.appendChild(spentList);

            } else {
                const message = document.createElement("p");
                message.innerText = "You don't have saved spents";
                spentsSection.appendChild(message);
            }
        }

        function fullfillCategoriesSelectWithinSpentCreateForm(categories) {
            const select = document.querySelector("#spent-category");

            categories.forEach(category => {
                availableCategories.push(category);
                const option = document.createElement("option");
                option.value = category.id;
                option.innerText = category.name;
                select.appendChild(option);
            });
        }

        async function findCategoriesBasedOnOnlineUser() {
            try {
                const response = await fetch("<?= site_url('/categories/all') ?>", {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    }
                });
                const body = await response.json();

                const hasErrors = body.errors.length > 0;

                if (hasErrors) {
                    alert(body.errors[0]);
                } else {
                    fullfillCategoriesSelectWithinSpentCreateForm(body.content);
                }

            } catch (exception) {
                console.log(exception);
            }
        }

        async function createSpent() {
            const value = document.querySelector("#spent-value").value;
            const created_at = document.querySelector("#spent-date").value;
            const category_id = document.querySelector("#spent-category").value;
            const description = document.querySelector("#spent-description").value;

            try {
                const response = await fetch("<?= site_url('/spents') ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify({
                        value,
                        created_at,
                        category_id,
                        description
                    })
                });

                const body = await response.json();

                if (body.errors.length > 0) {

                } else {
                    findSpentsBasedOnOnlineUser();
                }

            } catch (exception) {
                console.log(exception);
            }
        }

        async function findSpentsBasedOnOnlineUser() {
            try {
                const response = await fetch("<?= site_url('/spents/by-user') ?>");
                const body = await response.json();

                if (response.status === 200) {
                    renderSpents(body.content);
                }

            } catch (exception) {
                console.log(exception);
            }
        }

        createForm.addEventListener("submit", (event) => {
            event.preventDefault();
            createSpent();
        });

        window.addEventListener("load", () => {
            findCategoriesBasedOnOnlineUser();
            findSpentsBasedOnOnlineUser();
        });
    </script>

</body>

</html>