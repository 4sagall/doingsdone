<?php

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

//Переменная с названием страницы
$title = "Дела в порядке";

// простой массив проектов
$projects = [ 
    "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"
];

// двумерный массив задач
$tasks = [
    [
        "object" => "Собеседование в IT компании",
        "date" => "01.12.2019",
        "project" => $projects[2],
        'execution' => false
    ],
    [
        "object" => "Выполнить тестовое задание",
        "date" => "21.12.2019",
        "project" => $projects[2],
        "execution" => false
    ],
    [ 
        "object" => "Сделать задание первого раздела",
        "date" => "21.12.2019",
        "project" => $projects[1],
        "execution" => true
    ],
    [  
        "object" => "Встреча с другом",
        "date" => "22.12.2019",
        "project" => $projects[0],
        "execution" => false
    ],
    [ 
        "object" => "Купить корм для кота",
        "date" => "",
        "project" => $projects[3],
        "execution" => false
    ],
    [
        "object" => "Заказать пиццу",
        "date" => "",
        "project" => $projects[3],
        "execution" => false
    ],
    [
        "object" => "Проверить задание по работе с датами",
        "date" => "01.11.2022",
        "project" => $projects[1],
        'execution' => false
    ]
];

?>
