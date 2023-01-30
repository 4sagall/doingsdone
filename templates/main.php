<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <!-- На месте «название проекта» показывайте содержимое очередного массива. -->
            <?php foreach ($projects as $project) : ?>
                <li class="main-navigation__list-item">
                    <a class="main-navigation__list-item-link  <?= ($id == $project['id']) ? " main-navigation__list-item--active" : ""; ?>"
                       href="index.php?id= <?= $project['id']; ?>"> <?= htmlspecialchars($project['name']); ?></a>
                    <span class="main-navigation__list-item-count"> <?php print($project['task_count']); ?>  </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="GET" autocomplete="off">
        <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">
        <input class="search-form__submit" type="submit" name="" value="Искать" title="search">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="index.php?tasks_switch=all" class="tasks-switch__item <?= ($tasks_switch === 'all') ? " tasks-switch__item--active": ""; ?>">Все задачи</a>
            <a href="index.php?tasks_switch=agenda" class="tasks-switch__item <?= ($tasks_switch === 'agenda') ? " tasks-switch__item--active": ""; ?>">Повестка дня</a>
            <a href="index.php?tasks_switch=tomorrow" class="tasks-switch__item <?= ($tasks_switch === 'tomorrow') ? " tasks-switch__item--active": ""; ?>">Завтра</a>
            <a href="index.php?tasks_switch=end" class="tasks-switch__item <?= ($tasks_switch === 'end') ? " tasks-switch__item--active": ""; ?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" <?= ($show_complete_tasks) ? " checked ": ""; ?> type="checkbox">
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <!-- Замените все содержимое этой таблицы данными из массива задач. Если у задачи статус «выполнен», то строке с этой задачей добавить класс "task--completed". Если задача из массива выполнена, а переменная $show_complete_tasks равна нулю, то такую задачу в списке мы не показываем (пропуск итерации цикла через ключевое слово continue). -->
        <?php foreach ($tasks as $task)  : ?>
            <?php if (!$task['status'] && $show_complete_tasks) continue; ?>
            <tr class="tasks__item task <?= $task['status']? " task--completed": ""; ?><?= time_left($task['date_end'])? " task--important": ""; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" <?= ($task['status']) ? " checked ": ""; ?> type="checkbox" value="<?= $task['id']; ?>" >
                        <span class="checkbox__text"> <?= htmlspecialchars($task['name']); ?> </span>
                    </label>
                </td>

                <td class="task__file">
                    <a class="download-link" href="<?= isset($task['file']) ? $task['file_path'] : "#"; ?>"
                       target="_blank"> <?= isset($task['file']) ? $task['file'] : "file.psd"; ?> </a>
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
                <td class="task__controls"></td>
            </tr>
        <?php endif; ?>

        <!-- показывать следующий тег <tr/>, если по запросу $_GET search ни чего не найдено -->
        <?php if (!empty($search) && (empty($tasks) || mysqli_num_rows($tasks) === 0)) : ?>
            <tr class="tasks__item task">
                <td class="task__select">
                    <p>Ничего не найдено по вашему запросу "<?= $search; ?>"</p>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</main>
