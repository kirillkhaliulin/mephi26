<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Демо</title>
    <style>
        h2 {
            text-align: center;
            padding-block: 40px;
        }

        .night {
            background-color: black;
            color: white;
        }

        .evening {
            background-color: darkblue;
            color: white;
        }

        .afternoon {
            color: darkgreen;
        }

        .morning {
            background-color: orange;
            color: black;
        }

        .weekend {
            background-color: crimson;
            color: white;
        }
    </style>
</head>
<body>
    <?php
        date_default_timezone_set('Europe/Moscow');

        $now = date('H:i:s');
        echo "<h2>Вы зашли на страницу в $now</h2>";

        $hour = date('H');

        if ($hour < 6) {
            echo "<h2 class='night'>Доброй ночи!</h2>";
        } elseif ($hour < 12) {
            echo "<h2 class='morning'>Доброе утро!</h2>";
        } elseif ($hour < 18) {
            echo "<h2 class='afternoon'>Добрый день!</h2>";
        } else {
            echo "<h2 class='evening'>Добрый вечер!</h2>";
        }

        // date('w') возвращает номер дня недели:
        // 0 — воскресенье, 1 — понедельник, ..., 6 — суббота.
        $weekDays = [
            'воскресенье',
            'понедельник',
            'вторник',
            'среда',
            'четверг',
            'пятница',
            'суббота'
        ];

        $dayNumber = date('w');
        $dayName = $weekDays[$dayNumber];

        echo "<h2>Сегодня $dayName</h2>";

        if ($dayNumber == 0 || $dayNumber == 6) {
            echo "<h2 class='weekend'>Сегодня выходной!</h2>";
        }

        echo "<pre>";
        echo "Ваш браузер: " . $_SERVER['HTTP_USER_AGENT'] . "<br>";
        echo "Ваш IP-адрес: " . $_SERVER['REMOTE_ADDR'] . "<br>";
        echo "Ваш URL: " . $_SERVER['REQUEST_URI'] . "<br>";
        echo "</pre>";

        $phrases = [
            'Не забудьте сохранить файл',
            'Обязательно ставьте точку с запятой',
            'PHP выполняется на сервере!',
            'Кавычки снаружи двойные, а внутри одинарные'
        ];

        $index = rand(0, count($phrases) - 1);
        echo "<h2>Совет дня: " . $phrases[$index] . "</h2>";

        echo "<h2>";

        for ($i = 1; $i <= $hour; $i++) {
            echo "*";
        }

        echo "</h2>";
    ?>
</body>
</html>