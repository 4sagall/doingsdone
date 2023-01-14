<?php
//require_once ('helpers.php');
//require_once ('data.php');
require_once ('functions/models.php');
require_once ('functions/validators.php');
require_once ('functions/view.php');

$link = mysqli_connect("127.0.0.1", "root", "", "doingsdone");
mysqli_set_charset ($link, charset: "utf8");

$projects = [];
$tasks = [];
$content = '';

