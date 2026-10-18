<?php
    session_start();
    if(!isset($_SESSION['name'])) {
        http_response_code(401);
        echo('Вы не вошли в аккаунт!');
        exit;
    }

    $date_from = '2000-01-01'; // очень давно
    $date_to = '2100-01-01'; // очень нескоро

    if(!empty($_GET['date_from'])) {
        $date_from = $_GET['date_from'];
    }

    if(!empty($_GET['date_to'])) {
        $date_to = $_GET['date_to'];
    }

    $host = 'localhost';
    $username = 'root';
    $password_db = '';
    $db = 'calc';

    $mysqli = new mysqli($host, $username, $password_db, $db);

    // Обработка ошибки подключения к БД
    if($mysqli->connect_error) {
        die('Ошибка подключения к БД');
    }

    $login = $_SESSION['login'];

    $query = "SELECT operation_date, x, y, z, operation FROM operation WHERE login = ? AND DATE(operation_date) BETWEEN ? AND ?";

    $types = 'sss';
    $params = array($login, $date_from, $date_to);

    if(isset($_GET['z_max']) && $_GET['z_max'] !== '') {
        $query .= " AND z <= ?";
        $types .= 'd';
        $params[] = (float) $_GET['z_max'];
    }

    if(isset($_GET['z_min']) && $_GET['z_min'] !== '') {
        $query .= " AND z >= ?";
        $types .= 'd';
        $params[] = (float) $_GET['z_min'];
    }

    $result = $mysqli->prepare($query);
    $result->bind_param($types, ...$params);

    $result->execute();

    $calcs_db = $result->get_result();

    $calcs = array();

    while($row = $calcs_db->fetch_assoc()) {
        $calcs[] = $row;
    }

    $result->close();
    $mysqli->close();

    echo json_encode($calcs);