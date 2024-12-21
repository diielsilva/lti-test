<script>
    const loginForm = document.querySelector('#login-form');

    //
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

    //Getting email and password inputs, then convert them to a JSON
    const getLoginRequestBody = () => {
        const email = document.querySelector('#email').value;
        const password = document.querySelector('#password').value;
        const body = {
            email,
            password
        };

        return JSON.stringify(body);
    }

    //Trying to authenticate user
    const authenticate = async () => {
        //Get the request body to send in the authentication request
        const requestBody = getLoginRequestBody();

        //Try to authenticate the user
        const authenticateResponse = await fetch("<?= site_url('/login') ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            },
            body: requestBody
        });

        const responseBody = await authenticateResponse.json();
        const hasErrors = responseBody.message !== '';

        if (hasErrors) {
            const message = responseBody.message;

            displayMessage(message);

            return;
        }

        //If user was successfully authenticated, redirect to the users page
        window.location.href = "<?= site_url('/users') ?>";

    }

    //Listening events
    loginForm.addEventListener("submit", (event) => {
        event.preventDefault();
        authenticate();
    });
</script>