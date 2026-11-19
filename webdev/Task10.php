<?php
// Задание 10. Хостинг наших файлов на убунту под nginx.
// Делал примерно так:
// sudo apt install nginx php-fpm
// потом скопировал все файлы курсовой в /var/www/html
// в настройках сайта (/etc/nginx/sites-available/default) добавил обработку php:
// location ~ \.php$ {
//     include snippets/fastcgi-php.conf;
//     fastcgi_pass unix:/run/php/php8.5-fpm.sock;
// }
// sudo nginx -t
// sudo systemctl restart nginx
// после этого страницы открываются через http://localhost/имя_файла.php

date_default_timezone_set('Europe/Moscow');

// вывожу инфу о сервере, чтобы было видно, что всё реально работает
$os = php_uname();
$server = $_SERVER['SERVER_SOFTWARE'];
$phpver = PHP_VERSION;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Задание 10</title>
</head>
<body>
<h1>Задание 10 — хостинг на nginx</h1>
<p>Захостил наши курсовые файлы на ubuntu (в virtualbox) под веб сервером nginx.
Ниже видно, что страница действительно выполняется на линуксе под nginx:</p>

Операционная система: <?php echo $os; ?><br>
Веб-сервер: <?php echo $server; ?><br>
Версия php: <?php echo $phpver; ?><br>
Время на сервере: <?php echo date('H:i:s d.m.Y'); ?><br>

<h2>Наши страницы (лежат в /var/www/html)</h2>
<a href="../demo.php">demo.php</a><br>
<a href="../calc.html">calc.html</a><br>
<a href="../login.html">login.html</a><br>
</body>
</html>
