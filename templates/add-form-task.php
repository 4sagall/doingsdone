

<main class="content__main">
  <h2 class="content__main-heading">Добавление задачи</h2>

  <form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="form__row">
      <label class="form__label" for="name">Название <sup>*</sup></label>
      <?php $classname = (isset($errors['name'])) ? " form__input--error" : ""; ?>
                        
      <input class="form__input <?= $classname; ?>" type="text" name="name" id="name" value="" placeholder="Введите название">
      <p class="form__message"><?= $errors['name'] ?? ""; ?></p>
    </div>

    <div class="form__row">
      <label class="form__label" for="project">Проект <sup>*</sup></label>
      <?php $classname = (isset($errors['project'])) ? " form__input--error" : ""; ?>

      <select class="form__input form__input--select <?= $classname; ?>" name="project" id="project">
        <?php foreach ($projects as $project) : ?>
          <option value="<?= $project['id']; ?>"><?= $project['name']; ?></option>
        <?php endforeach; ?>
      </select>
      <p class="form__message"><?= $errors['project'] ?? ""; ?></p>
    </div>

    <div class="form__row">
      <label class="form__label" for="date">Дата выполнения</label>
      <?php $classname = (isset($errors['date_end'])) ? " form__input--error" : ""; ?>

      <input class="form__input form__input--date <?= $classname; ?>" type="date" name="date" id="date" value="" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
      <p class="form__message"><?= $errors['date_end'] ?? ""; ?></p>
    </div>

    <div class="form__row">
      <label class="form__label" for="file">Файл (*.pdf)</label>

      <div class="form__input-file">
        <input class="visually-hidden" type="file" name="file" id="file" value="">

        <label class="button button--transparent" for="file">
          <span>Выберите файл</span>
        </label>
      </div>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="" value="Добавить">
    </div>
  </form>
</main>

<!--
 * 5. Если валидация формы выявила ошибки, то сделать следующее:
 * - для всех полей формы, где найдены ошибки: 
 * - каждому полю с ошибкой добавить класс form__input--error;
 * - в контейнер поля добавить новый тег p.form__message;
 * - в этот тег поместить текст ошибки.
 * 6. Если пользователь выбрал файл, то сохраните его в публичной папке проекта. 
 * 7. Если проверка формы прошла успешно, то сформировать и выполнить SQL-запрос на добавление новой задачи, а затем переадресовать пользователя на главную страницу. 
 * 8. На главной странице для добавленной задачи показывать ссылку на загруженный файл. 
 * 
 * Последовательность действий: 
 * - Проверить, что отправлена форма. Убедиться, что заполнены все обязательные поля. 
 * - Выполнить все проверки. Если есть ошибки заполнения формы, то сохранить их в отдельном массиве. Если ошибок нет, то сохранить новую задачу (учитывая выбранный проект). 
 * - Если к задаче был прикреплен файл, то перенести его в публичную директорию и сохранить ссылку. При успешном сохранении формы, переадресовывать пользователя на главную страницу. 
 * 
 * Список проверок:  
 * - Проверка даты. Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»; Эта дата должна быть больше или равна текущей.  
 * - Проверка проекта. Для идентификатора выбранного проекта проверять, что он ссылается на реально существующий проект. 
 * - Проверка имени задачи. Имя задачи не должно быть пустой строкой.
 *  */ 