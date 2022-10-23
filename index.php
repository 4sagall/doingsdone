<?php

require_once("helpers.php");
require_once("data.php");
require_once("functions.php");

$page_content = include_template("main.php", [
    "projects" => $projects,
    "tasks" => $tasks,
    "show_complete_tasks" => $show_complete_tasks 
]);

$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "projects" => $projects,
    "title" => "Дела в порядке"
]);

print ($layout_content);

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
 */

?>
