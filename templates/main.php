            <section class="content__side">
                <h2 class="content__side-heading">Проекты</h2>

                <nav class="main-navigation">
                    <ul class="main-navigation__list">

<!-- На месте «название проекта» показывайте содержимое очередного массива. -->
<?php foreach ($projects as $project) : ?>
                        <li class="main-navigation__list-item">
                            <a class="main-navigation__list-item-link  <?php if($id == $project['id']) print(" main-navigation__list-item--active"); ?>" href="index.php?id=<?= $project['id']; ?>"> <?= htmlspecialchars($project['name']); ?></a>
<!-- function counter_tasks -->
                                <span class="main-navigation__list-item-count"> <?php print($project['task_count']); ?>  </span>
                        </li>
<?php endforeach; ?>

                    </ul>
                </nav>

                <a class="button button--transparent button--plus content__side-button"
                   href="pages/form-project.html" target="project_add">Добавить проект</a>
            </section>

            <main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="post" autocomplete="off">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                        <a href="/" class="tasks-switch__item">Повестка дня</a>
                        <a href="/" class="tasks-switch__item">Завтра</a>
                        <a href="/" class="tasks-switch__item">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
			<input class="checkbox__input visually-hidden show_completed <?php if ($show_complete_tasks) " checked" ?> " type="checkbox">
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>

        <table class="tasks">

<!-- Замените все содержимое этой таблицы данными из массива задач. Если у задачи статус «выполнен», то строке с этой задачей добавить класс "task--completed". Если задача из массива выполнена, а переменная $show_complete_tasks равна нулю, то такую задачу в списке мы не показываем (пропуск итерации цикла через ключевое слово continue). -->
<?php foreach($tasks as $task)  : ?>   
    <?php if($task['status'] && $show_complete_tasks) continue; ?>                
                    <tr class="tasks__item task <?php if($task['status']) { print(" task--completed"); } else if(time_left($task['date_end'])) print(" task--important"); ?>">
                        <td class="task__select">
                            <label class="checkbox task__checkbox">
                                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                                <span class="checkbox__text"> <?= htmlspecialchars($task['name']); ?> </span>
                            </label>
                        </td>

                        <td class="task__file">
                            <a class="download-link" href="<?= isset($task['file']) ? $task['file_path'] : "#"; ?>" target="_blank"> <?= isset($task['file']) ? $task['file'] : "file.psd"; ?> </a>
                        </td>
                        
                        <td class="task__date"> <?= htmlspecialchars($task['date_end']); ?> </td>
                    </tr>
<?php endforeach; ?>

 <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице -->
<?php if ($show_complete_tasks) : ?>

<tr class="tasks__item task task--completed">
  <td class="task__select">
    <label class="checkbox task__checkbox">
      <input class="checkbox__input visually-hidden" type="checkbox" checked>
      <span class="checkbox__text">Записаться на интенсив "Базовый PHP"</span>
    </label>
  </td>
  <td class="task__date">10.10.2019</td>

  <td class="task__controls">
  </td>
</tr>
<?php endif; ?>

                </table>
            </main>
