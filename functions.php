<?php

/** Функция вчисляет количество задач в каждой категории проектов
 * @param array - массив задач
 * @param  string - название проекта
 * @return number 
 * */
function counter_tasks (array $tasks, $project) {
    $amount = 0;
    foreach ($tasks as $key => $value) {
        if ($value['project_id'] == $project) {
            $amount++;
        } else continue; 
    }
    return $amount;
};

/** Функция рассчитывает сколько часов осталось до даты выполнения задачи
 * @param date - функция получает один аргумент в виде даты в произвольном виде
 * полученная дата сравнивается с текущим временем  
 * @return boolean - если до переданной даты осталось 24 часа и меньше, то возвращается true
 */
function time_left ($date) {
    date_default_timezone_set("Europe/Moscow");
    $cur_time = strtotime('now');
    $spec_time = strtotime($date);
    if(floor(($spec_time - $cur_time)/86400) <= 1) return true;
};

