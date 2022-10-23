<?php

/** funcion for count tasks in each project
 * @param array - массив задач
 * @param  string - название проекта
 * @return number 
 * */
function counter_tasks (array $task_list, $project_name) {
    $amount = 0;
    foreach ($task_list as $key => $item) {
        if ($item["project"] == $project_name) {
            $amount++;
        } else continue;
    } 
    return $amount;
};


?>
