<?php

require_once('functions.php');

$link = mysqli_connect('127.0.0.1', 'root', 'root', 'doingsdone');
mysqli_set_charset($link, charset:"utf8");

if(!$link) {
    print('Ошибка соединения SQL: ' . mysqli_connect_error() . '<br>');
} 
