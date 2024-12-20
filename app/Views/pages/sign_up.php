<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>
    <form id="form">
        <input type="text" name="name" id="name" placeholder="Name" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit">Sign Up</button>
        <a href="<?= site_url('/') ?>">Already have an account? sign in</a>
    </form>

    <script>
        //CREATE AN USER OBJECT TO SEND TO SERVER
        const createUser = () => {
            const name = document.querySelector("#name").value;
            const email = document.querySelector("#email").value;
            const password = document.querySelector("#password").value;

            return {
                name,
                email,
                password
            };
        }

        //DO AN AJAX CALL TO SERVER (TRY TO CREATE USER)
        const signUp = async () => {
            try {
                const user = createUser();
                const response = await fetch("<?= site_url('/signup') ?>", {
                    headers: {
                        "Content-Type": "application/json"
                    },
                    method: 'POST',
                    body: JSON.stringify(user)
                });
                const body = await response.json();

                if (body.errors.length > 0) {
                    alert(body.errors[0]);
                } else {
                    alert(body.content[0]);
                }

            } catch (exception) {
                console.log(exception);
            }
        }

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            signUp();
        });
    </script>
</body>

</html>