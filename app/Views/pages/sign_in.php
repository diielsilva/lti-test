<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
</head>

<body>
    <form id="form">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
    </form>

    <script>
        const form = document.querySelector("#form");

        //GET INPUT VALUES AND CREATE A REQUEST BODY
        const createRequestBody = () => {
            const email = document.querySelector("#email").value;
            const password = document.querySelector("#password").value;
            const body = {
                email,
                password
            };

            return JSON.stringify(body);
        }

        //DO AN AJAX CALL TO SERVER (TRY TO SIGN IN)
        const signIn = async () => {
            try {
                const request = createRequestBody();
                const response = await fetch("http://localhost:8080/signin", {
                    headers: {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    method: "POST",
                    body: request
                });
                const body = await response.json();

                if (body.errors.length > 0) {
                    alert(body.errors[0]);
                } else {
                    //IF SIGNED IN, REDIRECT TO /HOME
                    window.location.href = "http://localhost:8080/home";
                }

            } catch (exception) {
                console.log(exception);
            }

        }

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            signIn();
        });
    </script>

</body>

</html>