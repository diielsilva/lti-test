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

        async function removeSpent(id) {
            try {
                const response = await fetch("<?= site_url('/spents') ?>", {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify({
                        'spentId': id
                    })
                });

                findSpentsBasedOnOnlineUser();

            } catch (exception) {
                console.log(exception);
            }
        }

        async function updateSpent(id) {
            try {
                const spentId = id;
                const value = document.querySelector(`#update-value-${id}`).value;
                const createdAt = document.querySelector(`#update-date-${id}`).value;
                const categoryId = document.querySelector(`#update-category-${id}`).value
                const description = document.querySelector(`#update-description-${id}`).value;

                const response = await fetch("<?= site_url('/spents') ?>", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify({spentId, value, createdAt, categoryId, description})
                });

                if (response.status !== 200) {
                    console.log('error');
                } else {
                    findSpentsBasedOnOnlineUser();
                }

            } catch (exception) {
                console.log(exception);
            }
        }

        function renderSpents(spents) {
            spentsSection.innerHTML = "";

            if (spents.length > 0) {
                const spentList = document.createElement("ul");

                spents.forEach(spent => {
                    const listItem = document.createElement("li");
                    const spentCard = document.createElement("div");
                    const selectedCategory = availableCategories.filter((category) => category.id === spent.category_id)[0];
                    const removeSpentBtn = document.createElement("button");
                    const updateSpentBtn = document.createElement("button");
                    const inputValue = document.createElement("input");
                    const inputDate = document.createElement("input");
                    const inputCategory = document.createElement("select");
                    const inputDescription = document.createElement("textarea");
                    const selectPlaceholder = document.createElement("option");

                    updateSpentBtn.innerText = "Update Category";
                    updateSpentBtn.onclick = () => {
                        updateSpent(spent.id);
                    }

                    selectPlaceholder.value = "";
                    selectPlaceholder.innerText = "Category";
                    selectPlaceholder.disabled = true;

                    inputValue.id = `update-value-${spent.id}`;
                    inputValue.placeholder = "Value";
                    inputValue.type = "number";
                    inputValue.required = true;
                    inputValue.value = spent.value;

                    inputDate.id = `update-date-${spent.id}`;
                    inputDate.placeholder = "Date";
                    inputDate.required = true;
                    inputDate.value = spent.created_at.split(' ')[0];
                    inputDate.type = "date";

                    inputDescription.id = `update-description-${spent.id}`;
                    inputDescription.placeholder = "Description";
                    inputDescription.required = true;
                    inputDescription.value = spent.description;

                    inputCategory.appendChild(selectPlaceholder);
                    inputCategory.required = true;
                    inputCategory.id = `update-category-${spent.id}`;

                    availableCategories.forEach(category => {
                        const option = document.createElement("option");

                        option.value = category.id;
                        option.innerText = category.name;

                        if (category.id === selectedCategory.id) {
                            option.selected = true;
                        }

                        inputCategory.appendChild(option);
                    });


                    removeSpentBtn.innerText = 'Remove Spent';
                    removeSpentBtn.onclick = () => {
                        removeSpent(spent.id);
                    }

                    spentCard.appendChild(inputValue);
                    spentCard.appendChild(inputDate);
                    spentCard.appendChild(inputCategory);
                    spentCard.appendChild(inputDescription);

                    spentCard.appendChild(removeSpentBtn);
                    spentCard.appendChild(updateSpentBtn);

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