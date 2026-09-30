<?php
session_start();

if(!isset($_SESSION['login'])){
    echo("Вы не вошли в аккаунт! Через 3 секунды вы будете перенаправлены на страницу логина ");
    echo("<a href='login.html'>Войти в аккаунт</a>");
    echo("<meta http-equiv='refresh' content='3; url=login.html'>");
    die();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Калькулятор</title>
</head>
<body>
    <input type="number" id="x" placeholder="x">
    <input type="number" id="y" placeholder="y">

    <button onclick="operation('plus')">Сложить</button>
    <button onclick="operation('minus')">Вычесть</button>
    <button onclick="operation('multiply')">Умножить</button>

    <p>Результат: <span id="result"></span></p>

    <script>
        function operation(operation) {
            let x = document.getElementById('x').value;
            let y = document.getElementById('y').value;

            let request = new XMLHttpRequest();
            request.open('GET', 'operations_service.php?operation=' + operation + '&x=' + x + '&y=' + y, true);
            request.onload = function() {
                document.getElementById('result').innerHTML = request.responseText;
            };
            request.send();
        }
    </script>
</body>
</html>
