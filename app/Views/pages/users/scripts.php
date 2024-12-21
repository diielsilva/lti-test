<script>
    const updateForm = document.querySelector("#update-form");
    const deleteButton = document.querySelector("#delete-button");

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

    //Getting name, email, password then convert them to a JSON string
    const createUpdateUserRequestBody = () => {
        const name = document.querySelector("#name").value;
        const email = document.querySelector("#email").value;
        const password = document.querySelector("#password").value;

        return JSON.stringify({
            name,
            email,
            password
        });
    }

    //Trying to update user
    const update = async () => {

        //Getting request body to send in the update user request
        const requestBody = createUpdateUserRequestBody();

        //Try to update user
        const updateResponse = await fetch("<?= site_url('/users') ?>", {
            method: 'PUT',
            headers: {
                "Content-Type": "application/json; charset=utf-8"
            },
            body: requestBody
        });

        //Displaying result message (I am not handling failures here, but it MUST be done in real applications)
        const responseBody = await updateResponse.json();
        const message = responseBody.message;

        displayMessage(message);

    }

    //Trying to delete an user
    const deleteUser = async () => {

        //Try to delete an user
        const deleteResponse = await fetch("<?= site_url('/users') ?>", {
            headers: {
                "Content-Type": "application/json; charset=utf-8"
            },
            method: 'DELETE'
        });

        const responseBody = await deleteResponse.json();

        //If user was deleted, redirect to login page
        if (deleteResponse.status === 200) {
            window.location.href = "<?= site_url('/') ?>";
            return;
        }

        displayMessage(responseBody.message);

    }

    //Listening events
    updateForm.addEventListener("submit", (event) => {
        event.preventDefault();
        update();
    });

    deleteButton.addEventListener("click", deleteUser);
</script>