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

                console.log(body);

                //better display it

                const container = document.querySelector("#report-container");
                container.innerText = '';
                let totalOfReports = 0;

                body.content.forEach(report => {
                    totalOfReports += Number.parseFloat(report.value);

                    const card = document.createElement("div");
                    const reportValue = document.createElement("div");
                    const reportDate = document.createElement("div");
                    const reportCategory = document.createElement("div");
                    const reportDescription = document.createElement("div");


                    reportValue.innerText = report.value;
                    reportDate.innerText = report.created_at.split(' ')[0];
                    reportDescription.innerText = report.description;

                    card.appendChild(reportValue);
                    card.appendChild(reportDate);
                    card.appendChild(reportCategory);
                    card.appendChild(reportDescription);

                    container.appendChild(card);
                });

                const totalDiv = document.createElement("div");
                totalDiv.innerText = totalOfReports;

                container.appendChild(totalDiv);

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
                const response = await fetch("<?= site_url('/categories/by-user') ?>", {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    }
                });
                const body = await response.json();

                fullfillCategoriesSelectWithinSpentCreateForm(body.content);


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