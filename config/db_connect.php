<?php

    require 'db_config.php';

    $dsn = 'mysql:host='.DB_SERVER.';dbname='.DB_DATABASE.';charset=utf8';
    $opt = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $opt);


?>