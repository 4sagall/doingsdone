<?php

require_once ('helpers.php');
require_once ('data.php');
require_once ('functions.php');

$link = mysqli_connect("127.0.0.1", "root", "", "doingsdone");
mysqli_set_charset($link, charset: "utf8");

$projects = [];
$tasks = [];
$content = '';

