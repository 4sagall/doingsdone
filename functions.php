<?php
/** 
 * Функция вычисляет количество задач в каждой категории проектов. @param array - массив задач
 * @param  string - название проекта. @return number 
 */
function counter_tasks (array $tasks, $project) {
    $amount = 0;
    foreach ($tasks as $key => $value) {
        if ($value['project_id'] == $project) {
            $amount++;
        } 
        else continue; 
    }
    return $amount;
};

/** 
 * Функция рассчитывает сколько часов осталось до даты выполнения задачи. @param date - функция получает один аргумент в виде даты в произвольном виде
 * полученная дата сравнивается с текущим временем. @return boolean - если до переданной даты осталось 24 часа и меньше, то возвращается true
 */
function time_left ($date) {
    date_default_timezone_set("Europe/Moscow");
    $cur_time = strtotime('now');
    $spec_time = strtotime($date);
    if(floor(($spec_time - $cur_time)/86400) <= 1) return true;
};

/** 
 * Функция сравнивает переданный в нее id с имеющимися в массиве $projects, т.е. функция проверякт наличие проекта с данным id
 * @param int $pid - фунция получает аргумент и @param array $projects - массив с имеющимися проектами, и сравнивает id проектов с переданным аргументом
 * @return boolean - возвращает булево значение, если совпадение найдено - 1, совпадений нет - 0.    
 */
function getProjectById($pid, $projects) {
    foreach ($projects as $key => $value) {
        if ($value['id'] == $pid) return $pid;           
    }
    return null;
};

/** 
 * Функция для проверки на пустую строку и длины названия задачи
 * @param string $value - принимает строку и @param int $max - целое число, определяет введено ли название и сравнивает длину строки со значением
 * @return string - возвращает строку     
 */
function validateTaskName($value, $max) {
    if(!empty($value)) {
        $len = strlen($value); 
        if($len > $max) return "Имя задачи - не более " . $max . " символов";
    }
    else return null;
};

/** 
 * Функция сравнивает переданный в нее id с имеющимися в массиве $projects, т.е. функция проверякт наличие проекта с данным id
 * @param int $value - фунция получает аргумент и @param array $projects - массив с имеющимися проектами, и сравнивает id проектов с переданным аргументом 
 * В свою очередь функция array_key_exists — проверяет, присутствует ли в массиве указанный ключ или индекс
 */
function validateProjectId($value, $projects) {
    foreach ($projects as $project) {
        if ($value == $project['id']) return null;         
    } 
    return "Указан несуществующий проект";  
};

/** 
 * Функция для валидации даты. Эта дата должна быть больше или равна текущей и соответствовать формату.
 * Для валидации формата используется другая функция - is_date_valid()
 * @param string - принимает один аргумент в виде строки
 * @return возвращает текст ошибкиб либо null, если ошибки нет  
 */
function validateDate($value) {
    date_default_timezone_set("Europe/Moscow");
    $cur_time = strtotime('now');
    $spec_time = strtotime($value);
    if($value) {
        if(!is_date_valid($value)) {
            return "Содержимое поля - дата завершения, должно быть датой в формате ГГГГ-ММ-ДД";
        }
        elseif($cur_time > $spec_time) {
            return "Эта дата должна быть больше или равна текущей";
        }
    }
    else return null;
};

/**
 * Функция для для получения значений из POST-запроса и сохранения введенных пользователем значений в полях формы  
 */
function getPostVal($name) {
    return $_POST[$name] ?? "";
}