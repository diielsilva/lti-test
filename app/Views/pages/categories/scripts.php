<script>
    const createForm = document.querySelector("#create-form");
    const categoriesContainer = document.querySelector("#categories-container");

    const displayMessage = (message) => {
        const messageContainer = document.querySelector('#message-container');

        //Reseting message
        messageContainer.innerText = '';

        //Displaying message
        messageContainer.innerText = message;

        //Cleaning message after one second
        setTimeout(() => {
            messageContainer.innerText = '';
        }, 1500);
    }

    //Trying to get all categories that belongs to the current authenticated user and load then
    const findByUser = async () => {
        const findResponse = await fetch("<?= site_url('/categories/by-user') ?>");

        //If we could reach the server
        if (findResponse.status === 200) {
            //Get all categories
            const responseBody = await findResponse.json();

            //Render all categories inside the view
            renderCategories(responseBody.content);

            return;
        }

        displayMessage("Couldn't reach the server");
    }

    //Render all categories
    const renderCategories = (categories) => {

        //Reset categories list
        categoriesContainer.innerHTML = '';

        //If there aren't categories, display empty message
        if (categories.length === 0) {
            const message = document.createElement("p");
            message.innerText = "You don't have saved categories";

            categoriesContainer.appendChild(message);

            return;
        }

        //Create list to append inside the categories container
        const categoriesList = document.createElement("ul");

        categories.forEach(category => {
            const listItem = document.createElement("li");
            const card = document.createElement("div");
            const cardTitle = document.createElement("h3");
            const input = document.createElement("input");

            card.classList.add("card");

            cardTitle.innerText = "Category";

            //Adding id, and fulfilling the input to display the category name, it will be used to update later
            input.type = "text";
            input.id = `update-name-${category.id}`;
            input.value = category.name;
            input.placeholder = "Name";
    
            //Creating buttons to append inside category "card"
            const updateButton = document.createElement("button");
            const deleteButton = document.createElement("button");

            updateButton.classList.add("submit-button");
            deleteButton.classList.add("submit-button");

            //Adding behaviors to update category button
            updateButton.innerText = "Update Category";
            updateButton.onclick = () => {
                update(category.id);
            }

            //Adding behaviors to delete category button
            deleteButton.innerText = "Delete Category";
            deleteButton.onclick = () => {
                deleteCategory(category.id)
            };

            //Mounting category card
            card.appendChild(cardTitle);
            card.appendChild(input);
            card.append(updateButton);
            card.appendChild(deleteButton);

            //Adding card to list item
            listItem.appendChild(card);

            //Rendering list item
            categoriesList.appendChild(listItem);
        });

        //Rendering categories
        categoriesContainer.appendChild(categoriesList);

    }

    //Generating correct request body according to request type (create or update)
    const getCreateOrUpdateCategoryRequestBody = (id) => {

        //If is a create request, then return a JSON string without the ID
        if (id === null) {
            const name = document.querySelector("#name").value;

            return JSON.stringify({
                name
            });
        }

        //If is an update request, then return a JSON string with ID
        const name = document.querySelector(`#update-name-${id}`).value;

        return JSON.stringify({
            id,
            name
        });
    }

    //Trying to update a category, base on its ID
    const update = async (id) => {

        const requestBody = getCreateOrUpdateCategoryRequestBody(id);
        const updateResponse = await fetch("<?= site_url('/categories') ?>", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json; charset=utf-8"
            },
            body: requestBody
        });

        //If the category was not updated, display an error message
        if (updateResponse.status !== 200) {
            const responseBody = await updateResponse.json();
            const message = responseBody.message;

            displayMessage(message);

            return;
        }

        //Refresh categories list
        findByUser();
    }

    //Trying to create a category
    const create = async () => {
        const requestBody = getCreateOrUpdateCategoryRequestBody(null);

        //Try to create a category
        const createResponse = await fetch("<?= site_url('/categories') ?>", {
            method: 'POST',
            headers: {
                "Content-Type": "application/json; charset=utf-8"
            },
            body: requestBody
        });

        //If the category was created, refresh the categories list
        if (createResponse.status === 201) {
            findByUser();
            return;
        }

        //If there is any error (category with duplicated name), display message
        const responseBody = await createResponse.json();
        displayMessage(responseBody.message);
    }

    //Try to delete a category
    const deleteCategory = async (id) => {
        const response = await fetch("<?= site_url('/categories') ?>", {
            headers: {
                "Content-Type": "application/json; charset=utf-8"
            },
            method: "DELETE",
            body: JSON.stringify({
                id
            })
        });

        if (response.status === 200) {
            findByUser();
        }
    }

    //Listening events
    createForm.addEventListener("submit", (event) => {
        event.preventDefault();
        create();
    });

    window.addEventListener("load", () => {
        findByUser();
    });
</script>