<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php require_once(APPPATH . '/Views/templates/header.php'); ?>

    <form id="generate-report-form">
        <input type="date" name="start-date" id="start-date">
        <input type="date" name="end-date" id="end-date">
        <select name="tes" id="category">
            <option value="" selected disabled>Category</option>
        </select>
        <button type="submit">Generate Report</button>
    </form>

    <section id="report-container">

    </section>

    <script>
        const form = document.querySelector('#generate-report-form');


        async function generateReport() {
            try {
                const startDate = document.querySelector('#start-date').value;
                const endDate = document.querySelector('#end-date').value;
                const categoryId = document.querySelector("#category").value;

                const response = await fetch("<?= site_url('/reports') ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify({
                        startDate,
                        endDate,
                        categoryId
                    })
                });

                const body = await response.json();

                //better display it
                if (body.errors.length === 0) {
                    const container = document.querySelector("#report-container");
                    container.innerText = '';

                    body.content.forEach(report => {
                        const div = document.createElement("div");
                        div.innerText = `${report.description} , category = ${report.name}`;

                        container.appendChild(div);
                    });
                }


            } catch (exception) {
                console.log(exception);
            }
        }

        function fullfillCategoriesSelectWithinSpentCreateForm(categories) {
            const select = document.querySelector("#category");

            categories.forEach(category => {
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


        form.addEventListener("submit", (event) => {
            event.preventDefault();
            generateReport();
        });

        window.addEventListener("load", () => {
            findCategoriesBasedOnOnlineUser();
        })
    </script>

</body>

</html>