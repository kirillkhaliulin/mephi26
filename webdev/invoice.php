<?php
  session_start();
  if(!isset($_SESSION['name'])) {
    echo("<h2>Вы не вошли в аккаунт! <br /> Через 3 секунды вы будете перенаправлены на страницу логина</h2> <br />");
    echo("<a href='login.html'>Войти в аккаунт</a>");
    echo("<meta http-equiv='refresh' content='3; url=login.html'></meta>");
    die();
  }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Счет за услуги</title>
    <style>
        td, th {
            border: 1px solid white;
            text-align: center;
            padding: 5px;
            background-color: #ebafd7;
        }
        td {
            background-color: #dadada;
        }

        .filters {
            margin-block: 10px;
        }

        th {
            cursor: pointer;
        }

        th:hover {
            background: #689cfc;
            border: 1px solid black;
        }

        th.asc, th.desc {
            background: #fcf268;
        }

        th.asc::after {
            content: ' ↑';
        }

        th.desc::after {
            content: ' ↓';
        }

        button {
            padding: 4px 10px;
            border: 1px solid #313c49;
            background: #f4f4f4;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #949393;
        }

        button:active {
            transform: scale(0.95);
        }

        /* Анимациии */ 
        th {
            transition: all 0.3s;
        }

        button {
            transition: background-color 0.3s, transform 0.2s;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 3px solid #dddddd;
            border-top-color: #333333;
            border-radius: 14px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }


        /* Дополнительные эффекты задания. */
        #calcs td {
            transition: background-color 0.3s;
        }
        #calcs tr:hover td {
            background-color: #ebd9f5;
        }
        #calcs tr.calc-row {
            animation: rowAppear 0.4s ease-out both;
        }
        @keyframes rowAppear {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        let sort = 'operation_date';
        let dir = 'DESC';

        let page = 1;

        let limit = 5;

        function sortBy(column) {
            if (sort === column) {
                if(dir == 'ASC') {
                    dir = 'DESC';
                }
                else {
                    dir = 'ASC';
                }
            }
            else {
                sort = column;
                dir = 'ASC';
            }
            page = 1;
            showSort();
            loadData();
        }

        function showSort() {
            const headers = document.querySelectorAll('th');
            console.log(headers);
            headers.forEach((th) => {
                th.classList = [];
            });

            const curHeader = document.getElementById("header_" + sort);
            if(dir === 'ASC') {
                curHeader.className = 'asc'
            }
            else {
                curHeader.className = 'desc'
            }

        }

        function prevPage() {
            page = page - 1;
            loadData();
        }

        function nextPage() {
            page = page + 1;
            loadData()
        }

        function showPages(totalPages) {
            document.getElementById('page-info').textContent = `Страница ${page} из ${totalPages}`;
            document.getElementById('prev').disabled = page <= 1;
            document.getElementById('next').disabled = page >= totalPages;
        }
        

        function loadData() {
            document.getElementById("calcs").innerHTML = '<tr><td colspan="5"><span class="spinner"></span> Идет загрузка</td></tr>';

            const date_from = document.getElementById('date_from').value;
            const date_to = document.getElementById('date_to').value;

            const params = new URLSearchParams({
                date_from: date_from,
                date_to: date_to,
                sort: sort,
                dir: dir,
                z_max: document.getElementById('z_max').value,
                z_min: document.getElementById('z_min').value,
                page: page,
                limit: limit,
            })

            fetch('api/invoice_service.php?' + params.toString())
            .then((response) => {
                if (response.status === 401) {
                    window.location.href = "login.html";
                }
                else if (response.status === 200) {
                    return response.json();
                }
                else {
                    throw new Error(`Ошибка ${response.status}: ${response.statusText}`);
                }
            })
            .then((data) => {
                showCalcs(data.calcs);
                showPages(data.totalPages);
                document.getElementById('total').innerHTML = `Всего операций: ${data.total}.<b> К оплате: ${data.sum} руб.</b>`;
            })
            .catch((error) => {
                console.log(error);
            })

        }

        function showCalcs(calcs){
            if(calcs.length === 0) {
                document.getElementById("calcs").innerHTML = '<tr><td colspan="5">Ничего не найдено</td></tr>';
            }
            else {
                const html = calcs.map((row, index) => `<tr class="calc-row" style="animation-delay: ${index * 70}ms">
                <td>${row.operation_date}</td>
                <td>${row.x}</td>
                <td>${row.y}</td>
                <td>${row.operation}</td>
                <td>${row.z}</td>
                </tr>`).join('');
                document.getElementById("calcs").innerHTML = html;
            }

        }

        function resetFilter() {
            document.getElementById('date_from').value = '';
            document.getElementById('date_to').value = '';
            document.getElementById('z_max').value = '';
            document.getElementById('z_min').value = '';
            page = 1;
            loadData();
        }
        function applyFilters() {
            page = 1;
            loadData();
        }

        function changeLimit() {
            limit = document.getElementById('limit').value;
            page = 1;
            loadData();
        }
        window.onload = loadData;

        // function loadData() {
        //     // Ожидание ответа
        //     document.getElementById("calcs").innerHTML = '<tr><td colspan="5">Загрузка...</td></tr>';

        //     const date_from = document.getElementById('date_from').value;
        //     const date_to = document.getElementById('date_to').value;

        //     const params = new URLSearchParams({
        //         date_from: date_from,
        //         date_to: date_to
        //     })

        //     // Запрашиваем данные

        //     const xhr = new XMLHttpRequest();

        //     xhr.open('GET', 'api/invoice_service.php?' + params.toString(), true);
        //     xhr.onload = function () {
        //         if (xhr.status === 401) {
        //             window.location.href = "login.html";
        //         }
                
        //         else if (xhr.status === 200) {
        //             const calcs = JSON.parse(xhr.responseText);
        //             showCalcs(calcs);
        //         }
        //         else {
        //             alert(`Ошибка ${xhr.status}: ${xhr.responseText}`)
        //         }
        //     }

        //     xhr.send();
        // }
    </script>
</head>
<body>
    <h1>За прошедший период вы совершили следующие операции</h1>

    <div class="filters">
        <h3>Фильтры</h3>
        <label>C: </label>
        <input type="date" id="date_from"/>
        <label>По: </label>
        <input type="date" id="date_to" />

        <label for="z_max">Результат не больше: </label>
        <input type="number" id="z_max" step="any" />
        <label for="z_min">Результат не меньше: </label>
        <input type="number" id="z_min" step="any" />

        <button onclick="applyFilters()">Применить</button>
        <button onclick="resetFilter()">Сбросить</button>
    </div>
    
    <table>
        <thead>
            <tr>
                <th id="header_operation_date" onclick="sortBy('operation_date')">Время</th>
                <th id="header_x" onclick="sortBy('x')">x</th>
                <th id="header_y" onclick="sortBy('y')">y</th>
                <th id="header_operation" onclick="sortBy('operation')">Операция</th>
                <th id="header_z" onclick="sortBy('z')">Результат</th>
            </tr>
        </thead>
        <tbody id="calcs">
            <tr>
                <td colspan="5">Загрузка...</td>
            </tr>
        </tbody>
    </table>
    <div>
        <button id="prev" onclick="prevPage()">Назад</button>
        <span id="page-info"></span>
        <button id="next" onclick="nextPage()">Вперед</button>
        <select id="limit" onchange="changeLimit()">
            <option value="5">5 элементов</option>
            <option value="10">10</option>
            <option value="20">20</option>
        </select>
    </div>
    <p id="total"></p>
</body>
</html>