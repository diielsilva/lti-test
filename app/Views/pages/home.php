<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <form id="form">
        <input type="text" name="name" id="name" value="<?= session()->get('online_user')['name'] ?>" required>
        <input type="email" name="email" id="email" value="<?= session()->get('online_user')['email'] ?>" required>
        <input type="password" name="password" id="password" minlength="6" required>
        <button type="submit">Edit User</button>
    </form>

    <script>
        const updateForm = document.querySelector("#form");

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

        const updateUser = async () => {
            try {
                const updateRequestBody = createUpdateUserRequestBody();
                const updateResponse = await fetch("<?= site_url('/users') ?>", {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json; charset=utf-8"
                    },
                    body: updateRequestBody
                });
                const updateResponseBody = await updateResponse.json();

                //VERIFY AND DISPLAY ERRORS WHEN THERE ARE ERRORS, OTHERWISE, DISPLAY SUCCESS MESSAGE
                if (updateResponseBody.errors.length > 0) {
                    alert(updateResponseBody.errors[0]);
                } else {
                    alert(updateResponseBody.content[0]);
                }

            } catch (exception) {
                console.log(exception);
            }
        }

        updateForm.addEventListener("submit", (event) => {
            event.preventDefault();
            updateUser();
        });
    </script>
</body>

</html>