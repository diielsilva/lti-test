<script>
    const registerForm = document.querySelector('#register-form');

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

    //Getting name, email and password inputs, then convert them to a JSON string
    const getRegisterRequestBody = () => {
        const name = document.querySelector("#name").value;
        const email = document.querySelector("#email").value;
        const password = document.querySelector("#password").value;

        return JSON.stringify({
            name,
            email,
            password
        });
    }

    //Trying to register an user
    const register = async () => {

        //Get the request body to send in the register request
        const requestBody = getRegisterRequestBody();

        //Try to register user
        const registerResponse = await fetch("<?= site_url('/register') ?>", {
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            },
            body: requestBody
        });

        const responseBody = await registerResponse.json();
        const message = responseBody.message;

        displayMessage(message);
    }

    //Listening events
    registerForm.addEventListener("submit", (event) => {
        event.preventDefault();
        register();
    });
</script>