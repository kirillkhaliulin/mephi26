<?php
session_start();

if(!isset($_SESSION['login'])){
    echo("Вы не вошли в аккаунт! Через 3 секунды вы будете перенаправлены на страницу логина ");
    echo("<a href='login.html'>Войти в аккаунт</a>");
    echo("<meta http-equiv='refresh' content='3; url=login.html'>");
    die();
}

$host = 'localhost';
$username = 'root';
$password_db = '';
$db = 'calc';

$mysqli = new mysqli($host, $username, $password_db, $db);

if($mysqli->connect_error) {
    die('Ошибка подключения к БД');
}

$query = "INSERT INTO operation (login, operation, x, y, z) VALUES(?, ?, ?, ?, ?)";

$login = $_SESSION['login'];
$operation = $_REQUEST['operation'];
$x = $_REQUEST['x'];
$y = $_REQUEST['y'];

$z = 0;

if($operation === 'plus'){
    $z = $x + $y;
} elseif($operation === 'minus') {
    $z = $x - $y;
} elseif($operation === 'multiply') {
    $z = $x * $y;
}

$result = $mysqli->prepare($query);
$result->bind_param('ssddd', $login, $operation, $x, $y, $z);
$result->execute();

$result->close();
$mysqli->close();

echo($z);
?>
